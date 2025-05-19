// Gerencia a manipulação do formulário de contato
import { PopupHandler } from './popup.js';

export class ContactFormHandler {
    static init() {
        this.setupContactFormSubmit();
    }

    // Lógica para manipular o submit do formulário de contato (copiada e adaptada do index.php)
    static setupContactFormSubmit() {
        const contactForm = document.querySelector('.form-contact');
        if (contactForm) {
            contactForm.addEventListener('submit', (e) => {
                e.preventDefault();

                const emailInput = contactForm.querySelector('.input-email');
                const email = emailInput ? emailInput.value : '';

                // Aqui você pode adicionar a lógica de envio do email, por exemplo, uma chamada à API
                console.log('Email de contato para envio:', email); // Log provisório

                // Mostra o popup de feedback usando o PopupHandler
                PopupHandler.feedbackPopup?.classList.add('active'); // Acessa diretamente feedbackPopup da instância em PopupHandler
                // Alternativa: Criar um método openFeedbackPopup() em PopupHandler e chamá-lo aqui.

                // Limpa o formulário
                if (emailInput) {
                    emailInput.value = '';
                }

                // TODO: Implementar a lógica real de envio de email (AJAX, fetch, etc.)
            });
        }
    }
} 