<?php

require_once('config.php');
require_once('lib/models.php');
require_once('lib/helpers.php');

class RegistrationModel extends Model{
	static protected $table_name;
	static private $booted = false;
	static protected $connection = false;

	static public $fields = [];

	static private function boot() {

		if(self::$booted) return;

		self::$fields['email'] = new EmailField();
		self::$fields['phone'] = new PhoneField();
		self::$fields['city_id'] = new CityField();
		self::$fields['rubrics'] = new RubricField();

		global $CONNECTION;
		self::$connection = get_connection();
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

	static public function delete($id) {
		self::$connection = self::$connection ? self::$connection :
			get_connection();
		$query = 'DELETE FROM ' . static::$table_name .
			' WHERE id=' . $id;
		self::$connection->query($query);
	}

	static private function insert(self $obj) {
		if(! $obj->valid) {
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