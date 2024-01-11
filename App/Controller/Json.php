<?php

namespace Controller;

class Json
{
	use MainController;
	
	private $versions = [1, 2, 3, 4, 5, 6];

	// API call
	public function index($table, $ref = null, $piece = null){
		$ADMIN = new \Model\Admin; // Instantiate the admin to be able to access the MODEl functions
		$table = strtolower($table);
		$isValidTable = true;

		$DB_TABLES = json_decode(DB_TABLES); // From config
		$API_RETURN = json([]); // Default as empty inc ase the api call is b=not valid or no result is been returned
		if(!in_array($table, array_values($DB_TABLES))){
			$isValidTable = false;
			// $API_RETURN = json(array('message' => 'api call is invalid / irrelevant. Something is not right somewhere. Please check your call !!!'));
		}

		if($isValidTable){
			$result = $ADMIN->findAll($table); // Call the MODEL function to find all data in a particular table

			$API_RETURN = [];
			if(!is_null($ref)){
				$result = json_decode($result);
				$API_RETURN = [];
				foreach ($result as $data){
					// Sort seach
					if(!is_null($piece)){
						// This will sort search array key and value
						if(array_key_exists($ref, (array) $data)){
							if($data->$ref == $piece){
								$API_RETURN[] = $data;
							}
						} else {
							/*$API_RETURN = json(array('message' => 'api call is invalid / irrelevant. Something is not right somewhere. Please check your call !!!'));*/
						}
					} else {
						// Single search of data
						// This condition will help to find piece of DATA in a large portion by just searching for the PARSE value
						// It is a sort search
						if(array_search($ref, (array) $data)){
							$API_RETURN[] = $data;
						}
					}
				}

				$API_RETURN = json($API_RETURN); // Turn to a JSON
			} else {
				$API_RETURN = $result; // Result already in JSON format
			}
		}

		echo $API_RETURN;
	}
}