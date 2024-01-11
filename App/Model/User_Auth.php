<?php

namespace Model;

// User account authentication
// Like, Passsword reset, login, register, isloggedin, 
class User_Auth {
	use Model;
	protected $table = "user";

	// Can create OTP method
	// function otp()

	/**
	 * /\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\
	 * USER AUTHENTICATION
	 * 
	*/

	// Login authentication and user READER
	public static function isLoggedIn($fetch_mode = false){
		// Authenticate the user if it's is a valid user
		if($userid = Session::read(USER_COOKIE)){
			// Instatiate the user class
			$USER = new User;
			if(!$USER = $USER->read('id', $userid)){
				self::logout(true);
			}

			if($fetch_mode){
				$USER->image = $USER->image ?? MALE_PROFILE_IMAGE; // Fix an image for users if they dont have a profile image
				$USER->verification = json_decode($USER->verification);
				$USER->meta_data = json_decode($USER->meta_data);
				$USER->auth = json_decode($USER->auth);
				return $USER;
			}

			return true;
		}

		return false;
	}


	// Logout authentication
	public static function logout(bool $redirect = false){
		Session::delete(USER_COOKIE);
		
		if($redirect){
			redirect("/auth/login/"); // REdirect to the login page
		}
	}


	// Signup
	public function signupAuth($data){
		$data = (object) $data;
		// Validate empty input
		$this->emptyDataCheck($data, ['invite', 'privacy_policy']);

		$data->first_name = trim(strtolower($data->first_name));
		$data->last_name = trim(strtolower($data->last_name));
		$data->email = trim(strtolower($data->email));

		if(!preg_match("/^[a-zA-Z]*$/", $data->first_name)){
			return form_message("Invalid first name.");
		}

		if(!preg_match("/^[a-zA-Z]*$/", $data->first_name)){
			return form_message("Invalid last name");
		}

		// Email validity
		if(!preg_match("/^[a-zA-Z0-9@.-]*$/", $data->email) && !filter_var($data->email, FILTER_VALIDATE_EMAIL)){
			return form_message("Invalid email address");
		}

		// password not less than 6
		if(strlen($data->password) < 6){
			return form_message("Login password cannot be lesser than six digits");
		}

		// Privacy policy check
		if(!isset($data->privacy_policy)){
			return form_message("Accept privacy policy to continue");
		} else if($data->privacy_policy != "on"){
			return form_message(SYSTEM_ERROR);
		}

		// Database queries
		// Email exist check
		if(self::read('email', $data->email)){
			return form_message("Email address already taken");
		}
		
		// After validity
		## Environment API requests
		$userid = Api::random(7);
		// if the userid exist redo another userid
		if(self::read('id', $userid)){
			$userid = Api::random(7);
		}

		// Get timezone from ip
		$geo = Cookie::read("HUGON") ?? Api::ipInfo();
		$ipInfo = json_decode($geo);
		$timezone = $ipInfo->timeZone;
		$ipAddr = $ipInfo->ip;

		$upline_userid = NULL;
		// This user $robot will be helpful for bot user and upline. This will make visible 1. The day this user registered in it upline timezone, 2. The robot counts, 3. The rebate this user has generated for it upline
		$invite = trim($data->invite);
		unset($data->invite); // Remove

		// Extra useful data
		$meta_data = array(
			'robot_count' => 0, // Bot count
			'robot_active' => 0, // Bot count
			'trade_count' => 0, // Bot count
			'trade_active' => 0, // Bot count
			'rebate_count' => 0, // Bot count
			'rebate_amount' => 0, // Bot count
			'referral_count' => 0, // Referral count
			'deposit_count' => 0, // User deposit count
			'deposit_amount' => 0, // User deposit count
			'deposit_active' => 0, // User deposit count
			'withdrawal_count' => 0, // user withdrawal count
			'withdrawal_amount' => 0, // user withdrawal count
			'withdrawal_active' => 0, // user withdrawal count
		);

		// Check upline if valid
		if(!empty($invite)){
			// $invite is the invitation code
			if($UPLINE_DATA = $this->validateUpline($invite)){
				date_default_timezone_set($UPLINE_DATA->timezone); // Set upline default timezone for registeration ref
				$upline_userid = $invite;
				// $meta_data->upline_tzstamp = date("Y-m-d H:i:s");
				// $meta_data->upline_fullname = $UPLINE_DATA->fullname;
				// $meta_data->rebate = 0.00;
			}
		}

		// Set user timezone to get DATES
		date_default_timezone_set($timezone);

		$login = password_hash($data->password, PASSWORD_DEFAULT); // Login password

		// Validate the invite account
		$balance = 50000; // Test account
		$verification = json(array(
			// Send user id as verification key
			'kyc' => array('status' => 0, 'action' => null,), // Kyc verification
			'ban' => array('status' => 0, 'action' => null,), // User bannde
		));

		$auth = json(array("login_id" => null, "secret_key" => Api::random(16, 0, 1)));

		$data = (object)array(
			'email' => $data->email,
			'fullname' => $data->first_name." ".$data->last_name,
			'id' => $userid,
			'upline' => $upline_userid, // Upline user id
			'phone' => $data->phone,
			'timezone' => $timezone,
			'password' => json(array('login' => $login, 'log' => array())), // Log mean, the reset log
			'verification' => $verification,
			'auth' => $auth,
			'balance' => $balance, // Amount in usdt
			'ip' => $ipAddr,
			'tzstamp' => date("Y-m-d H:i:s"),
			'stamp' => STAMP,
			'meta_data' => json($meta_data), // Upline timezone
			'otp' => Api::random(6, 0, 0, 1), // Auto generate OTP
			'otp_expire' => date("Y-m-d H:i:s", strtotime("+30 minutes")), // Let otp code expire in 30 minutes
		);

		$OTP = (object)array(
			'otp' => Api::random(6, 0, 0, 1), // Auto generate OTP
			'otp_expire' => date("Y-m-d H:i:s", strtotime("+30 minutes")), // Let otp code expire in 30 minutes
		);

		
		// This will help to filter bad people from registering, 
		// If their email address is not confirmed, It would not store their information in the database
		$sendMail = true;
// 		$sendMail = false;

		// Delete the existing seeion if it is active
		Session::delete(USER_COOKIE);

		if(HOST_IS_LIVE){
			$fullname = $data->fullname;
			$email = $data->email;
			
			$code = $data->otp;
			$readable_stamp = readable_stamp($data->tzstamp);
			$message = "
			<section style='background: black; border-radius: 6px;font-family: cursive;'>
				<div style='margin-top: 40px;color: #f1f1f1;padding: 26px;font-size: 20px;'>
					<p>Hello ${fullname}, you just created an account with us on ${readable_stamp}.</p>
					<p>Here's the OTP to complete registration</p>
					<h2 style='font-size: 40px;color: blue;margin-top: 40px;'>${code}</h2>
				</div>
			</section>
			";

			if(!Mailer::sender(['email' => $email, 'message' => $message, 'subject' => 'Complete your registeration'])){
				return form_message(SYSTEM_ERROR);
			}
			
// 			$_SESSION[VERIFICATION_DATA] = json($data);
			
// 			Cookie::create(VERIFICATION_DATA, json($data));
			Session::create(VERIFICATION_DATA, (array)$data);
			return form_message("Complete your registration !\n\nPlease wait...", true, ['redirect' => '/auth/verify/']);
		}

		/*Session::create(VERIFICATION_DATA, (array)$data);
		return form_message($data->otp);*/
	}

