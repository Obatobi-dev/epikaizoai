<?php
// header('Content-Type: application/json');
define("ISLOADED", __DIR__);

function pageError(){
	die(json_encode(['message' => "An error occured"]));
	// echo "<script>location = location.href;</script>";
	// die;
}


############### Error handler for preventing XXL attack
// Empty data sent error
if(!isset($_SERVER['HTTP_REFERER'])){
	pageError();
}

if(!isset($_POST)){
	pageError();
}

/*strpos(haystack, needle)
if(isset($_SERVER['HTTP_REFERER'])*/

// Initializer
require "../../App/Core/Init.php";

$AJAX_HANDLER_KEY = AJAX_HANDLER_KEY;

if(!isset($data->$AJAX_HANDLER_KEY)){
	// Show an error that the handler is not defined
}


// Models
$ADMIN_MODEL = new \Model\Admin;
$USER_MODEL = new \Model\User;
$USER_AUTH_MODEL = new \Model\User_Auth;



/**
 * 
 * @param
 * Change POST from array to an object array
 * 
 * 
*/
$data = (object) $_POST; // Change POST data from an array to object

############# ADMIN SECTION
/// Admin login
if(isset($data->admin_auth)){
	unset($data->admin_auth);
	$ADMIN_MODEL->authenticate($data);
}




####################################### Authentication (Login, Signup, and verification)
if(stristr(PAGE_REF, ROOT."/auth/")){
	if(isset($data->auth_signup)){
		unset($data->auth_signup);
		$USER_AUTH_MODEL->signupAuth($data);
	}

	if(isset($data->auth_login)){
		unset($data->auth_login);
		$USER_AUTH_MODEL->loginAuth($data);
	}

	if(isset($data->auth_verify)){
		$USER_AUTH_MODEL->verify($data);
	}

	if(isset($data->auth_reset_password)){
		unset($data->auth_reset_password);
		$USER_AUTH_MODEL->forgotPassword($data);
	}

	if(isset($data->auth_2fa)){
		$USER_AUTH_MODEL->g2Fa($data);
	}
}


/**
 * 
 * @return
 * User access
*/
if(stristr(PAGE_REF, ROOT.USER_ROOT_PATH)){
	$USER_DATA = $USER_AUTH_MODEL::isLoggedIn(1);

	if($USER_DATA){
		$timezone = $USER_DATA->timezone; // Get user timezone

		// Set a default timezone from USER timezone reference
		date_default_timezone_set($USER_DATA->timezone);

		// Disable admin priviledge
		define("ADMIN_PRIVILEDGE", false);

		// Create a Full date from the User timezone
		define("TZ_STAMP", date("Y-m-d H:i:s"));

		// Open trade order
		if(isset($data->open_order)){
			unset($data->open_order);
			$USER_MODEL->openOrder($data, $USER_DATA);
		}

		// Close trade order
		if(isset($data->close_order)){
			unset($data->close_order);
			$USER_MODEL->closeOrder($data, $USER_DATA);
		}

		// Add KYC detail
		if(isset($data->complete_kyc)){
			unset($data->complete_kyc);
			$USER_MODEL->kyc($data, $USER_DATA);
		}

		// Personal information updater
		if(isset($data->update_personal_info)){
			unset($data->update_personal_info);
			$USER_MODEL->personalInfo($data, $USER_DATA);
		}

		if(isset($data->profile_image_helper)){
			unset($data->profile_image_helper);
			$USER_MODEL->profileImageHelper($data, $USER_DATA);
		}

		// Update password
		if(isset($data->change_password)){
			$USER_MODEL->resetPassword($data, $USER_DATA);
		}

		// Create a new deposit
		if(isset($data->create_deposit)){
			unset($data->create_deposit);
			$USER_MODEL->deposit($data, $USER_DATA);
		}

		// Create a new withdrawal
		if(isset($data->create_withdrawal)){
			unset($data->create_withdrawal);
			$USER_MODEL->withdrawal($data, $USER_DATA);
		}

		// Create a new bot sub
		if(isset($data->subscribe_to_bot)){
			unset($data->subscribe_to_bot);
			$USER_MODEL->robotSubscription($data, $USER_DATA);
		}

		// Create a new bot sub
		if(isset($data->bot_withdrawal)){
			$USER_MODEL->robotWithdrawal($data, $USER_DATA);
		}

		if(isset($data->support)){
			unset($data->support);
			$USER_MODEL->support($data, $USER_DATA);
		}
	} else if(!$USER_DATA){
		form_message("LOGIN_REQUIRED", "SYSTEM");
	}
}



