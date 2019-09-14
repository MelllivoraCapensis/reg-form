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

	if($comparison == 'startswith') {
		$query = "SELECT * FROM " . $table . " WHERE upper(" . 
		$field . ") LIKE '" . $value . "%' LIMIT " . $limit;
	}

	if($comparison == 'equals') {
		$query = "SELECT * FROM " . $table . " WHERE upper(" . 
		$field . ") LIKE '" . $value . "' LIMIT " .	$limit;
	}

	$data = get_data($query);
	return $data;		
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

?>