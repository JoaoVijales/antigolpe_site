<?php
/** @var string $id */
/** @var string $title */
/** @var string $content */
?>
<div id="<?= $id ?>" class="popup">
  <div class="popup-content">
    <button class="close-popup" aria-label="Fechar popup">&times;</button>
    <h2 class="popup-title"><?= $title ?></h2>
    <?= $content ?>
  </div>
</div>