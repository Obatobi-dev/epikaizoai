<?php

namespace Controller;

class Page
{
	use MainController;

	public function index($id = null)
	{
		if($id == null || !in_array($id, ['contact', 'about', 'privacy-policy'])){
			redirect();
		}
		$this->extraData = ['mode' => $id];
		$this->view("Page/${id}");
	}
}