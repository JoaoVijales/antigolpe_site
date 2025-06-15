<?php use App\Utils\View; use App\Utils\Container; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <?= View::render('components/head', [
    'title' => $title ?? 'É Cilada Bot',
    'meta' => [
      'description' => 'Verifique anúncios em segundos e evite cair em golpes',
      'keywords' => 'anti golpe, verificação de anúncios, segurança online, É cilada bot, golpes online, eciladabot',
    ]
  ]) ?>
  
  <style>
   a,
   button,
   input,
   select,
   h1,
   h2,
   h3,
   h4,
   h5,
   * {
       box-sizing: border-box;
       margin: 0;
       padding: 0;
       border: none;
       text-decoration: none;
       background: none;
   
       -webkit-font-smoothing: antialiased;
   }
   
   menu, ol, ul {
       list-style-type: none;
       margin: 0;
       padding: 0;
   }

   .features-card, .card {
     opacity: 0.5;
     transition: all 1.5s cubic-bezier(0.165, 0.84, 0.44, 1);
     will-change: transform, opacity;
   }

   /* Animações por posição */
   .features-card[data-animate="left"], .card[data-animate="left"] { transform: translateX(-100px); }
   .features-card[data-animate="right"], .card[data-animate="right"] { transform: translateX(100px); }
   .features-card[data-animate="top"], .card[data-animate="top"] { transform: translateY(-50px); }
   .features-card[data-animate="bottom"], .card[data-animate="bottom"] { transform: translateY(50px); }

   /* Estado final da animação */
   .features-card.animate, .card.animate {
     opacity: 1;
     transform: translate(0) !important;
   }

   /* Delays escalonados */
   .features-card:nth-child(1), .card:nth-child(1) { transition-delay: 0.2s; }
   .features-card:nth-child(2), .card:nth-child(2) { transition-delay: 0.2s; }
   .features-card:nth-child(3), .card:nth-child(3) { transition-delay: 0.2s; }
   .features-card:nth-child(4), .card:nth-child(4) { transition-delay: 0.2s; }
   .features-card:nth-child(5), .card:nth-child(5) { transition-delay: 0.2s; }

   /* Animação do FAQ */
   .faq-content {
     opacity: 0;
     transform: translateX(-100px);
     transition: all 1.5s cubic-bezier(0.165, 0.84, 0.44, 1);
     will-change: transform, opacity;
   }

   .faq-content.animate {
     opacity: 1;
     transform: translateX(0);
   }

   /* Delays escalonados para o FAQ */
   .faq-content:nth-child(1) { transition-delay: 0.2s; }
   .faq-content:nth-child(2) { transition-delay: 0.4s; }
   .faq-content:nth-child(3) { transition-delay: 0.6s; }
   .faq-content:nth-child(4) { transition-delay: 0.8s; }

   /* Estilos do conteúdo do FAQ */
   .faq-content p {
     margin: 1rem 0;
     line-height: 1.6;
   }

   .faq-content ul {
     list-style: none;
     padding-left: 1rem;
     margin: 1rem 0;
   }

   .faq-content li {
     position: relative;
     padding-left: 1.5rem;
     margin-bottom: 0.5rem;
   }

   .faq-content li::before {
     content: "•";
     position: absolute;
     left: 0;
     color: var(--primary-color);
   }

   </style>

   <!-- Firebase -->
   <script src="https://www.gstatic.com/firebasejs/9.1.0/firebase-app-compat.js"></script>
   <script src="https://www.gstatic.com/firebasejs/9.1.0/firebase-auth-compat.js"></script>
   <!-- Adicione outros SDKs do Firebase que você usar, como Firestore, etc. -->
</head>
<body>
  <?= View::render('components/layout/header') ?>
  <?= View::render('components/layout/auth_popups') ?>

  <main style="margin-bottom: 4rem;">
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
  <script type="module" src="<?= View::asset('js/main.js') ?>"></script>
</body>
</html>