<?php use App\Utils\View; /** @var array $errors */ ?>
<div id="loginPopup" class="popup">
  <div class="popup-content">
    <button class="close-popup" aria-label="Fechar popup">&times;</button>
    <h2 class="popup-title">Fazer Login</h2>
    
    <button class="google-btn">
      <img src="https://www.google.com/favicon.ico" alt="Ícone do Google" class="google-icon">
      <span>Continuar com Google</span>
    </button>
    
    <div class="divider">
      <span>ou</span>
    </div>
    
    <form class="login-form" method="POST" action="/api/auth/login">
      <input type="hidden" name="csrf_token" value="<?= View::escape($_SESSION['csrf_token'] ?? '') ?>">
      
      <?php if (!empty($errors['global'])): ?>
        <div class="alert error"><?= View::escape($errors['global']) ?></div>
      <?php endif; ?>

      <div class="form-group">
        <label for="loginEmail">Email</label>
        <input 
          type="email" 
          id="loginEmail" 
          name="email" 
          required
          value="<?= View::escape($_POST['email'] ?? '') ?>"
        >
        <?php if (!empty($errors['email'])): ?>
          <div class="form-error"><?= View::escape($errors['email']) ?></div>
        <?php endif; ?>
      </div>
      
      <div class="form-group">
        <label for="loginPassword">Senha</label>
        <input 
          type="password" 
          id="loginPassword" 
          name="password" 
          required
        >
        <?php if (!empty($errors['password'])): ?>
          <div class="form-error"><?= View::escape($errors['password']) ?></div>
        <?php endif; ?>
      </div>

      <button type="submit" class="submit-btn">Entrar</button>
    </form>
    
    <p class="signup-link">
      Não tem uma conta? <a href="#" id="showSignup">Criar conta</a>
    </p>
  </div>
</div>