<?php
require_once(__DIR__ . '\config.php');
require_once(__DIR__ . '\lib\helpers.php');
require_once(__DIR__ . '\views.php');

$CONNECTION = get_connection();

require_once(__DIR__ . '\models.php');

// var_dump($_POST);

index_view();

?>