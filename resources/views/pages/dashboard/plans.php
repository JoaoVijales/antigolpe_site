<?php

use App\Utils\View;

?>

<section id="precos"  class="plans_dashboard" aria-labelledby="planos-title">
  <h2 id="planos-title" class="title">Planos e Preços</h2>
  <div class="container-plans">
    <?php if (isset($plans) && is_array($plans)): ?>
      <?php foreach ($plans as $plan): ?>
        <?= View::render('components/ui/plan_card', ['plan' => $plan]) ?>
      <?php endforeach ?>
    <?php endif ?>
  </div>
</section>