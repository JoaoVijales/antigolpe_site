// Ponto de entrada principal
import { AuthHandler } from './modules/auth.js';
import { ScrollAnimator } from './modules/animations.js';
import { NavigationHandler } from './modules/navigation.js';
import { FaqHandler } from './modules/faq.js';
import { PopupHandler } from './modules/popup.js';
import { ContactFormHandler } from './modules/contact.js';

document.addEventListener('DOMContentLoaded', () => {
  AuthHandler.init();
  ScrollAnimator.init();
  NavigationHandler.init(); // Inicializa o handler de navegação
  FaqHandler.init(); // Inicializa o handler do FAQ
  PopupHandler.init(); // Inicializa o handler de popups
  ContactFormHandler.init(); // Inicializa o handler do formulário de contato

  // O manipulador de formulário de autenticação genérico no main.js parece redundante
  // com os manipuladores específicos dentro de AuthHandler.init().
  // Vou remover esta parte.
  // document.querySelectorAll('[data-js="auth-form"]').forEach(form => {
  //   form.addEventListener('submit', handleAuth);
  // });

  // TODO: O handleAuth ainda existe em algum lugar? Se não, remover.
  // TODO: Verificar se há outras lógicas no script inline que precisam ser movidas/refatoradas.
  
});