// Gerencia a exibição e o controle de popups
export class PopupHandler {
    static init() {
        this.setupPopups();
        this.setupEventListeners();
    }

    static setupPopups() {
        // Obtém referências aos popups (copiado do index.php)
        this.signupPopup = document.getElementById('signupPopup');
        this.loginPopup = document.getElementById('loginPopup');
        this.feedbackPopup = document.getElementById('feedbackPopup'); // Adicionado feedbackPopup
    }

    static openSignupPopup() {
        if (this.signupPopup) {
            this.signupPopup.classList.add('active');
        }
        if (this.loginPopup) {
            this.loginPopup.classList.remove('active');
        }
        // Garante que o feedbackPopup esteja fechado ao abrir outro
        if (this.feedbackPopup) {
          this.feedbackPopup.classList.remove('active');
        }
    }

    static openLoginPopup() {
        if (this.loginPopup) {
            this.loginPopup.classList.add('active');
        }
        if (this.signupPopup) {
            this.signupPopup.classList.remove('active');
        }
         // Garante que o feedbackPopup esteja fechado ao abrir outro
         if (this.feedbackPopup) {
           this.feedbackPopup.classList.remove('active');
         }
    }

    static closePopups() {
        console.log('closePopups');
        if (this.signupPopup) {
            console.log('signupPopup removendo active');
            this.signupPopup.classList.remove('active');
        }
        if (this.loginPopup) {
            this.loginPopup.classList.remove('active');
        }
        if (this.feedbackPopup) {
            this.feedbackPopup.classList.remove('active');
        }
    }

    static setupEventListeners() {
        // Event Listeners para abrir popups (copiado do index.php)
        const showSignupLink = document.getElementById('showSignup');
        const showLoginLink = document.getElementById('showLogin');
        // Adicionado listener para o botão "Começar Agora"
        const ctaButtons = document.querySelectorAll('.cta-buton');

        if (showSignupLink) {
            // REMOVIDO: showSignupLink.addEventListener('click', (e) => {
            // REMOVIDO:     e.preventDefault();
            // REMOVIDO:     this.openSignupPopup();
            // REMOVIDO: });
        }

        if (showLoginLink) {
            showLoginLink.addEventListener('click', (e) => {
                e.preventDefault();
                this.openLoginPopup();
            });
        }

        ctaButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.openSignupPopup();
            });
        });

        // Event Listeners para fechar popups (copiado do index.php)
        const closeButtons = document.querySelectorAll('.close-popup');
        closeButtons.forEach(button => {
            button.addEventListener('click', () => {
                this.closePopups();
            });
        });

        // Fechar popup ao clicar fora (copiado do index.php)
        // Adicionado verificação para feedbackPopup
        window.addEventListener('click', (e) => {
            if (this.signupPopup && e.target === this.signupPopup) {
                this.closePopups();
            }
            if (this.loginPopup && e.target === this.loginPopup) {
                this.closePopups();
            }
                if (this.feedbackPopup && e.target === this.feedbackPopup && e.target === e.currentTarget) {
                    this.closePopups();
                }
        });

            // Listener específico para fechar o feedbackPopup pelo botão
            const feedbackCloseButton = document.querySelector('.feedback-close');
            if(feedbackCloseButton) {
                feedbackCloseButton.addEventListener('click', () => {
                    this.closePopups(); // Usa closePopups para garantir que todos fechem
                });
            }
    }
} 