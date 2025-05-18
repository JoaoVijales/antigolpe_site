<?php use App\Utils\View; use App\Utils\Container; ?>
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
  
  <!-- CSS Principal -->
  <link rel="stylesheet" href="<?= View::asset('css/style.css') ?>">

  <style>
    /* CSS inline ou estilos específicos podem vir aqui, se necessário */
    /* A lógica de inclusão do style.css via PHP foi removida */
  </style>
</head>
<body>
  <?= View::render('components/layout/header') ?>
  <?= View::render('components/layout/auth_popups') ?>

  <main>
    <?= $content ?>
  </main>

  <?= View::render('components/layout/footer') ?>

  <?php
  // Acessar o container globalmente para obter o serviço
  try {
      $googleAnalyticsService = Container::getInstance()->get('google_analytics');
      if ($googleAnalyticsService && method_exists($googleAnalyticsService, 'getTrackingCode')) {
          echo $googleAnalyticsService->getTrackingCode();
      } else {
          // Logar um erro se o serviço não for encontrado ou não tiver o método
           error_log("ERRO: Serviço 'google_analytics' não encontrado ou método getTrackingCode() ausente.");
      }
  } catch (\Throwable $e) {
      // Logar qualquer exceção ao tentar obter o serviço
       error_log("ERRO ao obter serviço 'google_analytics': " . $e->getMessage());
  }
  ?>
  <script type="module">
    <?php
    // Usar ROOT_PATH também para o JS
    $jsPath = (defined('ROOT_PATH') ? ROOT_PATH : __DIR__ . '/../../') . '/public/assets/js/main.js';
     if (file_exists($jsPath)) {
         echo file_get_contents($jsPath); // Mantido a inclusão direta do JS por enquanto
     } else {
         // Logar um erro ou warning se o arquivo JS não for encontrado
          error_log("ERRO: Arquivo JS não encontrado em: " . $jsPath);
     }
    ?>
  </script>
</body>
</html>