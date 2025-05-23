<?php
/** @var array $form_data */


?>

<div class="card_form"> <!-- Usando classe existente que parece agrupar elementos de formulário -->

    <div class="card-content">
        <?php if (!empty($form_data['title'])) : ?>
            <h3 class="title"><?php echo htmlspecialchars($form_data['title']); ?></h3> <!-- Reutilizando uma classe de título do footer -->
        <?php endif; ?>

        <?php if (!empty($form_data['description'])) : ?>
            <p class="text-form"><?php echo htmlspecialchars($form_data['description']); ?></p> <!-- Reutilizando uma classe de texto do footer -->
        <?php endif; ?>
    </div>

    <form class="form-group" action="<?php echo htmlspecialchars($form_data['form_action']); ?>" method="post">
        <div class="form-group"> <!-- Agrupamento simples, pode ser necessário ajustar CSS -->
            <label for="<?= $form_data['id_input'] ?>" class="footer-text" style="display: none;">Número de Telefone:</label> <!-- Label para acessibilidade -->
            <input type="tel" id="<?= $form_data['id_input'] ?>" name="<?= $form_data['id_input'] ?>" class="input-email" placeholder="<?php echo htmlspecialchars($form_data['phone_input_placeholder']); ?>" required>
        </div>
        <button id="<?= $form_data['id_button'] ?>" type="submit" class="cta-buton">
            <span class="button-text">
                <?php echo htmlspecialchars($form_data['button_text']); ?>
            </span>
        </button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('#<?= $form_data['id'] ?>');
        if (form == 'form-verify-whatsapp') {
            const input = form.querySelector('#code');
            input.addEventListener('input', function() {
                if (input.value.length == 6) {
                    const code = input.value;
                    try {
                        const idToken = localStorage.getItem('idToken');
                        const [response, data] = await backendService.verify_phone(code,idToken);
                        if (response.status == 200) {
                            window.location.href = 'https://wa.me/5511999999999';
                        } else {
                            alert('Erro ao verificar o código, tente novamente.');
                        }
                    } catch (error) {
                        console.error('Erro ao verificar o código:', error);
                        alert('Erro ao verificar o código, tente novamente.');
                        
                }
            }else {
                alert('O código deve conter 6 dígitos.');
            }
        );
        }
        if (form == 'form-register-phone') {
            const input = form.querySelector('#<?= $form_data['id_input'] ?>');
            input.addEventListener('input', function() {
                if (input.value.length == 15) {
                    const phone = input.value;
                    try {
                        const idToken = localStorage.getItem('idToken');
                        const [response, data] = await backendService.register_phone(phone,idToken);
                        if (response.status == 200) {
                            window.location.href = '/dashboard/verify_phone';
                        } else {
                            alert('Erro ao registrar o telefone, tente novamente.');
                        }
                    } catch (error) {
                        console.error('Erro ao registrar o telefone:', error);
                        alert('Erro ao registrar o telefone, tente novamente.');
                    }
                }
            });
        }
    });
</script>
