<?php use App\Utils\View; ?>
<?php
/** @var array $item */
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