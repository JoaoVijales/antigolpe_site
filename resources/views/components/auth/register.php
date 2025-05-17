<?php /** @var array $errors */ ?>
<div id="signupPopup" class="popup">
  <div class="popup-content">
    <button class="close-popup" aria-label="Fechar popup">&times;</button>
    <h2 class="popup-title"><?= $this->translate('auth.create_account') ?></h2>
    
    <button class="google-btn">
      <img src="https://www.google.com/favicon.ico" alt="Ícone do Google" class="google-icon">
      <span><?= $this->translate('auth.continue_google') ?></span>
    </button>
    
    <div class="divider">
      <span><?= $this->translate('auth.or') ?></span>
    </div>
    
    <form class="signup-form" method="POST" action="/api/auth/register">
      <input type="hidden" name="csrf_token" value="<?= $this->escape($_SESSION['csrf_token'] ?? '') ?>">
      
      <?php if (!empty($errors['global'])): ?>
        <div class="alert error"><?= $this->escape($errors['global']) ?></div>
      <?php endif; ?>

      <div class="form-group">
        <label for="email"><?= $this->translate('auth.email') ?></label>
        <input 
          type="email" 
          id="email" 
          name="email" 
          required
          autocomplete="email"
          value="<?= $this->escape($_POST['email'] ?? '') ?>"
        >
        <?php if (!empty($errors['email'])): ?>
          <div class="form-error"><?= $this->escape($errors['email']) ?></div>
        <?php endif; ?>
      </div>

      <div class="form-group">
        <label for="password"><?= $this->translate('auth.password') ?></label>
        <input 
          type="password" 
          id="password" 
          name="password" 
          required
          autocomplete="new-password"
        >
        <?php if (!empty($errors['password'])): ?>
          <div class="form-error"><?= $this->escape($errors['password']) ?></div>
        <?php endif; ?>
      </div>

      <div class="form-actions">
        <button type="submit" class="submit-btn">
          <?= $this->translate('auth.create_account') ?>
        </button>
      </div>
    </form>
    
    <p class="login-link">
      <?= $this->translate('auth.have_account') ?> 
      <a href="#" id="showLogin"><?= $this->translate('auth.login') ?></a>
    </p>
  </div>
</div>