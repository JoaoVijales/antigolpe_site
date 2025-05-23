<?php

use App\Utils\View;

?>

<section class="form-basic" id="<?= $form_data['id'] ?>">
  <?= View::render('components/ui/form_basic', ['form_data' => $form_data]) ?>
</section>

