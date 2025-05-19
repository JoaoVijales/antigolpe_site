<?php
/** @var array $plan */
?>
<article class="card <?= $plan['id']?>">
  <h3 class="plan-title"><?= $plan['name'] ?></h3>
  <? 
  // refatorar para usar o foreach (sugestão)

  // sugestão: criar uma variavel esppecifica para 
  // esses casos desacomplando do text_variation
  // e criar um plan card base e extender ele
  // com os planos que possuem text-off e value-container
  // e outros que não possuem
  ?>
  <div class="plan-price<?= $plan['text_variation'] ? $plan['text_variation'] : '' ?>">
    <?php if ($plan['text_variation']): ?>
      <div class="price-container">
        <div class="text-off">
          <?= $plan['subtitle'] ?>
        </div>
      </div>
      <div class="value-container">
        <div class="text-price">
          <span>
            <span class="text-coin">R$</span>
            <span class="text-nunber"><?= $plan['price'] ?></span>
          </span>
        </div>
        <div class="text-cents">,90</div>
      </div>
    </div>
    <?php endif ?>
    
    <?php if (!$plan['text_variation']): ?>
    <div class="text-price">
      <span>
        <span class="text-coin">R$</span>
        <span class="text-nunber"><?= $plan['price'] ?></span>
      </span>
    </div>
      <div class="text-cents">,90</div>
    </div>
    <?php endif ?>

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