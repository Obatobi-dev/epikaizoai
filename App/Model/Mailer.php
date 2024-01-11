<?php

namespace Model;

use PHPMailer\PHPMailer\PHPMailer;
require_once APP_ROOT."/App/3rd/PHPMailer/PHPMailer.php";
require_once APP_ROOT."/App//3rd/PHPMailer/SMTP.php";
require_once APP_ROOT."/App//3rd/PHPMailer/Exception.php";

class Mailer extends PHPMailer
{
	public function config(){
		$this->isSMTP();
		$this->SMTPAuth = true;
		$this->Host = MAILER_HOST;
		$this->Port = MAILER_PORT;
		$this->SMTPSecure = MAILER_SECURE_TYPE;
		$this->Username = MAILER_USERNAME;
		$this->Password = MAILER_PASSWORD;

		// Exra settings
		$this->isHTML(true);
		$this->setFrom(MAILER_SETFROM, APP_NAME);
	}

	######################### Mailer
	public static function sender(array $data){
		if(!is_object($data)){
			$data = (object) $data;
		}

		$mailer = new Mailer;
		$mailer->config();
		$mailer->Subject = $data->subject ?? "From ".APP_NAME;
		$mailer->Body = $data->message ?? "This is a hi";
		$mailer->addAddress($data->email ?? "", $data->name ?? "");

		if(!$mailer->send()){
			return false;
		}

		return true;
	}
}