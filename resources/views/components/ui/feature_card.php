<?php
/** @var array $card */
/** @var string $animationDirection */
?>
<article class="features-card" data-animate="<?= $animationDirection ?>">
  <img class="<?= $card['img_class'] ?>" 
       src="<?= App\Utils\View::asset($card['icon']) ?>" 
       alt="<?= $card['alt_text'] ?? 'Ícone de Recurso' ?>">
  <h3 class="<?= $card['title_class'] ?>"><?= $card['title'] ?></h3>
  <?php if (!empty($card['description'])) : ?>
    <p class="feature-desc"><?= $card['description'] ?></p>
  <?php endif ?>
</article>