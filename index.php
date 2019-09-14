<?php

require_once(__DIR__ . '\config.php');
require_once(__DIR__ . '\lib\helpers.php');
require_once(__DIR__ . '\views.php');

$CONNECTION = get_connection();

index_view();

?>