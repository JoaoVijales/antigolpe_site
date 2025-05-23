<?php

use App\Utils\View;

?>

<section class="form-basic" id="<?= $verify_whatsapp_data['id'] ?>" style="display: none;">
  <?= View::render('components/ui/form_basic', ['form_data' => $verify_whatsapp_data]) ?>
</section>