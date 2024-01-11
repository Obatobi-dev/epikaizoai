<?php
// declare(strict_types=1);
// Deny direct access to file
defined("ISLOADED") OR die("{{Access denied}}");

// Vendor Autoloader
spl_autoload_register("myAutoLoader");
function myAutoLoader($classname){
	$classname = explode("\\", $classname);
	$classname = end($classname);

	require APP_ROOT."/App/Model/".ucfirst($classname).".php";
}

// Create session
session_set_cookie_params(3600 * 24, "/", null, true, true);
@session_name("epai_token");
session_start();
session_regenerate_id();

require "Config.php";
require "Function.php";
require "Database.php";
require "Model.php";
require "DBTable.php"; // Run this Database table creator once, in order not to stress the database to do more work, if you later have a large database data it may crash your code due to heavyness in data
require "Controller.php";
require "App.php";
require "ConfigManual.php";

DEBUG ? ini_set('display_errors', 1) : ini_set('display_errors', 0);