	// OTP verification
	public function verify($data){
		// Check if the OTP is expired or not: That is the user information is lost or not. // Session can only hold information for max 30 min: Expired OTP
		if(!$USER = Session::read(VERIFICATION_DATA)){
			return form_message("OTP expired");
		}

		// Change data type
		$USER = (object) $USER;

		date_default_timezone_set($USER->timezone); // Set default timezone of the registering user
		$NOW = date("Y-m-d H:i:s"); // User timezone date now
		if($NOW > $USER->otp_expire){
			return form_message("OTP expired");
		}

		// Invalid OTP code
		if($data->code !== $USER->otp){
			return form_message("OTP not correct");
		}

		unset($USER->otp); // Remove the OTP
		unset($USER->otp_expire); // Remove the OTP expire time


		$this->signup($USER); // Create user

		// Check if verifcation data is still set
		// Delete the otp session
		Session::delete(VERIFICATION_DATA);

		// Auto login for the user
		$this->login($USER->id); // Login the user
		
		// Success message
		return form_message("Registeration complete !\n\nPlease wait...", true, ['redirect' => USER_ROOT_PATH.'/dashboard/']);
	}
	

	// Upline validation, in case for referrals
	private function validateUpline($upline_userid){
		// Unset the code cookie if set
		Cookie::delete('invite');

		// User id is used for the Invide id
		if(!$UPLINE_DATA = $this->read('id', $upline_userid)){
			return false;
		}

		return $UPLINE_DATA;
	}


