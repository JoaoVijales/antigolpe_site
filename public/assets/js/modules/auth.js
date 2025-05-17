// Gerencia fluxo de autenticação
export class AuthHandler {
    static init() {
      this.setupLoginForm();
      this.setupRegisterForm();
      this.setupGoogleAuth();
    }
  
    static setupLoginForm() {
      document.querySelector('.login-form')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const email = document.getElementById('loginEmail').value;
        const password = document.getElementById('loginPassword').value;
        
        try {
          const { token } = await ApiService.login(email, password);
          SessionManager.setToken(token);
          window.location.href = '/dashboard';
        } catch (error) {
          Ui.showToast('Credenciais inválidas', 'error');
        }
      });
    }
  
    static setupGoogleAuth() {
      document.querySelectorAll('.google-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          window.location.href = '/api/auth/google';
        });
      });
    }
  }