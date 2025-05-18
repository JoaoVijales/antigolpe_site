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
  
  <style>
    /* Manter todos os estilos originais */
    <?php
    // Tentar usar ROOT_PATH se definido, caso contrário, usar caminho relativo mais seguro
    $cssPath = (defined('ROOT_PATH') ? ROOT_PATH : __DIR__ . '/../../') . '/public/assets/css/style.css';
    if (file_exists($cssPath)) {
        echo file_get_contents($cssPath);
    } else {
        // Logar um erro ou warning se o arquivo CSS não for encontrado
         error_log("ERRO: Arquivo CSS não encontrado em: " . $cssPath);
    }
    ?>
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
         echo file_get_contents($jsPath);
     } else {
         // Logar um erro ou warning se o arquivo JS não for encontrado
          error_log("ERRO: Arquivo JS não encontrado em: " . $jsPath);
     }
    ?>
  </script>
</body>
</html>