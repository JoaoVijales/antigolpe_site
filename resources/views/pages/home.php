<?php use App\Utils\View; ?>

<?= View::render('components/sections/hero', ['heroData' => $hero]) ?>

<section id="recursos" class="features">
  <h2>Principais Recursos</h2>
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
                  'description' => $feature['description'] ?? ''
              ],
              'animationDirection' => $feature['animation'] ?? 'bottom'
          ]) ?>
        <?php endforeach ?>
      <?php endif ?>
    </div>
  </div>
</section>

<section id="precos" class="plans">
  <h2>Planos e Preços</h2>
  <div class="container-plans">
    <?php // Dados dos planos não estão no HomeController atual. Abaixo é um placeholder. ?>
    <?php
    /*
    $plansData = [
        [
            'id' => 'basic',
            'name' => 'Básico',
            'price' => '47,90',
            'benefits' => [
                ['text' => '5 análises por mês'],
                ['text' => 'Suporte por email']
            ],
            'button_text' => 'Selecionar Plano',
            'highlight' => false
        ],
        [
            'id' => 'pro',
            'name' => 'Profissional',
            'price' => '157,90',
            'benefits' => [
                ['text' => 'Análises ilimitadas', 'highlight' => true],
                ['text' => 'Suporte prioritário'],
                ['text' => 'Acesso antecipado'],
                ['text' => 'Até 3 contas']
            ],
            'button_text' => 'Selecionar Plano',
            'highlight' => true
        ],
        [
            'id' => 'enterprise',
            'name' => 'Empresarial',
            'price' => 'A partir de 197,90',
            'benefits' => [
                ['text' => 'Tudo do plano Pro'],
                ['text' => 'Personalização'],
                ['text' => 'Múltiplos acessos'],
                ['text' => 'Suporte dedicado']
            ],
            'button_text' => 'Falar com Vendas',
            'highlight' => false
        ]
    ];
    ?>
    <?php if (isset($plansData) && is_array($plansData)): ?>
      <?php foreach ($plansData as $plan): ?>
        <?= View::render('components/ui/plan_card', ['plan' => $plan]) ?>
      <?php endforeach ?>
    <?php endif ?>
    */
    ?>
    <?php // Se o controller passar $plans no formato correto para plan_card.php, usar o loop abaixo: ?>
    <?php if (isset($plans) && is_array($plans)): ?>
      <?php foreach ($plans as $plan): ?>
        <?= View::render('components/ui/plan_card', ['plan' => $plan]) ?>
      <?php endforeach ?>
    <?php endif ?>
  </div>
</section>

<section id="faq" class="faq">
    <h2><?= $faq['title'] ?></h2>
    <div class="faq-container">
        <?php if (isset($faq['items']) && is_array($faq['items'])): ?>
            <?php foreach ($faq['items'] as $item): ?>
                <?= View::render('components/ui/faq_item', ['item' => $item]) ?>
            <?php endforeach ?>
        <?php endif ?>
    </div>
</section>