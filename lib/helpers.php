<?php

function get_connection() {

	global $SERVER_NAME, $DB_USER_NAME, $DB_USER_PASSWORD,
		$DB_NAME, $CONNECTION;

	if($CONNECTION) return $CONNECTION;

	try {
		$connection = new mysqli($SERVER_NAME, $DB_USER_NAME,
			$DB_USER_PASSWORD, $DB_NAME);

	} catch (Exception $e) {
		http_response_code(500);
		die();
	}
	return $connection;
}

function get_filtered_data($table, $field, $comparison, $value, $limit) {
	$comparison_array = [
		'equals',
		'contains',
		'startswith',
		'endswith',
		'morethen',
		'lessthen',
	];

	$connection = get_connection();

	if(! in_array($comparison, $comparison_array)) {
		throw new Exception($comparison . " - there is no such comparison!!!");
	}

	$value = strip_tags($value);

	if($table == 'rubric') {
		if($comparison == 'startswith') {
			$query = "SELECT child.name as name, parent.name as parent_name FROM " . $table . " as child JOIN " . $table . " as parent WHERE parent.id = child.parent_id  and upper(child." . $field . ") LIKE '" . $value . "%' LIMIT " . $limit;
		}

		if($comparison == 'equals') {
			$query = "SELECT child.name as name, parent.name as parent_name FROM " . $table . "  as child JOIN " . $table . " as parent WHERE parent.id = child.parent_id AND upper(child." . $field . ") LIKE '" . $value . "' LIMIT " .	$limit;
		}
	}
	elseif($table == 'city') {
		if($comparison == 'startswith') {
			$query = "SELECT FROM 
				(SELECT city.id as id, city.name as name, region.country_id as region_name 
				FROM city JOIN region 
				WHERE city.region_id=region.id 
					AND upper(city." . $field . ") LIKE '" . $value . "%' LIMIT " . $limit . ") as city JOIN country WHERE city.";
		}

		if($comparison == 'equals') {
			$query = "SELECT city.id, city.name, region.name as region_name FROM " . $table . " JOIN region WHERE city.region_id=region.id AND upper(city." . 
			$field . ") LIKE '" . $value . "' LIMIT " .	$limit;
		}

	}

	$data = get_data($query);
	return $data;		
}

function get_filtered_rubric($start_with, $limit) {
	$start_with = strip_tags($start_with);
	$query = "SELECT child.name as name, parent.name as parent_name FROM rubric as child JOIN rubric as parent WHERE parent.id = child.parent_id  and upper(child.name) LIKE '" . $start_with . "%' LIMIT " . $limit;
	
	try {
		$data = get_data($query);	
		return $data;		
	} catch (Exception $e) {
		return [];
	}	
}

function get_filtered_city($start_with, $limit) {
	$start_with = strip_tags($start_with);
	$limit = strip_tags($limit);

	$query = 
	"SELECT city.name as name, city.region_name as region_name, country.name as country_name FROM 
		(SELECT city.id as id, city.name as name, region.country_id as country_id, region.id as region_id, region.name as region_name 
		FROM city JOIN region 
		WHERE city.region_id=region.id 
			AND upper(city.name) LIKE '" . $start_with . "%' LIMIT " . $limit . ") as city JOIN country WHERE city.country_id=country.id";
	try {
		$data = get_data($query);	
		return $data;		
	} catch (Exception $e) {
		return [];
	}
	
}

function get_data($query) {

	$connection = get_connection();
	$result = $connection->query($query);

	if(! $result) {
		throw new Exception($connection->error);
		return;		
	}

	$data = [];

	while($row = $result->fetch_assoc()) {
		$data[] = $row;
	}

	return $data;
}

function get_data_from_post_or_403($fields, $post) {

	$result = [];

	foreach ($fields as $key => $field) {
		if(! array_key_exists($field, $post)) {
			http_response_code(403);
			die();			
		}
		
		$result[$field] = $post[$field];
	}
	return $result;
}

function render($template, $data = []) {
	include($template);
}

function json_response($text) {
	header('Content-Type: Application/json');
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET');
	header('Access-Control-Allow-Headers: Content-Type');
	header('Access-Control-Allow-Credentials: true');
	http_response_code(200);
	echo json_encode($text, JSON_UNESCAPED_UNICODE);
	die();
}

function http_redirect($url) {
	header('Location: ' . $url);
}

function check_user($auth_data) {
	$LOGIN = 'admin';
	$PASSWORD = '21232f297a57a5a743894a0e4a801fc3';

	if($auth_data['user_login'] != $LOGIN) {
		return false;
	}
	if(md5($auth_data['user_password']) != $PASSWORD) {
		var_dump(md5($auth_data['user_password']));
		return false;
	}
	return true;
}

function is_admin() {
	global $_SESSION;
	
	if(! isset($_SESSION['admin'])) {
	  $admin = false;
	} else if(! $_SESSION['admin']) {
	  $admin = false;
	} else {
	  $admin = true;
	}
	return $admin;
}

function get_city_id($long_city_name, $sep = '/') {
	$name_arr = explode($sep, $long_city_name);
	if(count($name_arr) < 3) {
		throw new Exception("not valid city");
	}

	$query = "SELECT city.id FROM city JOIN 
				(SELECT region.id as id,
					region.name as name,
					country.name as country_name
				 FROM region JOIN country 
				 WHERE region.country_id=country.id) as region
			  WHERE city.region_id=region.id AND city.name='" . $name_arr[0] . "' AND region.name='" . $name_arr[1] . "' AND region.country_name='" . $name_arr[2] . "' limit 1";

	$city_data = get_data($query);

	if(! $city_data) {
		throw new Exception("not valid city");
	};

	$city_id = $city_data[0]['id'];

	return $city_id;
}

function get_registrations_by_country($type, $country) {
	$query = "SELECT COUNT(*) as count FROM 
		(SELECT " . $type . ".id as type_id, city.region_id as region_id
			FROM " . $type . "_registration as " . $type . " JOIN city 
			WHERE " . $type . ".city_id = city.id) as type
		JOIN
		(SELECT region.id as id, country.name as country_name, country.id as country_id 
			FROM region JOIN country 
			WHERE region.country_id = country.id) as region
		WHERE region.id = type.region_id AND region.country_name='" . $country . "'";
	$data = get_data($query)[0]['count'];

	return $data;
}

?>