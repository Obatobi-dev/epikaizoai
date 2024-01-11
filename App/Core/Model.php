<?php

namespace Model;

// Deny direct access to file
defined("ISLOADED") OR die("{{Access denied}}");

Trait Model 
{
	use Database;

	public $limit 		= 30;
	public $offset 		= 0;
	public $order_type 	= "desc";
	public $order_column = "sn";

	###################### Select all
	public function findAll($table = null)
	{
		if($table == null) $table = $this->table;
		$query = "SELECT * FROM $table ORDER BY $this->order_column $this->order_type";

		$remove = ['password', 'withdrawalpin'];
		if($query = $this->query($query)){
			foreach($query as $num => $data){
				// $data = (array) $data;
				// show(array_keys((array)$data));
				foreach($data as $key => $val){
					if(in_array($key, $remove)){
						unset($query[$num]->$key);
					}
				}
			}
		}

		return json($query? $query:[]);
	}

	public function findSingle($table = null, array $ref = []){
		$this->table = $table;
		if($table == null) $table = $this->table;
		// Reference
		$key = array_keys($ref)[0];
		$value = array_values($ref)[0];

		/*$remove = ['password', 'withdrawalpin'];
		if($query = $this->read($key, $value)){
			foreach($query as $num => $data){
				// $data = (array) $data;
				// show(array_keys((array)$data));
				foreach($data as $key => $val){
					if(in_array($key, $remove)){
						unset($query[$num]->$key);
					}
				}
			}
		}*/
		$query = $this->whereSelect($ref);

		return json($query? $query:[]);
	}

	###################### Select all
	public function selectAll(string $extra = NULL){
		$query = trim("SELECT * FROM $this->table $extra");

		return $this->query($query);
	}


	###################### INSERT
	public function insert($data){
		$data = (array) $data;
		$dataKeysCollate = array_keys($data);
		$dataKeys = implode(",", $dataKeysCollate);// Data keys
		$dataValues = implode(",:", $dataKeysCollate);// Data keys

		$query = "INSERT INTO $this->table($dataKeys) VALUES (:$dataValues)";

		$this->query($query, $data);
		return true;
	}

	###################### Select from where function
	public function whereSelect(array $data){
		$data = (array) $data;
		$ref = $data;
		$dataKeys = array_keys($ref);
		$constr = "";
		$clause = $this->clause ?? 'or';
		$clause = strtoupper($clause);

		// Seperate key into PDO function
		foreach ($dataKeys as $key){
			$constr .= "${key} = :${key} ${clause} ";
		}

		$constr = rtrim($constr, "${clause} ");

		// $query = "SELECT * FROM $this->table WHERE ${constr} ORDER BY $this->order_column $this->order_type LIMIT $this->limit OFFSET $this->offset"; // Query part Remove clause ( )
		$query = "SELECT * FROM $this->table WHERE ${constr} ORDER BY $this->order_column $this->order_type"; // Query part Remove clause ( )

		// return $query;
		return $this->query($query, $ref);
	}

	###################### Delete all
	public function whereDelete($data){
		$data = (array) $data;
		$dataKeys = array_keys($data);
		$constr = "";
		$clause = $this->clause ?? 'and';
		$clause = strtoupper($clause);

		foreach ($dataKeys as $key){
			$constr .= "$key = :$key ${clause} ";
		}

		$constr = rtrim($constr, " ${clause} ");
		$query = "DELETE FROM $this->table WHERE $constr";
		$this->query($query, $data);
		return true;
	}

	###################### Update from where
	public function whereUpdate($data, array $ref){
		$data = (array) $data;
		$dataKeys = array_keys($data);
		$constr = "";

		foreach ($dataKeys as $key){
			$constr .= "${key} = :${key}, ";
		}

		// Join data array with the ref array to form a bond
		$data = array_merge($data, $ref);
		$query = "UPDATE $this->table SET ${constr}"; // Query part
		$query = rtrim($query, ", "); // Remove the last comma

		// Reference array keys extract
		$refs = " WHERE ";
		$clause = "AND";
		if(!empty($this->clause)){
			$clause = $this->clause;
		}

		$clause = strtoupper($clause);
		$refkeys = array_keys($ref);
		foreach ($refkeys as $key){
			$refs .= "${key} = :${key} ${clause} ";
		}

		$refs = rtrim($refs, "${clause} "); // Remove clause ( )

		$query .= $refs; // Query final

		$this->query($query, $data);
		return true;
	}

	###################### Validate empty inputs
	protected function emptyDataCheck($post_data, $optionals = []){
		$post_data = (array) $post_data;
		// Remove optional check from the list of $data
		foreach($optionals as $optional){
			if(in_array($optional, array_keys($post_data))){
				unset($post_data[$optional]);
			}
		}

		foreach($post_data as $data){
			if(empty($data)){
				return form_message("Fill important field(s)");
			}
		}
	}


	######################### FOR API REQUEST
	######################### CRUD SYSYEM
	public function create($data){
		$data = (array) $data;
		if(!self::insert($data)){
			return false;
		}

		return true; // On success
	}

	######################### Read data
	public function read($key = null, $val = null, $ref = null){
		// Fetch mode
		if(!is_array($key)){
			$data = [$key => $val];
		} else {
			$data = $key;
			$this->clause = $val;
		}

		foreach($data as $col){
			if(empty($col)){
				return false;
			}
		}

		if(!$query = self::whereSelect($data)){
			return false;
		}

		// If only one result is Matched / Returned
		if(count($query) == 1){
			$query = $query[0];
			if($ref){
				return $query->$ref ?? false;
			}
			return $query;
		}

		// Return result
		return $query;
	}

	public function update($data, array $ref){
		$data = (array) $data;
		if($query = self::whereUpdate($data, $ref)){
			return true;
		}

		return false;
	}

	public function delete($data){
		$data = (array) $data;

		if($query = self::whereDelete($data)){
			return true;
		}

		return false;
	}
}