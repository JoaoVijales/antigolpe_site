<?php
/** @var array $card */
/** @var string $animationDirection */
?>
<article class="features-card" data-animate="<?= $animationDirection ?>">
  <img class="feature-icon" 
       src="<?= $this->asset($card['icon']) ?>" 
       alt="<?= $card['alt_text'] ?>">
  <h3 class="feature-title"><?= $card['title'] ?></h3>
  <?php if (!empty($card['description'])) : ?>
    <p class="feature-desc"><?= $card['description'] ?></p>
  <?php endif ?>
</article>