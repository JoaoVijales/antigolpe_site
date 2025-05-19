<?php use App\Utils\View; ?>

<?= App\Utils\View::render('components/auth/auth_base', [
    'id' => 'signupPopup',
    'title' => 'Criar conta',
    'class_form' => 'signup-form',
    'action' => '/api/auth/register'
]) ?>
<?= App\Utils\View::render('components/auth/auth_base', [
    'id' => 'loginPopup',
    'title' => 'Entrar',
    'class_form' => 'login-form',
    'action' => '/api/auth/login'
]) ?>
<?= App\Utils\View::render('components/auth/auth_base', [
    'id' => 'forgotPasswordPopup',
    'title' => 'Esqueceu sua senha?',
    'class_form' => 'forgot-password-form',
    'action' => '/api/auth/forgot-password'
]) ?>
