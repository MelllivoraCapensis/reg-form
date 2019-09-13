<?php 

function index_view() {
	global $_SERVER;
	global $MESSAGES;

	$method = $_SERVER['REQUEST_METHOD'];

	if($method == 'GET') {
		render('templates/template.php', ['value' => 204]);
	}
	elseif($method == 'POST') {

		if(array_key_exists('customer-email', $_POST)) {
			try {
				$post_data = get_data_from_post(['customer-email', 'customer-phone', 'customer-city', 'customer-rubric'], $_POST);

			} catch (Exception $e) {
				echo $e->getMessage();
				die();
			}
			
			$city_data = CityModel::filter('name', 'equals', $post_data['customer-city'])[0];

			if(! $city_data) {
				$MESSAGES['customer-city'] = 'Некорректное название города';
				render('templates/template.php');
				return;			
			};

			$city_id = intval($city_data['id']);

			$rubric_data = RubricModel::filter('name', 'equals', $post_data['customer-rubric'])[0];
			if(! $rubric_data) {
				$MESSAGES['customer-rubric'] = 'Некорректное название рубрики';
				render('templates/template.php');
				return;	
			};

			$rubric_id = intval($rubric_data['id']);


			try {

				$customer = new CustomerRegistrationModel($post_data['customer-email'], $post_data['customer-phone'], $city_id, $rubric_id);
				$customer->save();
				http_response_code(201);
				return;
				
			} catch (Exception $e) {
				global $MESSAGES;
				if(array_key_exists('email', $MESSAGES)) {
					$MESSAGES['customer-email'] = $MESSAGES['email'];
					unset($MESSAGES['email']);
				};
				if(array_key_exists('phone', $MESSAGES)) {
					$MESSAGES['customer-phone'] = $MESSAGES['phone'];
					unset($MESSAGES['phone']);
				};

			}
			
		}
		elseif(array_key_exists('doer-email', $_POST)) {

			try {
				$post_data = get_data_from_post(['doer-email', 'doer-phone', 'doer-city', 'doer-rubric'], $_POST);
				
			} catch (Exception $e) {
				echo $e->getMessage();
				die();
			}
			
			$city_data = CityModel::filter('name', 'equals', $post_data['doer-city'])[0];
			if(! $city_data) {
				$MESSAGES['doer-city'] = 'Некорректное название города';
				render('templates/template.php');
				return;
			};

			$city_id = intval($city_data['id']);

			$rubric_data = RubricModel::filter('name', 'equals', $post_data['doer-rubric'])[0];
			if(! $rubric_data) {
				$MESSAGES['doer-rubric'] = 'Некорректное название рубрики';
				render('templates/template.php');
				return;				
			};

			$rubric_id = intval($rubric_data['id']);

			try {
				$doer = new DoerRegistrationModel($post_data['doer-email'], $post_data['doer-phone'], $city_id, $rubric_id);

				$doer->save();
				http_response_code(201);
				return;
				
			} catch (Exception $e) {
				echo $e->getMessage();
				global $MESSAGES;
				if(array_key_exists('email', $MESSAGES)) {
					$MESSAGES['doer-email'] = $MESSAGES['email'];
					unset($MESSAGES['email']);
				};
				if(array_key_exists('phone', $MESSAGES)) {

					$MESSAGES['doer-phone'] = $MESSAGES['phone'];
					unset($MESSAGES['phone']);
				};

			}
		
		}
		render('templates/template.php');
		}
	else {
		http_response_code(405);
		die();
	}

}


?>