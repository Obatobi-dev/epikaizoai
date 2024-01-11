<?php

namespace Controller;

// Deny direct access to file
defined("ISLOADED") OR die("{{Access denied}}");

Trait MainController
{
	protected $extraData = [];
	public function view($file)
	{
		$extradata = (array)$this->extraData;
		
		// Page extra data from the Controller
		!$extradata == null ? $extradata: $extradata = [];
		extract($extradata);

		$title = $title ?? "Our website Default";

		// Main viewing file
		$file = explode("/", $file);
		$filename = [];
		foreach($file as $fileName){
			$filename[] = ucfirst($fileName);
		}

		$filename = implode("/", $filename); // After changing to upper case

		$FILE_PATH = APP_ROOT."/App/Views/${filename}.php";
		
		// If the Controller cannot find the viewing file. Load the 404 default page
		if(!file_exists($FILE_PATH)){
			$FILE_PATH = APP_ROOT."/App/Views/404.php";
		}

		// Render
		require $FILE_PATH; // Main view
	}
}