<?php use App\Utils\View; /** @var array $errors */ ?>
<div id="forgotPasswordPopup" class="popup">
  <div class="popup-content">
    <?php // TODO: Refatorar - A estrutura externa deste popup (classes .popup, .popup-content, .close-popup, .popup-title, .divider, .login-link) é duplicada em login_form.php e register.php. Considerar criar um componente de popup genérico ou auth_popup_container para reutilizar esta estrutura.
     ?>
    <button class="close-popup" aria-label="Fechar popup">&times;</button>
    <h2 class="popup-title"><?= View::translate('auth.reset_password') ?></h2>
    
    <form class="forgot-password-form" method="POST" action="/auth/forgot-password">
      <input type="hidden" name="csrf_token" value="<?= View::escape($_SESSION['csrf_token'] ?? '') ?>">
      
      <?php if (!empty($errors['global'])): ?>
        <div class="alert error"><?= View::escape($errors['global']) ?></div>
      <?php endif; ?>

      <div class="form-group">
        <label for="resetEmail"><?= View::translate('auth.email') ?></label>
        <input 
          type="email" 
          id="resetEmail" 
          name="email" 
          required
          autocomplete="email"
          value="<?= View::escape($_POST['email'] ?? '') ?>"
        >
        <?php if (!empty($errors['email'])): ?>
          <div class="form-error"><?= View::escape($errors['email']) ?></div>
        <?php endif; ?>
      </div>

      <div class="form-actions">
        <button type="submit" class="submit-btn">
          <?= View::translate('auth.send_instructions') ?>
        </button>
      </div>
    </form>
    
    <p class="login-link">
      <a href="#" id="showLogin"><?= View::translate('auth.return_login') ?></a>
    </p>
  </div>
</div>