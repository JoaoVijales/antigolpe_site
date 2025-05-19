<?php
/** @var array $plan */
?>
<article class="card <?= $plan['id']?>">
  <h3 class="plan-title"><?= $plan['name'] ?></h3>
  
  <div class="plan-price">
    <div class="text-price">
      <span>
        <span class="text-coin">R$</span>
        <span class="text-nunber"><?= $plan['price'] ?></span>
      </span>
    </div>
    <div class="text-cents">,90</div>
  </div>

  <ul class="text-plan">
    <?php foreach ($plan['benefits'] as $benefit) : ?>
      <li 
      class="plan-benefit<?= $benefit['text_variation'] ? $benefit['text_variation'] : '' ?>">
        <?= $benefit['text'] ?></li>
    <?php endforeach ?>
  </ul>

  <button class="cta-buton" data-plan="<?= $plan['id'] ?>">
    <span class="button-text">
      <?= $plan['button_text'] ?>
    </span>
  </button>
</article>