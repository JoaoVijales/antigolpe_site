






<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  
  <!-- SEO Meta Tags -->
  <meta name="description" content="AntiGolpe - Verifique anúncios em segundos e evite cair em golpes. Receba um veredicto de segurança via WhatsApp em minutos.">
  <meta name="keywords" content="anti golpe, verificação de anúncios, segurança online, golpes digitais, proteção contra golpes">
  <meta name="author" content="AntiGolpe">
  <meta name="robots" content="index, follow">
  
  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://antigolpe.com/">
  <meta property="og:title" content="AntiGolpe - Proteção contra golpes online">
  <meta property="og:description" content="Verifique anúncios em segundos e evite cair em golpes. Receba um veredicto de segurança via WhatsApp em minutos.">
  <meta property="og:image" content="https://antigolpe.com/og-image.jpg">
  
  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image">
  <meta property="twitter:url" content="https://antigolpe.com/">
  <meta property="twitter:title" content="AntiGolpe - Proteção contra golpes online">
  <meta property="twitter:description" content="Verifique anúncios em segundos e evite cair em golpes. Receba um veredicto de segurança via WhatsApp em minutos.">
  <meta property="twitter:image" content="https://antigolpe.com/twitter-image.jpg">
  
  <!-- Canonical URL -->
  <link rel="canonical" href="https://antigolpe.com/">
  
  <!-- CSS -->
  <link rel="stylesheet" href="/assets/css/style.css">
  
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
  <title>AntiGolpe - Proteção contra golpes online</title>

  <!-- Firebase SDK -->
  <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
  <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-auth.js"></script>
