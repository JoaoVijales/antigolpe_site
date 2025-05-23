<?php use App\Utils\View; ?>

<section class="config">
  <div class="card_config">
      <h3 class="title">Configurações</h3>
      <div class="card-content-config">
          <div class="card-item-config">
              <h4 class="text-config">email:</h4>
              <p class="card-item-value">
                <script>
                  const email = localStorage.getItem('email');
                  if (email) {
                    document.write(email);
                  } else {
                    document.write('Não cadastrado');
                  }
                </script>
              </p>
          </div>
          <div class="card-item-config">
              <h4 class="text-config">Telefone:</h4>
              <p class="card-item-value">
                <script>
                  const phone = localStorage.getItem('phone');
                  if (phone) {
                    document.write(phone);
                  } else {
                    document.write('Não cadastrado');
                  }
                </script>
              </p>
          </div>
          <div class="card-item-config">
              <h4 class="text-config">Plano:</h4>
              <p class="card-item-value">
                <script>
                  const plan = localStorage.getItem('plan');
                  if (plan) {
                    document.write(plan);
                  } else {
                    document.write('Não cadastrado');
                  }
                </script>
              </p>
          </div>
      </div>
  </div>
</section>

<script>
    const idToken = localStorage.getItem('idToken');
    if (!idToken) {
      window.location.href = '/';
    }
    const plan = localStorage.getItem('plan');
    const phone = localStorage.getItem('phone');
    const email = localStorage.getItem('email');
</script>