	// Login auth
	public function loginAuth($data){
		$this->type = "login";

		// Check if a user session is active and want to relogin with an active session
		if(Session::read(USER_COOKIE)){
			form_message("You can't re-authenticate. You'll need to log out first.");
		}

		// Validate empty input
		$this->emptyDataCheck($data);

		$email = $data->email;
		$password = $data->password;

		// email validity
		if(!preg_match("/^[a-zA-Z0-9@.-]*$/", $email)){
			form_message("Invalid credentials");
		}

		// Database queries
		// Username and email address exist check
		$USER = self::read('email', $email);
		if(!$USER){
			return form_message("Invalid credentials");
		}

		$verification = json_decode($USER->verification);

		if($verification->ban->status){
			return form_message("Your account is ban. Please contact the customer service.");
		}

		// Verify password
		$USER->password = json_decode($USER->password);
		$verify_pwd = password_verify($password, $USER->password->login);

		if(!$verify_pwd){
			return form_message("Invalid credentials");
		}

		// Redirect user to 2fa
		$USER->auth = json_decode($USER->auth);
		Session::create(G2FA, ['secret_key' => $USER->auth->secret_key, 'userid' => $USER->id, 'email' => $USER->email]); // Create a 2fa key
		return form_message("Open your 2fa app to enter authentication code", true, ['redirect' => '/auth/2fa/']);
	}


	// Google two factor auth
	public function g2Fa($data){

		// Check if user is already logged in
		if(Session::read(USER_COOKIE)){
			return form_message("You are logged in already\n\nWe are redirecting you to your dashboard\nCHEERS...", false, ['redirect' => USER_ROOT_PATH.'/dashboard/']);
		}

		// Check if the 2fa is active
		if(!$auth = (object)Session::read(G2FA)){
			return false;
			// Or an error message
		}

		$G2FA = new \Model\G2fa();
		if(!$confirmation = $G2FA->checkCode($auth->secret_key, $data->code)){
			// Error code here
			return form_message("Code expired\n\nPlease check the new code in the Authenticator app.");
		}

		// Login the user with this id
		$this->login(Session::read(G2FA)['userid']);
		Session::delete(G2FA); // Delete auth session
		return form_message("Successfuly login\n\nWe are redirecting you to your dashboard\nCHEERS...", true, ['redirect' => USER_ROOT_PATH.'/dashboard/']);
	}


	private function signup($data){
		// User account can now be created successfully
		$this->create($data); // Data is user data
		return true;
	}

	private function login($id, $isCreateLog = true){
		Session::create(USER_COOKIE, $id); // Create login session

		if($isCreateLog){
			$this->loginHistory($id); // Create login history
		}

		return true;
	}

	// User meta data
	protected function metaData(string $userid, array $meta){
		$caller_table = $this->table;

		// Accepted key
		$this->table = "user";
		if(!$USER = $this->read('id', $userid)){
			return false;
		}

		$meta_data = json_decode($USER->meta_data);

		// Validate error
		foreach($meta as $key => $val){
			if(!array_key_exists($key, (array)$meta_data)){
				return false;
			}
		}

		foreach($meta as $key => $val){
			$meta_data->$key += $val;
		}

		$this->update(['meta_data' => json($meta_data)], ['id' => $userid]);

		$this->table = $caller_table;
		return true;
	}


