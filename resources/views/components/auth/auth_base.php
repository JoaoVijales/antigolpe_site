<?php use App\Utils\View; ?>

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

        <div class="form-group">
            <label for="AuthEmail">Email</label>
            <input 
            type="email" 
            id="AuthEmail" 
            name="email" 
            required
            autocomplete="email"
            value="<?= View::escape($_POST['email'] ?? '') ?>"
            >
            <?php if (!empty($errors['email'])): ?>
            <div class="form-error"><?= View::escape($errors['email']) ?></div>
            <?php endif; ?>
        </div>
        
        <?php if ($id == 'loginPopup'): ?>
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
            
            <p class="signup-link">Não tem uma conta? 
                <a href="#" id="showSignup">Criar conta</a>
            </p>
        <?php endif; ?>

        <?php if ($id == 'registerPopup'): ?>
            <div class="form-group">
                <label for="password">Senha</label>
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
                Criar conta
                </button>
            </div>
            </form>
            
            <p class="login-link">
                Já tem uma conta? 
                <a href="#" id="showLogin">Entrar</a>
            </p>
        <?php endif; ?>

        <?php if ($id == 'forgotPasswordPopup'): ?>
            <div class="form-actions">
                <button type="submit" class="submit-btn">
                    Enviar instruções
                </button>
            </div>
        </form>
            <p class="login-link">
                <a href="#" id="showLogin">Voltar para o login</a>
            </p>
        <?php endif; ?>
  </div>
</div>