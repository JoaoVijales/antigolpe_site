<?php
/** @var array $item */
// TODO: Refatorar - Este componente de item de FAQ é muito similar a components/sections/pricing.php (que tem nome incorreto). Considere consolidar a lógica e o HTML em um único componente de item de FAQ reutilizável.
?>
<details class="faq-item" <?= $item['is_open'] ? 'open' : '' ?>>
  <summary class="faq-question">
    <?= htmlspecialchars($item['question']) ?>
    <span class="material-icons">expand_more</span>
  </summary>
  <div class="faq-answer">
    <?php if (is_array($item['answer'])): ?>
      <ul>
        <?php foreach ($item['answer'] as $point): ?>
          <li><?= htmlspecialchars($point) ?></li>
        <?php endforeach ?>
      </ul>
    <?php else: ?>
      <p><?= htmlspecialchars($item['answer']) ?></p>
    <?php endif ?>
  </div>
</details>
