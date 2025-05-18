<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <?= View::render('components/head', [
    'title' => $title ?? 'AntiGolpe',
    'meta' => [
      'description' => 'Verifique anúncios em segundos e evite cair em golpes',
      'keywords' => 'anti golpe, verificação de anúncios, segurança online'
    ]
  ]) ?>
  
  <style>
    /* Manter todos os estilos originais */
    <?= file_get_contents(__DIR__ . '/../../public/assets/css/style.css') ?>
  </style>
</head>
<body>
  <?= $content ?>

  <?= View::service('google_analytics')->getTrackingCode() ?>
  <script type="module">
    <?= file_get_contents(__DIR__ . '/../../public/assets/js/main.js') ?>
  </script>
</body>
</html>