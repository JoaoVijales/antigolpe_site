// Gerencia fluxo de autenticação
import { ApiService } from '../services/api.js';
// TODO: Verificar a origem e a necessidade de SessionManager e Ui
// import { SessionManager } from './session.js'; // Exemplo
// import { Ui } from './ui.js'; // Exemplo

export class AuthHandler {
    static init() {
      this.setupLoginForm();
      this.setupRegisterForm();
      this.setupGoogleAuth();
    }

    static setupLoginForm() {
      // Usando o seletor do script inline para o formulário de login
      document.querySelector('.login-form')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        // Usando os IDs de input do script inline
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        
        try {
          // Usando a nova função login da ApiService
          const idToken = await ApiService.login(email, password);
          // Mantendo a lógica original de SessionManager e redirecionamento
          // TODO: Confirmar se SessionManager é necessário/existe
          // SessionManager.setToken(idToken);
          // window.location.href = '/dashboard'; // TODO: Confirmar o redirecionamento pós-login
           console.log('Login bem-sucedido! ID Token:', idToken); // Log provisório
           alert('Login bem-sucedido!'); // Alerta provisório
           // TODO: Implementar redirecionamento ou outra ação pós-login

        } catch (error) {
          console.error('Erro no login:', error);
          // Mantendo a lógica original de showToast
          // TODO: Confirmar se Ui.showToast é necessário/existe
          // Ui.showToast('Credenciais inválidas', 'error');
           alert('Erro no login: ' + (error.message || 'Credenciais inválidas')); // Alerta provisório
        }
      });
    }

    static setupRegisterForm() {
        // Adicionando a configuração para o formulário de registro do script inline
        // Usando o seletor do script inline para o formulário de cadastro
        document.querySelector('.signup-form')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            // Usando os IDs de input do script inline (assumindo que existem inputs com estes IDs no formulário de cadastro)
            const email = document.getElementById('email').value; // TODO: Verificar se o ID é 'email' no formulário de cadastro
            const password = document.getElementById('password').value; // TODO: Verificar se o ID é 'password' no formulário de cadastro
            const name = document.getElementById('name')?.value; // TODO: Adicionar input de nome ao formulário de cadastro e verificar o ID

            try {
                // Usando a nova função register da ApiService
                const idToken = await ApiService.register(email, password, name);
                 console.log('Registro bem-sucedido! ID Token:', idToken); // Log provisório
                 alert('Registro bem-sucedido!'); // Alerta provisório
                // TODO: Implementar redirecionamento ou outra ação pós-registro

            } catch (error) {
                console.error('Erro no registro:', error);
                 alert('Erro no registro: ' + (error.message || 'Erro ao registrar')); // Alerta provisório
            }
        });
    }

    static setupGoogleAuth() {
      document.querySelectorAll('.google-btn').forEach(btn => {
        btn.addEventListener('click', async () => { // Mudança para async
          // O script inline chamava googleLogin na ApiService
          try {
            const idToken = await ApiService.googleLogin();
            console.log('Login com Google bem-sucedido! ID Token:', idToken); // Log provisório
            alert('Login com Google bem-sucedido!'); // Alerta provisório
            // TODO: Implementar redirecionamento ou outra ação pós-login com Google
          } catch (error) {
             console.error('Erro no login com Google:', error);
             alert('Erro no login com Google: ' + (error.message || 'Erro ao fazer login com Google')); // Alerta provisório
          }
           // TODO: Remover o redirecionamento direto se a API lidar com o fluxo OAuth
           // window.location.href = '/api/auth/google';
        });
      });
    }
    
    // O manipulador handleAuth no main.js parece redundante com os manipuladores de submit aqui.
    // Podemos precisar revisar main.js depois.
  }