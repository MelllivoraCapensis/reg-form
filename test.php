<?php 
// header('Content-Type: Application/json');
// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Methods: GET');
// header('Access-Control-Allow-Headers: Content-Type');
// header('Access-Control-Allow-Credentials: true');

// echo json_encode($_POST, JSON_UNESCAPED_UNICODE);


require_once('models.php');

$models = ['customer' => CustomerRegistrationModel, 'doer' => DoerRegistrationModel];
$type = 'doer';
$i = new $models[$type]('dima@mail.com', '(434) 434-3243', 43, '432***434');
var_dump($i);

?>