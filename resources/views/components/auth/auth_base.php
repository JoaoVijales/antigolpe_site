<?php use App\Utils\View; /** @var array $errors */ ?>

<!-- Cpmponete base de popup de login e registro com id dinominico -->
<div id="<?= $id ?>" class="popup">
    <div class="popup-content">
        <button class="close-popup" aria-label="Fechar popup">&times;</button>
        <h2 class="popup-title"><?= $title ?></h2>    
        <button class="google-btn">
        <img src="https://www.google.com/favicon.ico" alt="Ícone do Google" class="google-icon">
        <span>Continuar com o Google</span>
        </button>
        <div class="divider">
            <span>Ou</span>
        </div>
        <form class="<?= $class_form ?>" method="POST" action="<?= $action ?>">
            <input type="hidden" name="csrf_token" value="<?= View::escape($_SESSION['csrf_token'] ?? '') ?>">
            
        <?php if (!empty($errors['global'])): ?>
            <div class="alert error"><?= View::escape($errors['global']) ?></div>
        <?php endif; ?>

        <?php switch ($id) {
            case 'loginPopup':
                echo View::render('components/auth/forms/login_form', ['errors' => $errors]);
                break;
            case 'signupPopup':
                echo View::render('components/auth/forms/register', ['errors' => $errors]);
                break;
            case 'forgotPasswordPopup':
                echo View::render('components/auth/forms/forgot_password', ['errors' => $errors]);
                break;
        }?>
  </div>
</div>