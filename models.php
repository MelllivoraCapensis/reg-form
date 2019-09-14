<?php

require_once('config.php');
require_once('lib/models.php');
require_once('lib/helpers.php');

class RegistrationModel extends Model{
	static protected $table_name;
	static private $booted = false;
	static private $connection = false;

	static public $fields = [];

	static private function boot() {

		if(self::$booted) return;

		self::$fields['email'] = new EmailField();
		self::$fields['phone'] = new PhoneField();
		self::$fields['city_id'] = new ForeignKey('city');
		self::$fields['rubric_ids'] = new TextField();

		global $CONNECTION;
		self::$connection = $CONNECTION;
	}

	function __construct(...$args) {

		self::boot();

		$this->args = $args;

		self::clean_data($this);
	}

	static public function clean_data(self $obj) {
		$i = 0;

			foreach (self::$fields as $key => $field) {

				$value = $obj->args[$i];

				if($field->is_valid($value)) {
					$obj->cleaned_data[$key] = $field->get_cleaned_value($value);
					$i ++;
				}
				else {
					$obj->valid = false;
					$obj->cleaned_data = null;
					return;
				}
			}
			$obj->valid = true;
	}

	static public function get_registrations_by_country($country) {
		$type = static::$type;
		$query = "SELECT COUNT(*) as count FROM 
			(SELECT " . $type . ".id as type_id, city.region_id as region_id
				FROM " . $type . "_registration as " . $type . " JOIN city 
				WHERE " . $type . ".city_id = city.id) as type
			JOIN
			(SELECT region.id as region_id, country.name as country, country.id as country_id 
				FROM region JOIN country 
				WHERE region.country_id = country.id) as regions
			WHERE regions.region_id = type.region_id AND regions.country='" . $country . "'";
		return get_data($query)[0]['count'];

	}

	static private function insert(self $obj) {
		if(! $obj->valid) {
			var_dump($obj);
			throw new Exception("Not valid object");			
		}

		$query = "INSERT INTO " . static::$table_name . 
			" (" . $obj->get_keys_str() . ") VALUE( " .
			$obj->get_values_str() . ")";


		if(! self::$connection->query($query)) {
			throw new Exception(self::$connection->error);
		}
	}

	public function get_keys_str() {
		return implode(', ', array_keys($this->cleaned_data));
	}

	public function get_values_str() {
		return implode(', ', array_values($this->cleaned_data));
	}

	public function save() {
		try {
			self::insert($this);
		} catch (Exception $e) {
			echo $e->getMessage();
			return;
		}
	}
}

class CityModel extends Model {
	static protected $table_name = 'city';

}

class RubricModel extends Model {
	static protected $table_name = 'rubric';

}

class DoerRegistrationModel extends RegistrationModel {
	static protected $table_name = 'doer_registration';
	static protected $type = 'doer';

}

class CustomerRegistrationModel extends RegistrationModel {
	static protected $table_name = 'customer_registration';
	static protected $type = 'customer';

}





?>