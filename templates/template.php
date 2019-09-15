<?php
global $STATIC_DIR;
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
    <link rel="stylesheet" href="<?= $STATIC_DIR ?>css/main.css"/>
    <link rel="stylesheet" href="<?= $STATIC_DIR ?>css/index.css"/>
    <!-- endbuild-->
  </head>
  <body>
    <main class="main-content" id="main-content">

      <section class="section section-step3" id="sign-up">
        <div class="container">
          <div class="row justify-content-between align-items-center">
            <div class="col-12 col-md-12 col-lg-5">
              <div class="section__title">
                <h2>Регистрация</h2>
              </div>
            </div>
            <div class="col-12 col-md-12 col-lg-7">
              <ul class="list-unstyled list-tabs">
                <li class="active"><a href="#doer-tab">Исполнитель</a></li>
                <li><a href="#customer-tab">Заказчик</a></li>
              </ul>
            </div>
          </div>
          <div class="tab-content">
          	<?php include('doer_tab.php'); ?>

          	<?php include('customer_tab.php'); ?>
           
          </div>
        </div>
        <div class="modal fade" id="modal-message" tabindex="-1" role="dialog" aria-label="Сообщение" aria-hidden="true">
          <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-body">
                <p>Регистрация прошла успешно!</p>
              </div>
              <div class="modal-footer">
                <button class="btn" data-dismiss="modal">Закрыть</button>
              </div>
            </div>
          </div>
        </div>
         <div class="modal fade" id="modal-server-error" tabindex="-1" role="dialog" aria-label="Сообщение" aria-hidden="true">
          <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-body">
                <p>Что то пошло не так, попробуйте позже</p>
              </div>
              <div class="modal-footer">
                <button class="btn" data-dismiss="modal">Закрыть</button>
              </div>
            </div>
          </div>
        </div>
         <div class="modal fade" id="modal-not-correct" tabindex="-1" role="dialog" aria-label="Сообщение" aria-hidden="true">
          <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-body">
                <p>Что то пошло не так, попробуйте позже</p>
              </div>
              <div class="modal-footer">
                <button class="btn" data-dismiss="modal">Закрыть</button>
              </div>
            </div>
          </div>
        </div>
      </section>

    </main>
    <script src="<?= $STATIC_DIR ?>/js/jquery-3.3.1.min.js"></script>
    <script src="<?= $STATIC_DIR ?>/js/core-dist.js"></script>
    <script src="<?= $STATIC_DIR ?>/js/jquery.mask.js"></script>
    <script src="<?= $STATIC_DIR ?>/js/main.js"></script>

  </body>
</html>