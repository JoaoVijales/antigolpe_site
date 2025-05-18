<?php use App\Utils\View; /** @var array $chartData */ ?>
<div class="chart-container">
  <canvas 
    id="<?= $chartData['id'] ?>" 
    data-labels="<?= View::escape(json_encode($chartData['labels'])) ?>"
    data-values="<?= View::escape(json_encode($chartData['values'])) ?>"
    data-type="<?= $chartData['type'] ?>"
  ></canvas>
</div>