<?php
namespace Controller;

use \Model\User as Auth;

if(!$GLOBALS['USERDATA'] = Auth::isLoggedIn(1)){
	Auth::logout(true);
}

class User
{
	use MainController;
	
	public function index($id = 'dashboard', $page_1 = null, $page_2 = null)
	{
		$data = $GLOBALS['USERDATA']; // Get user logged in data // In an object format

		// Ban status
		if($data->verification->ban->status){
			$this->extraData = array('mode' => 'ban');$this->view("user/auth");die;
		}

		// Set default timezone for each user
		date_default_timezone_set($data->timezone);

		define("USER_PAGE_STAMP", date("Y-m-d H:i:s"));
		define("USER_PAGE_STAMP_DATE", date("Y-m-d"));
		define("YEAR_18_AGO", date("Y-m-d", strtotime("-18 year")));

		$data->USER_MODEL = new \Model\User; // Instantiate user model for external use in the page.

		$this->extraData = $data; // User data
		// Id will help to show page // This was create because of multiple CLASS METHOD creation
		$this->view(USER_ROOT."/${id}");

	}
}