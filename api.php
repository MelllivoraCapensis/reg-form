<?php 
require_once('lib/helpers.php');
require_once('models.php');

if(! isset($_GET['type'])) {
	http_response_code(403);
	die();
}
$type = $_GET['type'];

if(isset($_GET['startswith']) && isset($_GET['limit'])) {

	$start_with = $_GET['startswith'];
	$limit = $_GET['limit'];

	$start_with = strip_tags($start_with);
	$limit = strip_tags($limit);

	if($type == 'city') {
		$data = get_filtered_city($start_with, $limit);
		$result = [];
		foreach ($data as $key => $value) {
			$result[] = ['name' => $value['name'],
				'region_name' => $value['region_name'],
				'country_name' => $value['country_name']];
		}
		json_response($result);
	}
	elseif($type == 'rubric') {
		$data = get_filtered_rubric($start_with, $limit);
		$result = [];
		foreach ($data as $key => $value) {
			$result[] = ['name' => $value['name'],
				'parent_name' => $value['parent_name']];
		}
		json_response($result);
	}
}
elseif(isset($_GET['counters'])) {
	$result = [];
	$country_titles = ['ukraine' => 'Украина', 'russia' => 'Россия', 
		'belarus' => 'Беларусь', 'kazakhstan' => 'Казахстан'];

	foreach ($country_titles as $key => $title) {
		$result[$key . '_' . $type . '_counter'] = 
			get_registrations_by_country($type, $title);
	}

	json_response($result);
	
}
else {
	http_response_code(403);
}

?>