<?php

namespace Model;

// Deny direct access to file
defined("ISLOADED") OR die("{{Access denied}}");

use PDO;

Trait Database
{
	private $host = DB_HOSTNAME, $user = DB_USERNAME, $password = DB_PASSWORD, $dbName = DB_NAME;

	private function connect(){
		try {
			$conn = new PDO("mysql:host=$this->host;dbname=$this->dbName",  $this->user, $this->password);
			$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $conn;
		} catch (PDOException $e) {
			// If connection fails show message
			result($e->getMessage(), true);
		}
	}

	protected function query($query, $data = []){
		$con = $this->connect();
		$stm = $con->prepare($query);
		
		// Destroy connection
		// $con = null;
		$check = $stm->execute($data);
		if($check)
		{
			$result = $stm->fetchAll();
			if(is_array($result) && count($result))
			{
				return $result;
			}
		}
		
		// De
		return false;
	}

	public function __destruct(){
		// echo "Spoilt";
	}
}