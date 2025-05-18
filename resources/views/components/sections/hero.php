<?php
/** @var array $heroData */
?>
<section id="hero" class="conatiner-hero" aria-labelledby="hero-title">
  <div class="hero">
    <div class="hero-content">
      <h1 id="hero-title" class="hero-title"><?= $heroData['title'] ?></h1>
      <p class="text"><?= $heroData['subtitle'] ?></p>
      <button class="cta-buton" aria-label="Começar a usar o AntiGolpe">
        <span class="button-text">Começar Agora</span>
      </button>
  </div>
  <div class="hero-img">
    <img class="whtsp-mockup" src="<?= App\Utils\View::asset($heroData['image']) ?>" alt="<?= $heroData['image_alt'] ?? 'Imagem Hero' ?>">
  </div>
</section>