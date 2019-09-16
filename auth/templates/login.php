<!DOCTYPE html>
<html lang="ru">
<head>
	<title>Sprava.mobi</title>
	<meta charset="utf-8"/>
	<meta content="Sprava.mobi" name="description"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta name="HandheldFriendly" content="true"/>
	<meta name="format-detection" content="telephone=no"/>
	<meta content="IE=edge" http-equiv="X-UA-Compatible"/>
	<!-- build:css css/main.css-->
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<!--   <link rel="stylesheet" href="<?= $STATIC_DIR ?>css/main.css"/> -->
	<!-- endbuild-->
</head>
<body>
	<main style="max-width:1000px" class="m-auto d-flex flex-column align-items-center">
		<form  class="d-flex w-50 flex-column mt-5 " method="post">
			<label class="d-flex justify-content-between w-100 ">
				<span class="d-block">Логин</span>
				<input name="user_login">
			</label>
			<label class="d-flex justify-content-between w-100">
				<span>Пароль</span>
				<input type="password" name="user_password">
			</label>
			<button>Войти</button>
		</form>
		<?php if(isset($data['auth_error'])): ?>
			<div class="mt-3 alert alert-warning">
				<?= $data['auth_error'] ?>
			</div> 
		<?php endif ?>
	</main>
</body>
</html>