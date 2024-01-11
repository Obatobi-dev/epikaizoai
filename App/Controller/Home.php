<?php

namespace Controller;

class Home
{
	use MainController;

	public function index()
	{
		$this->view("home");
	}
}