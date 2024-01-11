<?php

namespace Model;

// Deny direct access to file
defined("ISLOADED") OR die("{{Access denied}}");

class Cookie
{
	public static function create($name = null, $data = null, $exp = "1 day"){
		setcookie($name, $data, strtotime("+${exp}"), ROOT."/", null, true, true);
		return true;
	}

	public static function read($name = null){
		return $_COOKIE[$name] ?? null;
	}

	public static function delete($name = null){
		if(isset($_COOKIE[$name])){
			setcookie($name, null, strtotime("-2 day"), ROOT."/", null, true, true);
			return true;
		} else {
			return false;
		}
	}
}