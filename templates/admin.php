<?php
global $STATIC_DIR;
require_once('lib\helpers.php');

?>
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
    <link rel="stylesheet" href="<?= $STATIC_DIR ?>css/index.css"/>
    <!-- endbuild-->
  </head>
  <body>
     <main class="main-content">  
        <section>
          <?php render('templates/navbar.php', ['is_admin_page' => true]); ?>  
        </section>
         <section class="d-flex w-100 justify-content-around mb-5">
          <button class="btn btn-secondary w-50 mr-5 font-weight-bold disabled" disabled id="doer-list-button">Исполнители</button>
          <button class="btn btn-secondary w-50 font-weight-bold" id="customer-list-button">Заказчики</button>
        </section>
        <section class="d-flex flex-column align-items-center" id="doer-list">
          <h2 class="text-center">Список исполнителей</h2>
          <?php render('templates/registration_list.php', ['items' => 
          $data['doers'], 'type' => 'doer']) ?>
        </section>
        <section class="d-none flex-column align-items-center" id="customer-list">
          <h2 class="text-center">Список заказчиков</h2>
          <?php render('templates/registration_list.php', ['items' => 
          $data['customers'], 'type' => 'customer']) ?>
        </section>
     </main>
     <script src="<?= $STATIC_DIR ?>/js/admin.js"></script>
   <!--  <script src="<?= $STATIC_DIR ?>/js/jquery-3.3.1.min.js"></script>
    <script src="<?= $STATIC_DIR ?>/js/core-dist.js"></script>
    <script src="<?= $STATIC_DIR ?>/js/jquery.mask.js"></script>
    <script src="<?= $STATIC_DIR ?>/js/main.js"></script> -->

  </body>
</html>