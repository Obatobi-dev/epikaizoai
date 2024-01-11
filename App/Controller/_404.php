<?php

namespace Controller;

// Deny direct access to file
defined("ISLOADED") OR die("{{Access denied}}");

class _404
{
	use MainController;
	
	public function index()
	{
		$this->view("404");
	}
}