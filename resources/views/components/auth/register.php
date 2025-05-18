<?php use App\Utils\View; /** @var array $errors */ ?>
<div id="signupPopup" class="popup">
  <div class="popup-content">
    <button class="close-popup" aria-label="Fechar popup">&times;</button>
    <h2 class="popup-title"><?= View::translate('auth.create_account') ?></h2>
    
    <button class="google-btn">
      <img src="https://www.google.com/favicon.ico" alt="Ícone do Google" class="google-icon">
      <span><?= View::translate('auth.continue_google') ?></span>
    </button>
    
    <div class="divider">
      <span><?= View::translate('auth.or') ?></span>
    </div>
    
    <form class="signup-form" method="POST" action="/api/auth/register">
      <input type="hidden" name="csrf_token" value="<?= View::escape($_SESSION['csrf_token'] ?? '') ?>">
      
      <?php if (!empty($errors['global'])): ?>
        <div class="alert error"><?= View::escape($errors['global']) ?></div>
      <?php endif; ?>

      <div class="form-group">
        <label for="email"><?= View::translate('auth.email') ?></label>
        <input 
          type="email" 
          id="email" 
          name="email" 
          required
          autocomplete="email"
          value="<?= View::escape($_POST['email'] ?? '') ?>"
        >
        <?php if (!empty($errors['email'])): ?>
          <div class="form-error"><?= View::escape($errors['email']) ?></div>
        <?php endif; ?>
      </div>

      <div class="form-group">
        <label for="password"><?= View::translate('auth.password') ?></label>
        <input 
          type="password" 
          id="password" 
          name="password" 
          required
          autocomplete="new-password"
        >
        <?php if (!empty($errors['password'])): ?>
          <div class="form-error"><?= View::escape($errors['password']) ?></div>
        <?php endif; ?>
      </div>

      <div class="form-actions">
        <button type="submit" class="submit-btn">
          <?= View::translate('auth.create_account') ?>
        </button>
      </div>
    </form>
    
    <p class="login-link">
      <?= View::translate('auth.have_account') ?> 
      <a href="#" id="showLogin"><?= View::translate('auth.login') ?></a>
    </p>
  </div>
</div>