<?php 
// header('Content-Type: Application/json');
// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Methods: GET');
// header('Access-Control-Allow-Headers: Content-Type');
// header('Access-Control-Allow-Credentials: true');

// echo json_encode($_POST, JSON_UNESCAPED_UNICODE);


require_once('models.php');
require_once('views.php');

$models = ['customer' => 'CustomerRegistrationModel', 'doer' => 'DoerRegistrationModel'];
$type = 'customer';
// $i = new $models[$type]('dima@mail.com', '(434) 434-3243', 'Балашиха/Москва и Московская обл./Россия', 'Водный транспорт***Железнодорожный транспорт и комплектующие***Воздушный транспорт');
// var_dump($i);
// $i->save();

// $c = DoerRegistrationModel::get_registrations_by_country('Беларусь');
// var_dump($c);


// $d = get_filtered_data('city', 'name', 'equals', 'Кировск', 10);
// var_dump($d);

// $r = get_city_id('Гомель/Гомельская обл./Беларусь');
// var_dump($r);
var_dump(md5('admin'));
?>