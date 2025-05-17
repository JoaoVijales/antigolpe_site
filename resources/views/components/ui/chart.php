<?php /** @var array $chartData */ ?>
<div class="chart-container">
  <canvas 
    id="<?= $chartData['id'] ?>" 
    data-labels="<?= $this->escape(json_encode($chartData['labels'])) ?>"
    data-values="<?= $this->escape(json_encode($chartData['values'])) ?>"
    data-type="<?= $chartData['type'] ?>"
  ></canvas>
</div>