// Controla animações baseadas em scroll
export class ScrollAnimator {
    static init() {
      // Não precisamos mais do setupScrollTriggers original
      // this.setupScrollTriggers();
      
      // Integramos o setupFAQInteractions aqui se não for para um módulo separado
      // this.setupFAQInteractions(); // TODO: Refatorar FAQ para seu próprio módulo

      // Adiciona as animações aos cards com base na configuração
      this.setupAnimationAttributes();

      // Adiciona o evento de scroll para atualizar as animações
      window.addEventListener('scroll', () => {
        requestAnimationFrame(this.updateAnimation.bind(this));
      });

      // Inicializa a animação
      this.updateAnimation();
    }

    // Configuração das animações
    static animationConfig = {
      'grid-row-a': ['left', 'top', 'right'],
      'grid-row-b': ['left', 'bottom'],
      'container-plans': ['left', 'bottom', 'right']
    };

    static setupAnimationAttributes() {
      Object.entries(this.animationConfig).forEach(([rowClass, directions]) => {
        const row = document.querySelector(`.${rowClass}`);
        if (row) {
          const cards = row.querySelectorAll('.features-card, .card');
          cards.forEach((card, index) => {
            card.setAttribute('data-animate', directions[index] || 'bottom');
          });
        }
      });
    }

    // Função para calcular o progresso do scroll (copiada do index.php)
    static getScrollProgress(element) {
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

    // Função para atualizar a animação baseada no scroll (copiada do index.php)
    static updateAnimation() {
      // Incluindo .faq-container conforme o script original
      document.querySelectorAll('.grid-row-a, .grid-row-b, .container-plans, .faq-container').forEach(section => {
        const progress = this.getScrollProgress(section);
        // Incluindo .faq-content conforme o script original
        const elements = section.querySelectorAll('.features-card, .card, .faq-content');
        
        elements.forEach(element => {
          // O script original usava progress > 0.1 para adicionar a classe 'animate'
          // Mantendo essa lógica por enquanto, mas pode ser ajustada se necessário
          if (progress > 0.1) {
            element.classList.add('animate');
          } else {
            element.classList.remove('animate');
          }
        });
      });
    }

    // A lógica setupFAQInteractions original pode ser movida para um módulo de FAQ separado.
    // static setupFAQInteractions() { ... }
}