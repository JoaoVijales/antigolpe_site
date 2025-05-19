<?php
/** @var array $plan */
?>
<article class="card <?= $plan['highlight'] ? 'highlight' : '' ?>">
  <h3 class="plan-title"><?= $plan['name'] ?></h3>
  
  <div class="plan-price">
    <div class="text-price">
      <span>
        <span class="currency">R$</span>
        <span class="amount"><?= $plan['price'] ?></span>
      </span>
    </div>
    <div class="text-cents">,90</div>
  </div>

  <ul class="text-plan">
    <?php foreach ($plan['benefits'] as $benefit) : ?>
      <li 
      class="plan-benefit<?= $benefit['highlight'] ? 'highlight' : '' ?>">
        <?= $benefit['text'] ?></li>
    <?php endforeach ?>
  </ul>

  <button class="cta-buton" data-plan="<?= $plan['id'] ?>">
    <?= $plan['button_text'] ?>
  </button>
</article>