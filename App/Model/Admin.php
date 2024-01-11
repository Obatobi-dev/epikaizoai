<?php

namespace Model;

class Admin {
	use Model;

	protected $table = "admin", $type = "admin";
	public $refkey;

	public function authenticate($data){
		$this->type = "admin_authentication";

		// Validate empty input
		$this->emptyDataCheck($data);

		$username = $data->username;
		$password = $data->password;

		// username validity
		if(!preg_match("/^[a-zA-Z0-9@.-]*$/", $username)){
			form_message("Invalid username");
		}

		// Database queries
		// Username and email address exist check
		$userdata = self::read('username', $username);
		if(!$userdata){
			form_message("Invalid / User not exist");
		}

		// Verify password
		$verify_pwd = password_verify($password, $userdata->password);

		if(!$verify_pwd){
			form_message("Invalid password");
		}

		// Create a login history
		// $this->userLoginHistory($userdata->userid);
		// log user into the APP
		$_SESSION['admin_auth'] = time();
		return form_message("Successful", true, ['reload' => true]);
	}
	

	public function changePassword($data){
		// Validate empty input
		$this->emptyDataCheck($data, ['change_password']);

		// Check if new password doen't mactch
		if(empty($data->new_password)){
			form_message("New password is required");
		}

		if($data->new_password !== $data->newpassword_rp){
			form_message("New passwords doen't match");
		}

		if(strlen($data->new_password) < 7){
			form_message("Password cannot be lesser than six digits");
		}
		// Check if the DB password doen't match
		if(!password_verify($data->old_password, ADMIN_PASSWORD)){
			form_message("Old password doen't match");
		}

		// Check if the new password match the existing password. That is no change will be made
		if(password_verify($data->new_password, ADMIN_PASSWORD)){
			form_message("No change(s) made.");
		}

		$password = password_hash($data->new_password, PASSWORD_DEFAULT);
		

		// UPDATE password
		$this->update(['password' => $password], ['username' => ADMIN_USERNAME]);
		return form_message("Reset complete", true);
	}


	public function updateContact($data){
		$data = json($data);

		$this->update(['contact' => $data], ['username' => ADMIN_USERNAME]);
		return form_message("change made", true);
	}

	public function siteSetting($data){
		$data->rebate = floatval($data->rebate);
		$data = json($data);

		$this->update(['sitesetting' => $data], ['username' => ADMIN_USERNAME]);
		return form_message("change made", true);
	}
	
	public function replyToSupprt($data){
	    $this->table = "support";
	    $data = (object) $data;
	    
		if(!$query = $this->read('id', $data->id)){
			return form_message("Support not found");
		}
		
		if(empty($data->reply)){
		    return form_message("Enter a message to reply ...");
		}
		
		$detail = json_decode($query->detail);
		$detail->reply = $data->reply;
		$detail->isReplied = true;
		$detail->repliedon = STAMP;
		
		$this->update(['detail' => json($detail)], ['id' => $data->id]);
		return form_message("Reply sent", true);
	}



	// Bot helper to toggle active
	public function botHelper($data){
		// Have a data of type
		if(!in_array($data->type, ['status', 'detail'])){
			return form_message("Invalid bot");
		}
		// Check if the user have uploaded DATA
		$this->table = "bot";
		if(!$DATA = $this->read('id', $data->id)){
			return form_message("Invalid bot");
		}

		// Change the user active status of bot
		if($data->type === 'status'){
			$verify_text = "";
			$ACTIVE = null;
			if($DATA->active){
				$ACTIVE = false;
				$verify_text = "disabled";
			} else if(!$DATA->active){
				$ACTIVE = true;
				$verify_text = "active";
			}

			// Update DATA detail
			$this->update(['active' => $ACTIVE], ['id' => $DATA->id]);

			// Change user's account verification status
			return form_message("Bot version ".$DATA->version." is now ${verify_text}", true);
		} else if($data->type === 'detail'){
			$allowed = ['min', 'max', 'return', 'lock_duration'];
			// Remove unwanted key in this array
			foreach($data as $key => $val){
				if(!in_array($key, $allowed)){
					unset($data->$key);
				}
			}

			foreach($data as $key => $val){
				if(!is_numeric($val) || empty($val)){
					return form_message(str_replace("_", " ", ucfirst($key." can not be empty and must be a number")));
				}
			}

			$data = json($data);

			// No changes made check
			if($data === $DATA->detail){
				return form_message("No change made");
			}

			// Update DATA detail
			$this->update(['detail' => $data], ['id' => $DATA->id]);
			return form_message("Bot version ".$DATA->version." is updated", true);
		}
	}
}