// Gerencia a interatividade do menu e a navegação baseada em scroll
export class NavigationHandler {
    static init() {
        this.setupSmoothScroll();
        this.setupActiveClassOnScroll();
    }

    // Lógica para scroll suave ao clicar nos links do menu (copiada do index.php)
    static setupSmoothScroll() {
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = link.getAttribute('href').substring(1);
                const targetSection = document.getElementById(targetId);

                // Remove a classe animate de todos os elementos antes de rolar (mantido do script original)
                document.querySelectorAll('.features-card, .card, .faq-content').forEach(element => {
                    element.classList.remove('animate');
                });

                // Rola para a seção
                targetSection.scrollIntoView({ behavior: 'smooth' });

                // Força uma atualização da animação após um pequeno delay (mantido do script original)
                setTimeout(() => {
                    // TODO: Chamar a função de atualização de animação do ScrollAnimator, se necessário
                    // ScrollAnimator.updateAnimation(); 
                    // No momento, a animação é baseada no scroll global, então a rolagem suave já a dispara.
                }, 100);
            });
        });
    }

    // Lógica para adicionar classe active ao menu quando scrollar (copiada do index.php)
    static setupActiveClassOnScroll() {
        window.addEventListener('scroll', () => {
            const sections = document.querySelectorAll('section');
            const navLinks = document.querySelectorAll('.nav-link');

            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                // Ajuste o offset (60) conforme a altura do seu header fixo, se houver
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
    }
} 