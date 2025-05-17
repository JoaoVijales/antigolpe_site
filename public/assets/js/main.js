// Ponto de entrada principal
import { AuthHandler } from './modules/auth.js';
import { ScrollAnimator } from './modules/animations.js';
import { Ui } from './modules/ui.js';

document.addEventListener('DOMContentLoaded', () => {
  AuthHandler.init();
  ScrollAnimator.init();
  Ui.initTooltips();

  document.querySelectorAll('[data-js="auth-form"]').forEach(form => {
    form.addEventListener('submit', handleAuth);
  });
  
});