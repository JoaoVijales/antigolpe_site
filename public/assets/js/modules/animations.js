// Controla animações baseadas em scroll
export class ScrollAnimator {
    static init() {
      this.setupScrollTriggers();
      this.setupFAQInteractions();
    }
  
    static setupScrollTriggers() {
      const animateOnScroll = (elements) => {
        elements.forEach(el => {
          const rect = el.getBoundingClientRect();
          const isVisible = rect.top < window.innerHeight * 0.8;
          el.classList.toggle('animate', isVisible);
        });
      };
  
      window.addEventListener('scroll', () => {
        animateOnScroll(document.querySelectorAll('[data-animate]'));
      });
  
      // Dispara inicialmente
      animateOnScroll(document.querySelectorAll('[data-animate]'));
    }
  }