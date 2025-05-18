<?php
/** @var string $id */
/** @var string $title */
/** @var string $content */
// TODO: Refatorar - Este componente de popup genérico pode ser utilizado para refatorar a estrutura duplicada nos componentes de popup de autenticação (components/auth/login_form.php, register.php, forgot_password.php).
?>
<div id="<?= $id ?>" class="popup">
  <div class="popup-content">
    <button class="close-popup" aria-label="Fechar popup">&times;</button>
    <h2 class="popup-title"><?= $title ?></h2>
    <?= $content ?>
  </div>
</div>