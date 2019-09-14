<?php 
require_once('helpers.php');

abstract class Field {
	public function __construct() {

	}

	public function is_valid($value) {
		return true;
	}

	public function get_cleaned_value($value) {
		return $value;
	}
}

class IntField extends Field {

	public function is_valid($value) {
		if(! is_int($value)) {
			throw new Exception($value . ' is not an int number');
			return false;
		}
		
		return true;
	}

	public function get_cleaned_value($value) {
		return $value;
	}
}

class ForeignKey extends IntField {

	public function __construct($table) {
		$this->table = $table;
	}

	public function is_valid($value) {
		if(! parent::is_valid($value)) return false;

		$data = get_data("SELECT * FROM " . $this->table .
			" WHERE id =" . $value);
		if(count($data) == 0) {
			throw new Exception($value . ' is not valid id');
			return false;			
		}

		return true;
	}

	public function get_cleaned_value($value) {
		return $value;
	}

}


class EmailField extends TextField {


	public function is_valid($value) {
		global $MESSAGES;

		if(! parent::is_valid($value)) return false;

		if(filter_var($value, FILTER_VALIDATE_EMAIL)) {
			return true;
		}
		else {
			$MESSAGES['email'] = "Email is not valid";
			throw new Exception("Email is not valid");
			return false;
		}
	}
	
}

class PhoneField extends TextField {

	public function is_valid($value) {
		global $MESSAGES;

		if(! parent::is_valid($value)) return false;
		
		preg_match('/^\(\d{3}\)\s\d{3}-\d{4}$/', $value,
		    $matches);

		if(count($matches) > 0) {
			return true;
		}
		else {
			$MESSAGES['phone'] = "Phone is not valid";
			throw new Exception("Phone is not valid");
			return false;
		}
	}
}

class TextField extends Field {

	public function is_valid($value) {
		if(! parent::is_valid($value)) return false;

		// $value = strip_tags($value);
		// if(strlen($value) == 0) {
		// 	throw new Exception('The field is empty !!!');
		// 	return false;			
		// }
		return true;
	}
	
	public function get_cleaned_value($value) {
		return '"' . $value . '"';
	}
}

class Model {
	static protected $table_name;

	public static function filter($field, $comparison, $value) {
		$data = get_filtered_data(static::$table_name, $field,
			$comparison, $value);
		return $data;
	}

	public static function all() {
		$data = get_data("SELECT * FROM " . static::$table_name)[0];
		return $data;
	}
}




 ?>