	// User login history register
	private function loginHistory($userid){
		if(!$USER = $this->read('id', $userid)){
			return false;
			// Create a login history
		}

		date_default_timezone_set($USER->timezone); // Set user default timezone in order to get the correspond

		$data = array(
			'userid' => $userid,
			'id' => Api::random(5), // Login token
			'info' => json(array(
				'ip' => $_SERVER['REMOTE_ADDR'],
				'agent' => $_SERVER['HTTP_USER_AGENT'],
			)),
			'tzstamp' => date("Y-m-d H:i:s"),
			'stamp' => STAMP,
		);

		$this->table = "userlogin";
		$this->create($data);
		return true;
	}

	private function passwordManager($data){
		$allowed_data = ['source', 'type', 'source', 'password'];
		foreach ($allowed_data as $key){
			if(empty($data->$key)){
				return form_message(SYSTEM_ERROR);
			}
		}

		$allowed_type = ['login', ''];
		$allowed_source = ['reset', 'forgot'];

		if(!in_array($type, $allowed_type)){
			return form_message(SYSTEM_ERROR);
		}

		if(!in_array($source, $allowed_source)){
			return form_message(SYSTEM_ERROR);
		}


		// Create log
		$USER = $this->read("id", $userid);
		$password = json_decode($USER->password);
		$password_log = $password->log;

		date_default_timezone_set($USER->timezone);
		$log = array(
			'source' => $source, // Source means where the reset is been initiated by the user (e.g, reset password in User account setting or On reset password page in auth)
			'info' => json(array(
				'ip' => $_SERVER['REMOTE_ADDR'],
				'agent' => $_SERVER['HTTP_USER_AGENT'],
			)),
			'tzstamp' => date("Y-m-d H:i:s"),
			'stamp' => STAMP,
		);


		$password->log = array_merge($log, (array)$password_log);

		show($password->log);
	}


	// Forgot password reset in auth
	public function forgotPassword($data){
		$sendMail = false;
		$sendMail = true;

		if(empty($data->email)){
			return form_message("Email cannot be empty");
		}

		// Check user with email
		if(!$USER = $this->read('email', $data->email)){
			return form_message("Invalid");
		}

		// Autoset the user timezone
		date_default_timezone_set($USER->timezone);

		// Change user password
		// auto generate password and send to user mail
		$code = Api::random(6, false, false, true);
		$fullname = ucfirst($USER->fullname);
		$tzstamp = date("Y-m-d H:i:s");

		if($sendMail){
			$message = "
			<section style='background: black; border-radius: 6px;'>
				<h2 style='font-size: 30px;color: blue;margin-top: 40px;'>Password reset (".APP_NAME.")</h2>
				<div style='margin-top: 40px;color: #f1f1f1;padding: 26px;font-size: 20px;'>
				<p>Hello ${fullname}, you a password reset occured on your account at ${tzstamp}.</p>
				<p>Here is your new login code is:</p>
				<h2 style='font-size: 40px;color: blue;margin-top: 40px;'>${code}</h2>
				</div>
			</section>
			";

			// User email oh $data->email
			// $email = NOTIFICATION_EMAIL;
			$email = $data->email;
			if(!Mailer::sender(['email' => $email, 'message' => $message, 'subject' => 'Reset password instruction'])){
				return form_message(SYSTEM_ERROR);
			}

			// Reset user password here
			$USER->password = json_decode($USER->password);
			$USER->password->login = password_hash($code, PASSWORD_DEFAULT);

			// Update password to the resent sent password (To email)
			$this->update(['password' => json($USER->password)], ['id' => $USER->id]);
			return form_message("Instruction sent to ".$data->email."\n\nOpen your mail and follow it.", true);
		}

		return form_message(SYSTEM_ERROR);
	}


