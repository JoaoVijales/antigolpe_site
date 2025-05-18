<?php use App\Utils\View; /** @var string $type */ ?>
<?php /** @var string $message */ ?>
<div class="alert <?= $type ?>">
  <?= View::svg('icon-' . $type) ?>
  <span><?= View::escape($message) ?></span>
</div>