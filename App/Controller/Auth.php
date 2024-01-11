<?php

namespace Controller;

class Auth
{
	use MainController;

	public function index($id = null, $key = null)
	{
		if($id == null || !in_array($id, ['register', 'login', 'reset-password', 'verify', '2fa'])){
			redirect();
		}

		$secret = 'XVQ2UIGO75XRUKJO';
		$code = '615204';
		$g = new \Model\G2fa();
/*
		echo 'Current Code is: ';
echo $g->getCode($secret);
if ($g->checkCode($secret, $code)) {
    echo "YES \n";
} else {
    echo "NO \n";
}*/

/*$secret = $g->generateSecret();
echo "Get a new Secret: $secret \n";
echo "The QR Code for this secret (to scan with the Google Authenticator App: \n";*/


		$this->extraData = ['mode' => $id];
		$this->view("user/auth");
	}
}