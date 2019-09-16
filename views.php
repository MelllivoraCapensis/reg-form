<?php

require_once('models.php');
require_once('lib/helpers.php');

session_start();

function index_view() {
	global $_SERVER;

	$method = $_SERVER['REQUEST_METHOD'];

	if($method == 'GET') {
		render('templates/template.php', ['admin' => 
		is_admin()]);
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

function admin_view() {
	global $_SERVER;

	$method = $_SERVER['REQUEST_METHOD'];

	if($method == 'GET') {
		if(!is_admin()) {
			http_redirect('/auth/login.php');
		}
		$doers = DoerRegistrationModel::all();
		$customers = CustomerRegistrationModel::all();
		render('templates/admin.php', ['doers' => $doers,
			'customers' => $customers]);
		return;
	}
	else if($method == 'DELETE') {

		$models = ['doer' => 'DoerRegistrationModel',
			'customer' => 'CustomerRegistrationModel'];

		if(!isset($_GET['id']) || !isset($_GET['type'])) {
			http_response_code(403);
		}

		try {
			$id = $_GET['id'];
			$type = $_GET['type'];

			$model = $models[$type];

			$model::delete($id);

			http_response_code(204);
			
		} catch (Exception $e) {
			http_response_code(403);
		}
	}
	
}

function handle_post_request($type) {
	$models = ['customer' => 'CustomerRegistrationModel', 'doer' => 'DoerRegistrationModel'];

	$post_data = get_data_from_post_or_403([$type . '-email', 
			$type . '-phone', $type . '-city', $type . '-rubrics'], $_POST);
	try {
		$new_instance = new $models[$type](
			$post_data[$type . '-email'], $post_data[$type . '-phone'],
			get_city_id($post_data[$type . '-city']), $post_data[$type . '-rubrics']);

		$new_instance->save();
		http_response_code(201);
		die();

	} catch (Exception $e) {

		if($e->getMessage() == 'not valid city') {

			json_response([$type . '-city' => 'Некорректное значение']);
		}
		elseif($e->getMessage() == 'not valid rubric') {
			json_response([$type . '-rubric' => 'Некорректное значение']);
		}
		http_response_code(500);
	}
}

?>