</head>
<body>
  <!-- Popup de Cadastro -->
  <div id="signupPopup" class="popup">
    <div class="popup-content">
      <button class="close-popup" aria-label="Fechar popup">&times;</button>
      <h2 class="popup-title">Criar Conta</h2>
      
      <button class="google-btn">
        <img src="https://www.google.com/favicon.ico" alt="Ícone do Google" class="google-icon">
        <span>Continuar com Google</span>
      </button>
      
      <div class="divider">
        <span>ou</span>
      </div>
      
      <form class="signup-form">
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
          <label for="password">Senha</label>
          <input type="password" id="password" name="password" required>
        </div>
        
        <button type="submit" class="submit-btn">Criar Conta</button>
      </form>
      
      <p class="login-link">
        Já tem uma conta? <a href="#" id="showLogin">Fazer login</a>
      </p>
    </div>
  </div>
  
  <!-- Popup de Login -->
  <div id="loginPopup" class="popup">
    <div class="popup-content">
      <button class="close-popup" aria-label="Fechar popup">&times;</button>
      <h2 class="popup-title">Fazer Login</h2>
      
      <button class="google-btn">
        <img src="https://www.google.com/favicon.ico" alt="Ícone do Google" class="google-icon">
        <span>Continuar com Google</span>
      </button>
      
      <div class="divider">
        <span>ou</span>
      </div>
      
      <form class="login-form">
        <div class="form-group">
          <label for="loginEmail">Email</label>
          <input type="email" id="loginEmail" name="email" required>
        </div>
        
        <div class="form-group">
          <label for="loginPassword">Senha</label>
          <input type="password" id="loginPassword" name="password" required>
        </div>
        
        <button type="submit" class="submit-btn">Entrar</button>
      </form>
      
      <p class="signup-link">
        Não tem uma conta? <a href="#" id="showSignup">Criar conta</a>
      </p>
    </div>
  </div>

  <header class="page">
    <nav class="menu" role="navigation" aria-label="Menu principal">
      <div class="menu-logo">
        <img class="logo-icon" src="/assets/images/logo-icon0.png" alt="Logo AntiGolpe" />
        <div class="logo-name">
          <h1 class="logo-name-text">AntiGolpe</h1>
        </div>
      </div>
      <ul class="menu-nav">
        <li><a href="#hero" class="nav-link"><span class="nav-link-text">Home</span></a></li>
        <li><a href="#recursos" class="nav-link"><span class="nav-link-text">Recursos</span></a></li>
        <li><a href="#precos" class="nav-link"><span class="nav-link-text">Preços</span></a></li>
        <li><a href="#faq" class="nav-link"><span class="nav-link-text">FAQ</span></a></li>
        <li><a href="#contato" class="nav-link"><span class="nav-link-text">Contato</span></a></li>
      </ul>
    </nav>

    <main>
      <section class="conatiner-hero" aria-labelledby="hero-title">
        <div id="hero" class="hero">
          <div class="hero-content">
            <h1 id="hero-title" class="hero-title">
              Pare de cair em golpes: verifique anúncios em segundos
            </h1>
            <p class="text">
              Envie o link ou screenshot do anúncio e receba um veredicto de
              segurança via WhatsApp em minutos.
            </p>
            <button class="cta-buton" aria-label="Começar a usar o AntiGolpe">
              <span class="button-text">Começar Agora</span>
            </button>
          </div>
          <div class="hero-img">
            <img class="whtsp-mockup" src="/assets/images/whtsp-mockup0.png" alt="Demonstração do WhatsApp AntiGolpe" />
          </div>
        </div>

        <section id="recursos" class="features" aria-labelledby="recursos-title">
          <h2 id="recursos-title" class="title">Principais Recursos</h2>
          <div class="features-grid">
            <div class="grid-row-a">
              <article class="features-card">
                <img class="anlize-icon" src="/assets/images/anlize-icon0.svg" alt="Ícone de análise" />
                <h3 class="text2">Analise em tempo real</h3>
              </article>
              <article class="features-card">
                <img class="play-icon" src="/assets/images/play-icon0.svg" alt="Ícone de cobertura" />
                <h3 class="text2">Cobertura ampla de anúncios</h3>
              </article>
              <article class="features-card">
                <img class="note-icon" src="/assets/images/note-icon0.svg" alt="Ícone de relatório" />
                <h3 class="text2">Relatório detalhado do risco</h3>
              </article>
            </div>
            <div class="grid-row-b">
              <article class="features-card">
                <img class="menssage-icon" src="/assets/images/mail-send-email-message-send-email-paper-airplane-deliver0.svg" alt="Ícone de mensagem" />
                <h3 class="text3">Integração simples via WhatsApp</h3>
              </article>
              <article class="features-card">
                <img class="database-icon" src="/assets/images/database-icon0.svg" alt="Ícone de banco de dados" />
                <h3 class="text2">Histórico de verificações</h3>
              </article>
            </div>
          </div>
        </section>
      </section>

      <section id="precos" class="plans" aria-labelledby="planos-title">
        <h2 id="planos-title" class="title">Planos e Preços</h2>
        <div class="container-plans">
          <article class="card basic">
            <h3 class="plan-title">Básico</h3>
            <div class="plan-price">
              <div class="text-price">
                <span>
                  <span class="text-coin">R$</span>
                  <span class="text-nunber">7</span>
                </span>
              </div>
              <div class="text-cents">,90</div>
            </div>
            <ul class="text-plan">
              <li class="plan-benefit">Análise limitada: 6/mês</li>
              <li class="plan-benefit">Sem prioridade de tempo</li>
            </ul>
            <button class="cta-buton" aria-label="Assinar plano básico">
              <span class="button-text">Começar Agora</span>
            </button>
          </article>

          <article class="card pro">
            <h3 class="plan-title">Profissional</h3>
            <div class="plan-price">
              <div class="text-price">
                <span>
                  <span class="text-coin">R$</span>
                  <span class="text-nunber">97</span>
                </span>
              </div>
              <div class="text-cents">,90</div>
            </div>
            <ul class="text-plan">
              <li class="plan-benefit">Análise limitada: 100/mês</li>
              <li class="plan-benefit">Prioridade de tempo</li>
              <li class="plan-benefit">Acesso antecipado e gratuito a novas funcionalidades</li>
              <li class="plan-benefit">Até 3 contas diferentes</li>
            </ul>
            <button class="cta-buton" aria-label="Assinar plano profissional">
              <span class="button-text">Começar Agora</span>
            </button>
          </article>

          <article class="card empr">
            <h3 class="plan-title">Empresarial</h3>
            <div class="plan-price2">
              <div class="price-container">
                <div class="text-off">A partir de:</div>
              </div>
              <div class="value-container">
                <div class="text-price">
                  <span>
                    <span class="text-coin">R$</span>
                    <span class="text-nunber">57</span>
                  </span>
                </div>
                <div class="text-cents">,90</div>
              </div>
            </div>
            <ul class="text-plan">
              <li class="plan-benefit">Tudo do Plano Profissional</li>
              <li class="plan-benefit">Análise por demanda</li>
              <li class="plan-benefit2">Personalize conforme sua necessidade</li>
              <li class="plan-benefit2">Múltiplos acessos para sua equipe</li>
            </ul>
              <button class="cta-buton" aria-label="Solicitar contato para plano empresarial">
              <span class="button-text">Entrar em Contato</span>
            </button>
          </article>
        </div>
      </section>

      <section id="faq" class="faq" aria-labelledby="faq-title">
        <h2 id="faq-title" class="title">Perguntas Frequentes</h2>
        <div class="faq-container">
          <details class="faq-content">
            <summary class="faq-title">
              Como o bot identifica se algo é golpe?
              <img class="arrow-icon" src="/assets/images/arrow-icon0.svg" alt="" />
            </summary>
            <p>
              Nosso agente faz uma busca rápida na internet para verificar se o link, produto ou loja tem sinais de golpe. Ele analisa:
            </p>
            <ul>
              <li>Se o site é oficial ou usa um domínio estranho</li>
              <li>Se o domínio foi criado recentemente, o que pode indicar fraude</li>
              <li>Se a loja tem reclamações em sites como o Reclame Aqui, Procon ou fóruns de denúncias</li>
              <li>Se as imagens ou textos foram copiados de outras páginas</li>
              <li>Se há promessas exageradas ou condições fora da realidade</li>
            </ul>
          </details>

          <details class="faq-content">
            <summary class="faq-title">
              Posso usar o bot para checar links do WhatsApp ou Instagram?
              <img class="arrow-icon" src="/assets/images/arrow-icon1.svg" alt="" />
            </summary>
            <p>
              Sim! Nosso bot é capaz de analisar links de diversas plataformas, incluindo:
            </p>
            <ul>
              <li>Links do WhatsApp</li>
              <li>Links do Instagram</li>
              <li>Links de sites de e-commerce</li>
              <li>Links de anúncios em marketplaces</li>
              <li>Links de mensagens suspeitas</li>
            </ul>
          </details>

          <details class="faq-content">
            <summary class="faq-title">
              O bot também verifica mensagens de texto?
              <img class="arrow-icon" src="/assets/images/arrow-icon2.svg" alt="" />
            </summary>
            <p>
              Sim! Além de links, nosso bot pode analisar o conteúdo de mensagens de texto para identificar:
            </p>
            <ul>
              <li>Padrões comuns de golpes</li>
              <li>Promessas suspeitas</li>
              <li>Pedidos de informações pessoais</li>
              <li>Urgência indevida</li>
              <li>Ofertas muito boas para serem verdade</li>
            </ul>
          </details>

          <details class="faq-content">
            <summary class="faq-title">
              Meus dados estão seguros aqui?
              <img class="arrow-icon" src="/assets/images/arrow-icon3.svg" alt="" />
            </summary>
            <p>
              Absolutamente! Segurança é nossa prioridade:
            </p>
            <ul>
              <li>Não armazenamos seus dados pessoais</li>
              <li>As análises são feitas em tempo real</li>
              <li>Utilizamos criptografia de ponta a ponta</li>
              <li>Não compartilhamos informações com terceiros</li>
              <li>Você pode excluir suas verificações a qualquer momento</li>
            </ul>
          </details>
        </div>
      </section>
    </main>

    <footer id="contato" class="footer">
      <!-- Popup de Feedback -->
      <div id="feedbackPopup" class="feedback-popup">
        <div class="feedback-content">
          <span class="material-icons feedback-icon">mark_email_read</span>
          <h3 class="feedback-title">Mensagem Enviada!</h3>
          <p class="feedback-text">Entraremos em contato em breve.</p>
          <button class="feedback-close">OK</button>
        </div>
      </div>

      <div class="footer-container">
        <div class="footer-content">

          <h2 class="footer-title">Proteja-se</h2>
          <p class="footer-text">Não caia mais em golpes</p>
        </div>
        <address class="contact-content">
          <div class="footer-content">
            <h2 class="footer-title">Contato</h2>
            <p class="email">Email: <a href="mailto:contato@antigolpe.com">contato@antigolpe.com</a></p>
            <p class="telefone">Telefone: <a href="tel:+5511999999999">(11) 99999-9999</a></p>
          </div>
          <div class="footer-content">
            <form class="form-contact">
              <input class="input-email" type="email" placeholder="Digite seu email">
              <button class="contact-buton" type="submit">Entrar em Contato</button>
            </form>
          </div>
        </address>
      </div>
      <div class="footer-rights">
        <p class="rights-text">© AntiGolpe 2025. Todos os direitos reservados.</p>
      </div>
    </footer>
  </header>

  <script>

       // Your web app's Firebase configuration
    const firebaseConfig = {
      apiKey: <?= json_encode(getenv('FIREBASE_API_KEY')); ?>,
      authDomain: <?= json_encode(getenv('FIREBASE_AUTH_DOMAIN')); ?>,
      projectId: <?= json_encode(getenv('FIREBASE_PROJECT_ID')); ?>,
      storageBucket: <?= json_encode(getenv('FIREBASE_STORAGE_BUCKET')); ?>,
      messagingSenderId: <?= json_encode(getenv('FIREBASE_MESSAGING_SENDER_ID')); ?>,
      appId: <?= json_encode(getenv('FIREBASE_APP_ID')); ?>
    };


    

    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
    // Interatividade do Menu
    let click = false;
    document.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        const targetId = link.getAttribute('href').substring(1);
        const targetSection = document.getElementById(targetId);
        
        // Remove a classe animate de todos os elementos antes de rolar
        document.querySelectorAll('.features-card, .card, .faq-content').forEach(element => {
          element.classList.remove('animate');
        });
        
        // Rola para a seção
        targetSection.scrollIntoView({ behavior: 'smooth' });
        
        // Força uma atualização da animação após um pequeno delay
        setTimeout(() => {
          updateAnimation();
        }, 100);
      });
    });

    // Interatividade do FAQ
    document.querySelectorAll('details').forEach(detail => {
      detail.addEventListener('toggle', (e) => {
        const arrow = e.target.querySelector('img');
        if (e.target.open) {
          arrow.style.transform = 'rotate(180deg)';
        } else {
          arrow.style.transform = 'rotate(0deg)';
        }
      });
    });


    // Adiciona classe active ao menu quando scrollar
    window.addEventListener('scroll', () => {
      const sections = document.querySelectorAll('section');
      const navLinks = document.querySelectorAll('.nav-link');
      
      let current = '';
      sections.forEach(section => {
        const sectionTop = section.offsetTop;
        if (pageYOffset >= sectionTop - 60) {
          current = section.getAttribute('id');
        }
      });

      navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href').substring(1) === current) {
          link.classList.add('active');
        }
      });
    });

    // Configuração das animações
    const animationConfig = {
      'grid-row-a': ['left', 'top', 'right'],
      'grid-row-b': ['left', 'bottom'],
      'container-plans': ['left', 'bottom', 'right']
    };

    // Aplica as animações aos cards
    Object.entries(animationConfig).forEach(([rowClass, directions]) => {
      const row = document.querySelector(`.${rowClass}`);
      if (row) {
        const cards = row.querySelectorAll('.features-card, .card');
        cards.forEach((card, index) => {
          card.setAttribute('data-animate', directions[index] || 'bottom');
        });
      }
    });

    // Função para calcular o progresso do scroll
    function getScrollProgress(element) {
      const rect = element.getBoundingClientRect();
      const windowHeight = window.innerHeight;
      const elementTop = rect.top;
      const elementHeight = rect.height;
      
      // Calcula o progresso baseado na posição do elemento
      let progress = 0;
      
      if (elementTop < windowHeight) {
        progress = Math.min(1, (windowHeight - elementTop) / (windowHeight + elementHeight));
      }
      
      return progress;
    }

    // Função para atualizar a animação baseada no scroll
    function updateAnimation() {
      document.querySelectorAll('.grid-row-a, .grid-row-b, .container-plans, .faq-container').forEach(section => {
        const progress = getScrollProgress(section);
        const elements = section.querySelectorAll('.features-card, .card, .faq-content');
        
        elements.forEach(element => {
          if (progress > 0.1) {
            element.classList.add('animate');
          } else {
            element.classList.remove('animate');
          }
        });
      });
    }

    // Adiciona o evento de scroll
    window.addEventListener('scroll', () => {
      requestAnimationFrame(updateAnimation);
    });

    // Inicializa a animação
    updateAnimation();

    // Controle dos Popups
    const signupPopup = document.getElementById('signupPopup');
    const loginPopup = document.getElementById('loginPopup');
    const showSignupLink = document.getElementById('showSignup');
    const showLoginLink = document.getElementById('showLogin');
    const closeButtons = document.querySelectorAll('.close-popup');

    // Função para abrir o popup de cadastro
    function openSignupPopup() {
      signupPopup.classList.add('active');
      loginPopup.classList.remove('active');
    }

    // Função para abrir o popup de login
    function openLoginPopup() {
      loginPopup.classList.add('active');
      signupPopup.classList.remove('active');
    }

    // Função para fechar os popups
    function closePopups() {
      signupPopup.classList.remove('active');
      loginPopup.classList.remove('active');
    }

    // Event Listeners
    showSignupLink.addEventListener('click', (e) => {
      e.preventDefault();
      openSignupPopup();
    });

    showLoginLink.addEventListener('click', (e) => {
      e.preventDefault();
      openLoginPopup();
    });

    closeButtons.forEach(button => {
      button.addEventListener('click', closePopups);
    });

    // Fechar popup ao clicar fora
    window.addEventListener('click', (e) => {
      if (e.target === signupPopup || e.target === loginPopup) {
        closePopups();
      }
    });

    // Manipulação dos formulários
    document.querySelector('.signup-form').addEventListener('submit', (e) => {
      e.preventDefault();
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;
      
      // Lógica de cadastro com Firebase
      firebase.auth().createUserWithEmailAndPassword(email, password)
        .then((userCredential) => {
          // Usuário cadastrado e logado com sucesso
          console.log('Usuário cadastrado e logado:', userCredential.user);
          alert('Cadastro realizado com sucesso!');
          closePopups();
        })
        .catch((error) => {
          const errorCode = error.code;
          const errorMessage = error.message;
          console.error('Erro no cadastro:', errorCode, errorMessage);
          alert(`Erro no cadastro: ${errorMessage}`);
        });
    });

    document.querySelector('.login-form').addEventListener('submit', (e) => {
      e.preventDefault();
      const email = document.getElementById('loginEmail').value;
      const password = document.getElementById('loginPassword').value;

      // Lógica de login com Firebase
      firebase.auth().signInWithEmailAndPassword(email, password)
        .then((userCredential) => {
          // Usuário logado com sucesso
          console.log('Usuário logado:', userCredential.user);
          alert('Login realizado com sucesso!');
          closePopups();
        })
        .catch((error) => {
          const errorCode = error.code;
          const errorMessage = error.message;
          console.error('Erro no login:', errorCode, errorMessage);
          alert(`Erro no login: ${errorMessage}`);
        });
    });

    // Login com Google (Placeholder para Firebase Google Auth)
    document.querySelectorAll('.google-btn').forEach(button => {
      button.addEventListener('click', () => {
        // Aqui você pode adicionar a lógica de login com Google do Firebase
        console.log('Login com Google (implementando Firebase Google Auth)');

        const provider = new firebase.auth.GoogleAuthProvider();
        firebase.auth().signInWithPopup(provider)
          .then((result) => {
            // O popup foi fechado e o usuário logou com sucesso
            const user = result.user;
            console.log('Usuário logado com Google:', user);
            alert('Login com Google realizado com sucesso!');
            closePopups();
          })
          .catch((error) => {
            // Lidar com erros durante o login com Google
            const errorCode = error.code;
            const errorMessage = error.message;
            const email = error.email;
            const credential = error.credential;
            console.error('Erro no login com Google:', errorCode, errorMessage, email, credential);
            alert(`Erro no login com Google: ${errorMessage}`);
          });
      });
    });

    // Abrir popup de cadastro ao clicar no botão "Começar Agora"
    document.querySelectorAll('.cta-buton').forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        openSignupPopup();
      });
    });

    // Manipulação do formulário de contato
    document.querySelector('.form-contact').addEventListener('submit', (e) => {
      e.preventDefault();
      const email = document.querySelector('.input-email').value;
      
      // Aqui você pode adicionar a lógica de envio do email
      console.log('Email de contato:', email);
      
      // Mostra o popup de feedback
      const feedbackPopup = document.getElementById('feedbackPopup');
      feedbackPopup.classList.add('active');
      
      // Limpa o formulário
      document.querySelector('.input-email').value = '';
    });

    // Fechar popup de feedback
    document.querySelector('.feedback-close').addEventListener('click', () => {
      document.getElementById('feedbackPopup').classList.remove('active');
    });

    // Fechar popup de feedback ao clicar fora
    document.getElementById('feedbackPopup').addEventListener('click', (e) => {
      if (e.target === e.currentTarget) {
        e.currentTarget.classList.remove('active');
      }
    });
  </script>
</body>
</html> 