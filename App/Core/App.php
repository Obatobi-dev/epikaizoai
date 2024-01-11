<?php

// Deny direct access to file
defined("ISLOADED") OR die("{{Access denied}}");

// Page loading App
class App {
	protected $page = "", $controller = "_404", $method = "index";

	public function loadController(){

		$url = $this->filterUrl();

		$USER_ROOT_PATH = trim(USER_ROOT_PATH, "/"); // App users path
		if($USER_ROOT_PATH == $url[0]){
			$url[0] = USER_ROOT;
		}

		$open_file = APP_ROOT."/App/Controller/".ucfirst($url[0]).".php";

		/**
		 * 
		 * $url[0] serves as the class, $url[1] serves as the method called, the rest $url[] serves as extra data to be parsed as a parameter to the method $url[1]
		*/

		if(!file_exists($open_file)){
			$this->page = APP_ROOT."/App/Controller/$this->controller.php";
			$url[0] = str_replace("-", "_", $url[0]);
		} else {
			$this->page = $open_file;
			$this->controller = $url[0];
			unset($url[0]);
		}

		// Load page controller
		require $this->page;

		// Load controller from the Loaded page
		$mycontroller = ('\Controller\\'.$this->controller);
		$mycontroller = new $mycontroller;

		if(!empty($url[1])){
			$loadedMethod = str_replace("-", "_", $url[1]);

			if(method_exists($mycontroller, $loadedMethod)){
				$this->method = $loadedMethod;

				unset($url[1]);
			}
		}

		$url = array_values($url);
		call_user_func_array([$mycontroller, $this->method], $url);
	}

	private function filterUrl(){
		// $URL = strtolower($_GET['name'] ?? DEFAULT_PAGE);
		$URL = ($_GET['name'] ?? DEFAULT_PAGE);
		$URL = explode("/", trim($URL,"/"));
		return $URL;
	}
}