	// Personal information helper, this helps to reset email, username, bio and order personal informartions
	public function personalInfo($data, $USER){
		$data = (object) $data;

		// Email validation
		// Email validity
		if($data->email !== $USER->email || $data->email === ''){
			if(!preg_match("/^[a-zA-Z0-9@.-]*$/", $data->email) && !filter_var($email, FILTER_VALIDATE_EMAIL)){
				return form_message("Invalid email address");
			}

			if(self::read('email', $data->email)){
				return form_message("Email address already taken");
			}
		}

		// Admin is editing user password
		if(ADMIN_PRIVILEDGE){
			$isSamePassword = true;
			if(isset($data->password)){
				if(!empty($data->password)){
					// Then check user password, no need verifing
					$original_pwd = json_decode($USER->password);
					$login_password = $original_pwd->login;
					if(!password_verify($data->password, $login_password)){
						$isSamePassword = false;
					}

					// Hash password
					$data->password = json(array("login" => password_hash($data->password, PASSWORD_DEFAULT)));
				} else {
					// Remove password field in order not to cause conflict
					unset($data->password);
				}
			}

			// No change made
			if($USER->fullname == $data->fullname && $USER->wallet == $data->wallet && $USER->email == $data->email && $USER->bio == $data->bio && $isSamePassword) return form_message("No change made");
		}

		
		// Update
		$this->update($data, ['id' => $USER->id]);

		// Return result
		if(ADMIN_PRIVILEDGE){
			return form_message(ucfirst($USER->fullname)." account information updated", null, true);
		}

		return form_message("Personal information updated successfully", true);
	}


	// Profile image uploader
	public function profileImageHelper($data, $USER){
		if($upload = Api::imageUpload($_FILES['image'])){
			// Error check while uploading
			if(is_string($upload)){
				return form_message($upload);
			}

			// Else success on upload
			$data->image = $upload->filename; // A new file name was produced after the file was successfully uploaded
		}

		// Delete old image
		@unlink(APP_ROOT.$USER->image);

		// Update profile image
		$this->update($data, ['id' => $USER->id]);
		return true;
	}


	// Password reset function !!!
	public function resetPassword($data, $USER){
		$this->type = "password";
		$data = (object) $data;

		// Validate empty input
		// $this->emptyDataCheck($data);
		$type = 'login';

		// Check if new password doen't mactch
		if(empty($data->new_password)){
			form_message("Password is required");
		}

		if($data->new_password !== $data->new_password_rp){
			form_message("New passwords doen't match");
		}

		if(strlen($data->new_password) < 7){
			form_message("Password cannot be lesser than six digits");
		}

		$USER->password = json_decode($USER->password);

		// Check if the DB password doen't match
		if(!password_verify($data->old_password, $USER->password->$type)){
			form_message("Old password doen't match");
		}

		// Check if the new password match the existing password. That is no change will be made
		if(password_verify($data->new_password, $USER->password->$type)){
			form_message("No change(s) made.");
		}

		// Update the new password
		$USER->password->$type = password_hash($data->new_password, PASSWORD_DEFAULT);
		// Then jSON it 
		$USER->password = json($USER->password);
		

		// UPDATE password
		$this->update(['password' => $USER->password], ['id' => $USER->id]);

		if(ADMIN_PRIVILEDGE){
			return form_message(ucfirst($USER->fullname)." password updated", true);
		}
		return form_message("Reset complete", true);
	}


