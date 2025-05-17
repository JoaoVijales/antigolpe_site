<?php /** @var string $type */ ?>
<?php /** @var string $message */ ?>
<div class="alert <?= $type ?>">
  <?= $this->svg('icon-' . $type) ?>
  <span><?= $this->escape($message) ?></span>
</div>