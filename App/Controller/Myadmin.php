<?php
namespace Controller;
use \Model\Admin as Auth;

class Myadmin
{
	use MainController;
	
	public function index($id = 'dashboard', $page_1 = null)
	{
		// Id will help to show page even if the method is not available
		$this->extraData['page_1'] = $page_1;
		
		$this->view("admin/${id}");
	}
}

if(!\Model\Session::read('admin_auth')){
	$cont = new \Controller\Myadmin;
	$cont->view("admin/auth");
	die;
}