	// Upload KYC detail from the user
	public function kyc($detail, $USER){
		$proof_image = 2; // Min and max image upload
		$data = new \stdClass();
		$this->table = "kyc";

		// Check if this user have uploaded a KYC detail before
		if($query = $this->read('userid', $USER->id)){
			$detail = json_decode($query->detail);
			$v_status = $detail->status; // verification status

			if($v_status === 'under_review'){
				return form_message("KYC verification is under review");
			} else if ($v_status === 'complete'){
				return form_message("Your account is verified. Cheers.");
			} else if($v_status === 'rejected'){
				// This one will allow the user to reupload
			}
		}

		// Validations
		if(empty($detail->fullname)){
			return form_message("Enter your fullname");
		}
		
		if(empty($detail->phone)){
			return form_message("Enter phone number");
		}

		if(empty($detail->dob)){
			return form_message("Enter date of birth");
		}

		if(empty($detail->address)){
			return form_message("Enter full address");
		}

		if(empty($detail->postal)){
			return form_message("Enter postal code");
		}

		if(empty($detail->identity_type)){
			return form_message("Choose an identity type");
		}

		if(empty($detail->proof_type)){
			return form_message("Choose an address proof type");
		}

		if(count($_FILES['identity_image']['name']) <> $proof_image){
			return form_message("Upload 2 files (Front and back of your identity type)");
		}

		if(empty($_FILES['proof_image']['name'])){
			return form_message("Upload proof of address");
		}


		$detail->proof_image = [];
		// Upload images and send a success message
		for($i = 0; $i < $proof_image; $i++){
			// Extracted
			$uploadImage = array(
				'name' => $_FILES['identity_image']['name'][$i],
            	// 'full_path' => $_FILES['identity_image']['full_path'][$i],
            	'type' => $_FILES['identity_image']['type'][$i],
	            'tmp_name' => $_FILES['identity_image']['tmp_name'][$i],
	            'error' => $_FILES['identity_image']['error'][$i],
	            'size' => $_FILES['identity_image']['size'][$i],
			);

			if($upload = Api::imageUpload($uploadImage)){
				// Error check while uploading
				if(is_string($upload)){
					return form_message($upload);
				}

				// Else success on upload
				$detail->identity_image[] = $upload->filename; // A new file name was produced after the file was successfully uploaded
			}
		}

		// Upload proof of address
		if($upload = Api::imageUpload($_FILES['proof_image'])){
				// Error check while uploading
			if(is_string($upload)){
				return form_message($upload);
			}

			// Else success on upload
			$detail->proof_image = $upload->filename; // A new file name was produced after the file was successfully uploaded
		}


		// Ready for upload
		$data->id = Api::random(6);
		$data->userid = $USER->id;
		$data->stamp = STAMP;
		$data->tzstamp = TZ_STAMP;
		$detail->status = 'under_review'; // Complete, rejected, under_review
		$detail->approved_stamp = null;
		$data->detail = json($detail);
		
		$this->create($data);
		return form_message("KYC detail uploaded successfully. Please wait for verification", true);
	}


	// Delete USER
	public function deleteAccount($data, $USER){
		// Get all tables
		$TABLE = json_decode(DB_TABLES);
		unset($TABLE[0]); // Remove the admin table here
		// Remove user from there
		foreach ($TABLE as $TABL) {
			$this->table = $TABL;
			$KEY = 'userid';
			if($TABL === 'user') $KEY = 'id'; // The reason is user's table doesn't have any colum as userid, The userid is named id in the users table. In order not to cause conflict, hence the key is changed
			if(!$this->delete([$KEY => $USER->id])){
				return form_message("Sorry, an error occured while deleting the user's data from ".strtoupper($TABL));
			}
		}

		return form_message(ucfirst($USER->fullname)." account deleted successfully", true, ['reload' => true]);
	}

	public function disableAccount($data, $USER){
		$verify_text = "";
		$USER->verification = json_decode($USER->verification);
		// $USER->verification->ban->status =  1;
		// $USER->verification = json($USER->verification);

		if($USER->verification->ban->status){
			$USER->verification->ban->status =  0;
			$verify_text = "active";
		} else if(!$USER->verification->ban->status){
			$USER->verification->ban->status =  1;
			$verify_text = "disabled";
		}

		$USER->verification->ban->action = ['stamp' => STAMP, 'tzstamp' => date("Y-m-d H:i:s")];

		// Update DATA detail
		$this->update(['verification' => json($USER->verification)], ['id' => $USER->id]);

		// Change user's account verification status
		return form_message(ucfirst($USER->fullname)." account is now ${verify_text}", true);
	}


	private function verification($type, $USER_ID){
		// qallowed
		$allowed = ['kyc', 'ban', 'email'];

	}
}