<?php use App\Utils\View; ?>

<?= View::render('components/auth/auth_base', [
    'id' => 'registerPopup',
    'title' => 'Criar conta',
    'class_form' => 'signup-form',
    'action' => '/api/auth/register'
]) ?>
<?= View::render('components/auth/auth_base', [
    'id' => 'loginPopup',
    'title' => 'Entrar',
    'class_form' => 'login-form',
    'action' => '/api/auth/login'
]) ?>
<?= View::render('components/auth/auth_base', [
    'id' => 'forgotPasswordPopup',
    'title' => 'Esqueceu sua senha?',
    'class_form' => 'forgot-password-form',
    'action' => '/api/auth/forgot-password'
]) ?>
