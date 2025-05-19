<?php use App\Utils\View; /** @var array $errors */ ?>
<div class="form-group">
  <label for="Email">Email</label>
  <input 
  type="email" 
  id="Email" 
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