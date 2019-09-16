<nav class="m-3 p-2">

  <?php if($data['is_admin_page']): ?>
  	<a class="btn btn-info mr-3" href="/">Главная</a>
  <?php else: ?>
  	<a class="btn btn-info mr-3" href="/admin.php">Админ</a>
  <?php endif; ?>

  <?php if(is_admin()): ?>
    <a class="btn btn-info" href="auth/logout.php">Выйти</a>
  <?php else: ?>
    <a class="btn btn-info" href="auth/login.php">Войти</a>
  <?php endif; ?>
  
</nav>