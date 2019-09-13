<?php 
header('Content-Type: Application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');

require_once('models.php');

if(! isset($_GET['type'])) {
	http_response_code(403);
	die();
}
$type = $_GET['type'];

if(! isset($_GET['startswith'])) {
	http_response_code(403);
	die();
}
$startwith = $_GET['startswith'];

if($type == 'city') {
	$data = CityModel::filter('name', 'startswith', $startwith);
	$result = [];
	foreach ($data as $key => $value) {
		$result[] = $value['name'];
	}
	echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
elseif($type == 'rubric') {
	$data = RubricModel::filter('name', 'startswith', $startwith);
	$result = [];
	foreach ($data as $key => $value) {
		$result[] = $value['name'];
	}
	echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

?>