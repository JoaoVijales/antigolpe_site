<?php
/** @var array $item */
// TODO: Refatorar - Este componente de item de FAQ é muito similar a components/sections/pricing.php (que tem nome incorreto). Considere consolidar a lógica e o HTML em um único componente de item de FAQ reutilizável.
?>
<details class="faq-content" <?= $item['is_open'] ? 'open' : '' ?>>
  <summary class="faq-title">
    <?= htmlspecialchars($item['question']) ?>
    <span class="material-icons">expand_more</span>
  </summary>
  <p>
    <?= htmlspecialchars($item['response']) ?>
  </p>
  <?php if (isset($item['list'])): ?>
    <ul>
      <?php foreach ($item['list'] as $point): ?>
        <li><?= htmlspecialchars($point) ?></li>
      <?php endforeach ?>
    </ul>
  <?php endif ?>
</details>
