<?php use App\Utils\View; ?>

<?= View::render('components/sections/hero', ['heroData' => $hero]) ?>

<section id="recursos" class="features" aria-labelledby="recursos-title">
  <h2 id="recursos-title" class="title">Principais Recursos</h2>
  <div class="features-grid">
    <?php // A estrutura de grid-row-a/b é mantida aqui, os feature_cards são renderizados dentro delas. ?>
    <div class="grid-row-a">
      <?php if (isset($features) && is_array($features)): ?>
        <?php foreach (array_slice($features, 0, 3) as $feature): ?>
          <?= View::render('components/ui/feature_card', [
              'card' => [
                  'icon' => $feature['icon'] ?? '',
                  'title' => $feature['title'] ?? '',
                  'description' => $feature['description'] ?? '' // Adicionado description, caso o controller passe
              ],
              'animationDirection' => $feature['animation'] ?? 'bottom'
          ]) ?>
        <?php endforeach ?>
      <?php endif ?>
    </div>
    <div class="grid-row-b">
      <?php if (isset($features) && is_array($features)): ?>
        <?php foreach (array_slice($features, 3) as $feature): ?>
           <?= View::render('components/ui/feature_card', [
              'card' => [
                  'icon' => $feature['icon'] ?? '',
                  'title' => $feature['title'] ?? '',
                  'description' => $feature['description'] ?? '',
                  'img_class' => $feature['img_class'] ?? '',
                  'title_class' => $feature['title_class'] ?? ''
              ],
              'animationDirection' => $feature['animation'] ?? 'bottom'
          ]) ?>
        <?php endforeach ?>
      <?php endif ?>
    </div>
  </div>
</section>

<section id="precos" class="plans" aria-labelledby="planos-title">
  <h2 id="planos-title" class="title">Planos e Preços</h2>
  <div class="container-plans">
    <?php if (isset($plans) && is_array($plans)): ?>
      <?php foreach ($plans as $plan): ?>
        <?= View::render('components/ui/plan_card', ['plan' => $plan]) ?>
      <?php endforeach ?>
    <?php endif ?>
  </div>
</section>
<section id="faq" class="faq" aria-labelledby="faq-title">
    <h2 id="faq-title" class="title"><?= $faq['title'] ?></h2>
    <div class="faq-container">
        <?php if (isset($faq['items']) && is_array($faq['items'])): ?>
            <?php foreach ($faq['items'] as $item): ?>
                <?= View::render('components/ui/faq_item', ['item' => $item]) ?>
            <?php endforeach ?>
        <?php endif ?>
    </div>
</section>