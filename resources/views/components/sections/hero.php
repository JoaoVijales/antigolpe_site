<?php
/** @var array $heroData */
?>
<section class="hero" id="hero">
  <div class="hero-content">
    <h1><?= $heroData['title'] ?></h1>
    <p class="subtitle"><?= $heroData['subtitle'] ?></p>
    <button class="cta-button" data-action="scroll-to-form">
      <?= $heroData['cta_text'] ?>
    </button>
  </div>
  <div class="hero-image">
    <img src="<?= $this->asset($heroData['image']) ?>" alt="<?= $heroData['image_alt'] ?>">
  </div>
</section>