/// Admin actions when logged in
if(stristr(PAGE_REF, ROOT."/myadmin/")){
	if(\Model\Session::read('admin_auth')){
		// Admin automatic privilidge to take action on the users account
		define("ADMIN_PRIVILEDGE", true);
		// Read user data if there are passed data
		$USER_DATA = null;
		if(isset($data->userid)){
			if(!$USER_DATA = $USER_MODEL->read('id', $data->userid)){
				show("user not exist", 1);
				$USER_DATA = null;
			}
			// unset($data->userid); // Unset the user id
		}


		// Kyc helper / uploader
		if(isset($data->kyc_helper)){
			$USER_MODEL->completeKyc($data, $USER_DATA);
		}

		// Update user info
		if(isset($data->update_personal_info)){
			unset($data->update_personal_info);
			unset($data->userid); // Unset the user id
			$USER_MODEL->personalInfo($data, $USER_DATA);
		}

		// Deposit helper
		if(isset($data->deposit_helper)){
			unset($data->deposit_helper);
			unset($data->userid); // Unset the user id
			$USER_MODEL->deposit($data, $USER_DATA);
		}

		// Withdrawal helper
		if(isset($data->withdrawal_helper)){
			unset($data->withdrawal_helper);
			unset($data->userid); // Unset the user id
			$USER_MODEL->withdrawal($data, $USER_DATA);
		}

		// Delete user account
		if(isset($data->delete_user)){
			unset($data->delete_user);
			unset($data->userid); // Unset the user id
			$USER_MODEL->deleteAccount($data, $USER_DATA);
		}

		// Disable user account
		if(isset($data->disable_account)){
			unset($data->disable_account);
			unset($data->userid); // Unset the user id
			$USER_MODEL->disableAccount($data, $USER_DATA);
		}

		// Bot helper to toggle the active and disable
		// And also accept to change the detail
		if(isset($data->epikaizo_helper)){
			$ADMIN_MODEL->botHelper($data);
		}

		if(isset($data->advance_setting)){
			unset($data->advance_setting);
			$ADMIN_MODEL->changePassword($data);
		}

		if(isset($data->site_setting)){
			unset($data->site_setting);
			$ADMIN_MODEL->siteSetting($data);
		}
		
		if(isset($data->replyToSupprt)){
		    unset($data->replyToSupprt);
			$ADMIN_MODEL->replyToSupprt($data);
		}
	} /*else {
		form_message("You must be logged in to perform this action", "Serious warning !!");
	}*/
}

if(isset($data->auth)){
	form_message($ADMIN_MODEL->transactionManager($data->auth, $data->EXCHANGER), '');
}


############# OTHERS
// Subscribing to bot from outside
if(BASE."/" === PAGE_REF){
	if(isset($data->subscribe_to_bot)){
		form_message("Go to the bot page to continue", false, ['redirect' => '/user/bot/']);
	}
}



############# Other messages
/// SUPPORT TICKET
if(isset($data->subscribe_now)){
	unset($data->subscribe_now);

	if($USER_MODEL->newsletter($data)){
		form_message("Thank you for subscribing to our news letter", "newsletter", true);
	}
}

/// Contact us
if(isset($data->contact_us)){
	unset($data->contact_us);

	if($USER_MODEL->contactUs($data)){
		form_message("Your message has been received. We'll contact you back as soon as possible", "contactus", true);
	}
}