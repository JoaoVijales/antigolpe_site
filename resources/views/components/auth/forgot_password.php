<?php /** @var array $errors */ ?>
<div id="forgotPasswordPopup" class="popup">
  <div class="popup-content">
    <button class="close-popup" aria-label="Fechar popup">&times;</button>
    <h2 class="popup-title"><?= $this->translate('auth.reset_password') ?></h2>
    
    <form class="forgot-password-form" method="POST" action="/auth/forgot-password">
      <input type="hidden" name="csrf_token" value="<?= $this->escape($_SESSION['csrf_token'] ?? '') ?>">
      
      <?php if (!empty($errors['global'])): ?>
        <div class="alert error"><?= $this->escape($errors['global']) ?></div>
      <?php endif; ?>

      <div class="form-group">
        <label for="resetEmail"><?= $this->translate('auth.email') ?></label>
        <input 
          type="email" 
          id="resetEmail" 
          name="email" 
          required
          autocomplete="email"
          value="<?= $this->escape($_POST['email'] ?? '') ?>"
        >
        <?php if (!empty($errors['email'])): ?>
          <div class="form-error"><?= $this->escape($errors['email']) ?></div>
        <?php endif; ?>
      </div>

      <div class="form-actions">
        <button type="submit" class="submit-btn">
          <?= $this->translate('auth.send_instructions') ?>
        </button>
      </div>
    </form>
    
    <p class="login-link">
      <a href="#" id="showLogin"><?= $this->translate('auth.return_login') ?></a>
    </p>
  </div>
</div>