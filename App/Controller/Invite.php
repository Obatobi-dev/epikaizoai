<?php

namespace Controller;

class Invite
{
	use MainController;

	public function index($id)
	{
		\Model\Cookie::create("invite", $id);
		redirect("/auth/register/");
	}
}