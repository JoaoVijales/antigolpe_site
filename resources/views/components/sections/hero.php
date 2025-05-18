<?php
/** @var array $heroData */
?>
<section class="hero" id="hero">
  <div class="hero-content">
    <h1><?= $heroData['title'] ?></h1>
    <p class="subtitle"><?= $heroData['subtitle'] ?></p>
    <button class="cta-button" data-action="scroll-to-form">
      <?= $heroData['cta_text'] ?? 'Saiba Mais' // Usar Saiba Mais como fallback ?>
    </button>
  </div>
  <div class="hero-image">
    <img src="<?= App\Utils\View::asset($heroData['image']) ?>" alt="<?= $heroData['image_alt'] ?? 'Imagem Hero' ?>">
  </div>
</section>