<?php
/** @var array $form_data */


?>

<div class="form-contact"> <!-- Usando classe existente que parece agrupar elementos de formulário -->
    <?php if (!empty($form_data['title'])) : ?>
        <h3 class="footer-title"><?php echo htmlspecialchars($form_data['title']); ?></h3> <!-- Reutilizando uma classe de título do footer -->
    <?php endif; ?>

    <?php if (!empty($form_data['description'])) : ?>
        <p class="footer-text"><?php echo htmlspecialchars($form_data['description']); ?></p> <!-- Reutilizando uma classe de texto do footer -->
    <?php endif; ?>

    <form action="<?php echo htmlspecialchars($form_data['form_action']); ?>" method="post">
        <div class="form-group"> <!-- Agrupamento simples, pode ser necessário ajustar CSS -->
            <label for="<?= $form_data['id_input'] ?>" class="footer-text" style="display: none;">Número de Telefone:</label> <!-- Label para acessibilidade -->
            <input type="tel" id="<?= $form_data['id_input'] ?>" name="<?= $form_data['id_input'] ?>" class="input-email" placeholder="<?php echo htmlspecialchars($form_data['phone_input_placeholder']); ?>" required>
        </div>

        <button id="<?= $form_data['id_button'] ?>" type="submit" class="contact-buton">
            <span class="button-text">
                <?php echo htmlspecialchars($form_data['button_text']); ?>
            </span>
        </button>
    </form>
</div>

<?php
// Exemplo de como usar este template em outro arquivo PHP:
/*
<?php
$form_data = [
    'title' => 'Receba Novidades',
    'description' => 'Deixe seu telefone para receber nossas últimas promoções.',
    'button_text' => 'Quero Receber',
    'form_action' => 'salvar_telefone.php'
];

include 'resources/views/components/ui/form_basic.php';
?>
*/
?>
