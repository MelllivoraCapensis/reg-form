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

}

class CityField extends TextField {

	public function is_valid($value) {
		if(! parent::is_valid($value)) return false;

		return true;
	}

}

class RubricField extends TextField {

	public function is_valid($value) {
		
		if(! parent::is_valid($value)) return false;

		$rubrics_arr = explode('***', $value);
		
		if($rubrics_arr == [null]) {
			throw new Exception("not valid rubric");
			return false;
		}

		foreach($rubrics_arr as $key => $rubric) {
			$data = get_data("SELECT * FROM rubric
				WHERE name ='" . $rubric . "'"); 
			if(count($data) > 0) {
				return true;
			}

		}
		throw new Exception("not valid rubric");
		return false;	
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
			throw new Exception("Phone is not valid");
			return false;
		}
	}
}

class TextField extends Field {

	public function is_valid($value) {
		if(! parent::is_valid($value)) return false;
		return true;
	}
	
	public function get_cleaned_value($value) {
		return '"' . $value . '"';
	}
}

class Model {
	static protected $table_name;

	public static function filter($field, $comparison, $value, $limit) {
		$data = get_filtered_data(static::$table_name, $field,
			$comparison, $value, $limit);
		return $data;
	}

	public static function all() {
		$query = "SELECT type.id, type.email, type.phone, type.city as city, region.country_name as country, type.rubrics  FROM (SELECT type.id as id, type.email as email, type.phone as phone, city.name as city, type.rubrics as rubrics, city.region_id as region_id FROM " . static::$table_name . " as type JOIN 
			city WHERE city.id=type.city_id) as type JOIN 
			(SELECT region.id as id, country.name as country_name FROM
			region JOIN country WHERE region.country_id=country.id) as region
			WHERE type.region_id=region.id";
		$data = get_data($query);
		return $data;
	}
}

?>