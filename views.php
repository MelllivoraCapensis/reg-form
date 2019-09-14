<?php

require_once('models.php');

function index_view() {
	global $_SERVER;

	$method = $_SERVER['REQUEST_METHOD'];

	if($method == 'GET') {
		render('templates/template.php');
	}
	elseif($method == 'POST') {

		if(array_key_exists('customer-email', $_POST)) {
			handle_post_request('customer');
		}
		elseif(array_key_exists('doer-email', $_POST)) {
			handle_post_request('doer');		
		}
	}
	else {
		http_response_code(405);
		die();
	}
}

function handle_post_request($type) {
	$models = ['customer' => 'CustomerRegistrationModel', 'doer' => 'DoerRegistrationModel'];

	$post_data = get_data_from_post_or_403([$type . '-email', 
			$type . '-phone', $type . '-city', $type . '-rubrics'], $_POST);

	$city_id = get_city_id($post_data, $type);

	$rubric_ids_str = get_rubric_ids_str($post_data, $type);

	try {
		$new_instance = new $models[$type](
			$post_data[$type . '-email'], $post_data[$type . '-phone'],
			$city_id, $rubric_ids_str);
		$new_instance->save();
		http_response_code(201);
		die();

	} catch (Exception $e) {
		// json_response($e);
		http_response_code(500);
	}
}

function get_city_id($post_data, $type) {
	$city_data = CityModel::filter('name', 'equals', $post_data[$type . '-city'], 1)[0];

	if(! $city_data) {
		$key = $type . '-city';
		json_response([$key => 'Такого города нет в списке']);
		die();
	};

	$city_id = intval($city_data['id']);

	return $city_id;
}

function get_rubric_ids_str($post_data, $type) {
	$rubrics_str = $post_data[$type . '-rubrics'];
	$rubrics_arr = explode('***', $rubrics_str);
	$rubric_ids_arr = [];

	foreach ($rubrics_arr as $key => $rubric) {
		$rubric_ids_arr[] = RubricModel::filter('name', 'equals', $rubric, 1)[0]['id'];				
	}

	if(count($rubric_ids_arr) == 0 || $rubric_ids_arr == [null]) {
		$key = $type . '-rubric';
		json_response([$key => 'Таких рубрик нет в списке']);
		die();
	}

	$rubric_ids_str = join('***', $rubric_ids_arr);

	return $rubric_ids_str;
}

?>