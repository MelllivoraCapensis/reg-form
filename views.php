<?php 

function index_view() {
	global $_SERVER;
	global $MESSAGES;

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
		render('templates/template.php');
	}
	else {
		http_response_code(405);
		die();
	}

}

function handle_post_request($type) {
	$models = ['customer' => CustomerRegistrationModel, 'doer' => DoerRegistrationModel];
	try {
		$post_data = get_data_from_post([$type . '-email', 
			$type . '-phone', $type . '-city', $type . '-rubric'], $_POST);

	} catch (Exception $e) {
		http_response_code(403);
		die();
	}
	
	$city_data = CityModel::filter('name', 'equals', $post_data[$type . '-city'])[0];

	if(! $city_data) {
		$key = $type . '-city';
		json_response([$key => 'Такого города нет в списке']);
		die();
	};

	$city_id = intval($city_data['id']);

	$rubrics = explode('***', $_POST[$type . '-rubric']);
	$rubric_ids_arr = [];
	foreach ($rubrics as $key => $rubric) {
		$rubric_ids_arr[] = RubricModel::filter('name', 'equals', $rubric)[0]['id'];				
	}

	if(count($rubric_ids_arr) == 0 || $rubric_ids_arr == [null]) {
		$key = $type . '-rubric';
		json_response([$key => 'Таких рубрик нет в списке']);
		die();
	}
	$rubric_ids = join('***', $rubric_ids_arr);

	try {
		$new_instance = new $models[$type](
			$post_data[$type . '-email'], $post_data[$type . '-phone'],
			$city_id, $rubric_ids);
		$new_instance->save();
		http_response_code(201);
		die();
		
	} catch (Exception $e) {
		global $MESSAGES;
		if(array_key_exists('email', $MESSAGES)) {
			$MESSAGES[$type . '-email'] = $MESSAGES['email'];
			unset($MESSAGES['email']);
		};
		if(array_key_exists('phone', $MESSAGES)) {
			$MESSAGES[$type . '-phone'] = $MESSAGES['phone'];
			unset($MESSAGES['phone']);
		};

	}
	
}

?>