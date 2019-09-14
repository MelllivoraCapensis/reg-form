<?php 
require_once('lib/helpers.php');
require_once('models.php');

if(! isset($_GET['type'])) {
	http_response_code(403);
	die();
}
$type = $_GET['type'];

if(isset($_GET['startswith']) && isset($_GET['limit'])) {

	$startwith = $_GET['startswith'];
	$limit = $_GET['limit'];

	if($type == 'city') {
		$data = CityModel::filter('name', 'startswith', $startwith, $limit);
		$result = [];
		foreach ($data as $key => $value) {
			$result[] = $value['name'];
		}
		json_response($result);
	}
	elseif($type == 'rubric') {
		$data = RubricModel::filter('name', 'startswith', $startwith, $limit);
		$result = [];
		foreach ($data as $key => $value) {
			$result[] = $value['name'];
		}
		json_response($result);
	}
}
else if(isset($_GET['counters'])) {
	$models = ['doer' => 'DoerRegistrationModel',
		'customer' => 'CustomerRegistrationModel'];
	$model = $models[$type];
	$result = [];
	$country_titles = ['ukraine' => 'Украина', 'russia' => 'Россия', 
		'belarus' => 'Беларусь', 'kazakhstan' => 'Казахстан'];

	foreach ($country_titles as $key => $title) {
		$result[$key . '_' . $type . '_counter'] = 
			$model::get_registrations_by_country($title);
	}

	json_response($result);
	
}
else {
	http_response_code(403);
}

?>