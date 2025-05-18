<?php use App\Utils\View; ?>
<?php
/** @var array $item */
// TODO: Refatorar - O nome deste arquivo (pricing.php) está incorreto, o conteúdo é um componente de item de FAQ. Além disso, o conteúdo é muito similar a components/ui/faq_item.php. Considere consolidar em um único componente de item de FAQ e corrigir a nomeação.
?>
<details class="faq-content">
  <summary class="faq-title">
    <?= $item['question'] ?>
    <img class="arrow-icon" src="<?= View::asset('images/arrow-icon.svg') ?>" alt="">
  </summary>
  <div class="faq-answer">
    <?php if (is_array($item['answer'])) : ?>
      <ul>
        <?php foreach ($item['answer'] as $point) : ?>
          <li><?= $point ?></li>
        <?php endforeach ?>
      </ul>
    <?php else : ?>
      <p><?= $item['answer'] ?></p>
    <?php endif ?>
  </div>
</details>