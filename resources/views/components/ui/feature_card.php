<?php
/** @var array $card */
/** @var string $animationDirection */
?>
<article class="features-card" data-animate="<?= $animationDirection ?>">
  <?= $card['icon'] ?>
  <h3 class="<?= $card['title_class'] ?>"><?= $card['title'] ?></h3>
  <?php if (!empty($card['description'])) : ?>
    <p class="feature-desc"><?= $card['description'] ?></p>
  <?php endif ?>
</article>