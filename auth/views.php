<?php 
require_once('../lib/helpers.php');

function login_view() {
	global $_SERVER, $_SESSION;
	session_start();

	if(isset($_SESSION['admin'])) {
		if($_SESSION['admin'] == true) {
			http_redirect('/admin.php');
		}
	}
	$method = $_SERVER['REQUEST_METHOD'];

	if($method == 'GET') {
		render('templates/login.php');
	}
	else if($method == 'POST') {
		$auth_data = get_data_from_post_or_403(
			['user_login', 'user_password'], $_POST);
		if(check_user($auth_data)) {
			$_SESSION['admin'] = true;
			http_redirect('/admin.php');
		}
		else {
			render('templates/login.php', ['auth_error' =>
				'Неправильная пара логин/пароль']);
		}
		
	}
}

function logout_view() {
	global $_SERVER, $_SESSION;

	session_start();

	$method = $_SERVER['REQUEST_METHOD'];

	if($method == 'GET') {
		$_SESSION['admin'] = false;
		http_redirect('/');
	}
	else {
		http_response_code(403);
	}
}





?>