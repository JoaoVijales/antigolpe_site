<div class="form-group">
  <label for="RecoverEmail">Email</label>
  <input 
  type="email" 
  id="RecoverEmail" 
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
      Enviar instruções
  </button>
</div>
</form>
<p class="login-link">
  <a href="#" id="showLogin">Voltar para o login</a>
</p>