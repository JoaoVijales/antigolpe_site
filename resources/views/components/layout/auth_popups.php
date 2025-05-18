<?php use App\Utils\View; ?>

<?= View::render('components/auth/register') ?>
<?= View::render('components/auth/login_form') ?>

<?php /*
  O popup de esqueci a senha (forgot_password.php) pode ser incluído aqui se for exibido na página inicial, 
  mas com base no index.php original, apenas login e register estavam presentes diretamente no HTML. 
  Vamos incluir apenas login e register por enquanto.
  
  <?= View::render('components/auth/forgot_password') ?>
*/ ?> 