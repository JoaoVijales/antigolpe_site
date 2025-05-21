<?php use App\Utils\View; ?>

<section id="precos" class="plans" aria-labelledby="planos-title">
  <h2 id="planos-title" class="title">Planos e Preços</h2>
  <div class="container-plans">
    <?php if (isset($plans) && is_array($plans)): ?>
      <?php foreach ($plans as $plan): ?>
        <?= View::render('components/ui/plan_card', ['plan' => $plan]) ?>
      <?php endforeach ?>
    <?php endif ?>
  </div>
</section>

<section class="form-basic" id="<?= $form_data['id'] ?>">
  <?= View::render('components/ui/form_basic', ['form_data' => $form_data]) ?>
</section>

<section class="form-basic" id="<?= $verify_whatsapp_data['id'] ?>">
  <?= View::render('components/ui/form_basic', ['form_data' => $verify_whatsapp_data]) ?>
</section>

<script>
  // listener para esperar o dom estar carregado
  document.addEventListener('DOMContentLoaded', () => {
    const idToken = localStorage.getItem('idToken');
    const planCards = document.querySelectorAll('.plans');
    const formBasic = document.getElementById('form-basic');
    const formVerifyWhatsapp = document.getElementById('form-verify-whatsapp');
    if (idToken) {
    planCards.style.display = 'none';
    formVerifyWhatsapp.style.display = 'none';
    formBasic.style.display = 'flex';
    }
    else {
    formBasic.style.display = 'none';
    formVerifyWhatsapp.style.display = 'none';
    planCards.style.display = 'flex';
    }

    const verifyWhatsapp = document.getElementById('verify_whatsapp');
    verifyWhatsapp.addEventListener('click', async (e) => {
      const phone = localStorage.getItem('phone');
      const code = formVerifyWhatsapp.querySelector('input[name="code"]').value;
      e.preventDefault();
      const [status, data] = await api.update_phone(phone, code idToken);
      if (status === 200) {
        window.location.href = 'https://wa.me/5511999999999'; // colocar numero do bot
      }
      else {
        return error(data);
      }
    });

    const updatePhone = document.getElementById('upadte_phone');
    updatePhone.addEventListener('click', (e) => {
      e.preventDefault();
      const formData = new FormData(form);
      const phone = formData.get('phone');
      try {
        const [status, data] = await api.verify_phone(phone, idToken);
        if (status === 200) {
          localStorage.setItem('phone', phone);
          formBasic.style.display = 'none';
          formVerifyWhatsapp.style.display = 'flex';
        }
        else {
          return error(data);
        }
      }
      catch (error) {
        return error(error);
      }
        
      console.log(phone);
    });
  });
</script>