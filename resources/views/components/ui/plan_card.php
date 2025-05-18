<?php
/** @var array $plan */
?>
<article class="card <?= $plan['highlight'] ? 'highlight' : '' ?>">
  <h3 class="plan-title"><?= $plan['name'] ?></h3>
  
  <div class="plan-price">
    <span class="currency">R$</span>
    <span class="amount"><?= $plan['price'] ?></span>
  </div>

  <ul class="benefits">
    <?php foreach ($plan['benefits'] as $benefit) : ?>
      <li class="<?php echo $benefit['highlight'] ? 'highlight' : '' ?>">
        <?= $benefit['text'] ?>
      </li>
    <?php endforeach ?>
  </ul>

  <button class="cta-button" data-plan="<?= $plan['id'] ?>">
    <?= $plan['button_text'] ?>
  </button>
</article>