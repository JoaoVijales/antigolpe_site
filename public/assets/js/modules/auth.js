// Gerencia fluxo de autenticação
import { backendService } from '../services/api.js';

// TODO: Verificar a origem e a necessidade de SessionManager e Ui
// import { SessionManager } from './session.js'; // Exemplo

export class AuthHandler {
    // TODO: Mover esta configuração para um arquivo de configuração externo seguro (como o usado no dashboard.html)
    static firebaseConfig = {
      apiKey: "AIzaSyCirroxmPsvwMx83Qow8BMIxZm-RYj5OXw",
      authDomain: "antigolpe-9d5e3.firebaseapp.com",
      projectId: "antigolpe-9d5e3",
      storageBucket: "antigolpe-9d5e3.firebasestorage.app",
      messagingSenderId: "997074583987",
      appId: "1:997074583987:web:0cc3cdc764cebce7c98bc6",
      measurementId: "G-9CZ3CGJWQR"
    };

    static auth = null;
    static db = null; 
    static googleProvider = null;
    static currentUser = null; 
    static authStateObservers = []; 

    static init() {
      if (typeof firebase === 'undefined' || !firebase.apps.length) {
         firebase.initializeApp(AuthHandler.firebaseConfig);
      }

      this.auth = firebase.auth();
      this.googleProvider = new firebase.auth.GoogleAuthProvider();
      this.setupLoginForm();
      this.setupRegisterForm();
      this.setupGoogleAuth();
      this.setupLogout();

      // Centralizar a verificação do estado de autenticação
      this.auth.onAuthStateChanged(user => {
          this.currentUser = user;
          console.log('Estado de autenticação alterado (AuthHandler): ', user ? user.email : 'Nenhum usuário logado');
          // Notificar todos os observadores
          this.authStateObservers.forEach(observer => observer(user));
      });

       console.log('AuthHandler inicializado.');
    }

    // Método para adicionar observadores do estado de autenticação
    static onAuthStateChanged(observer) {
        this.authStateObservers.push(observer);
        // Chamar o observador imediatamente com o estado atual
        observer(this.currentUser);
    }

    // Método para obter o usuário logado
    static getCurrentUser() {
        return this.currentUser;
    }
    static getCurrentUserToken() {
        idToken = localStorage.getItem('idToken');
        return idToken;
    }

    static async verifyToken(idToken) {
        const idToken = this.getCurrentUserToken()
        const authSucess = await this.auth.verifyIdToken(idToken)
        return authSucess;
    }

    // Método para fazer logout
    static async logout() {
        try {
            await this.auth.signOut();
            console.log('Logout bem-sucedido.');
            // Opcional: Redirecionar ou limpar estado no frontend
            // window.location.href = '/index.html'; // Exemplo: redirecionar para a página inicial
        } catch (error) {
            console.error('Erro ao fazer logout:', error);
            alert('Erro ao fazer logout: ' + error.message);
        }
    }

    static setupLoginForm() {
      document.querySelector('.login-form')?.addEventListener('submit', async (e) => {
        e.preventDefault();
 
        const email = document.getElementById('loginEmail').value; 
        const password = document.getElementById('loginPassword').value;

        try {
          const userCredential = await this.auth.signInWithEmailAndPassword(email, password);
          const idToken = userCredential.user.getIdToken();

          // TODO: Remover em produção
          console.log('Login com Email/Senha bem-sucedido (Firebase frontend):', user);
          console.log('Firebase ID Token obtido no frontend (Email/Senha):', idToken);

          try {
               const [authSucess, data] = await backendService.login(idToken);
                if (authSucess == 200) {
                    localStorage.setItem('idToken', idToken);
                    alert('Login com Email/Senha processado com sucesso pelo backend de autenticação!');
                    // TODO: Implementar redirecionamento para uma página autenticada (ex: dashboard)
                    // Ex: window.location.href = '/dashboard';
                     // Opcional: Fechar o popup de login
                     // if (typeof PopupHandler !== 'undefined' && PopupHandler.closePopups) {
                     //     PopupHandler.closePopups();
                     // }
                } else {
                    // TODO: Remover em produção
                     console.error('Resposta do outro backend não contém token de autenticação.', data);

                     alert('Erro ao processar login: Token não recebido do servidor de autenticação.');
                     // TODO: Mostrar erro na UI do popup
                }

            } catch (backendFetchError) {
                 console.error('Erro na comunicação com o outro backend:', backendFetchError);
                 
                 alert('Ocorreu um erro de rede ao tentar comunicar com o servidor de autenticação.');
                 // TODO: Mostrar erro na UI do popup
            }

        } catch (firebaseError) {
          console.error('Erro no login com Email/Senha (Firebase):', firebaseError);
          const errorMessage = firebaseError.message || 'Erro ao fazer login';
          alert('Erro no login: ' + errorMessage);
          // TODO: Mostrar erro na UI do popup
        }
      });
    }

    static setupRegisterForm() {
        document.querySelector('.signup-form')?.addEventListener('submit', async (e) => {
            e.preventDefault();

            const email = document.getElementById('email').value; 
            const password = document.getElementById('password').value; 

            try {
                const userCredential = await this.auth.createUserWithEmailAndPassword(email, password);
                const idToken = userCredential.user.getIdToken();

                console.log('Registro com Email/Senha bem-sucedido (Firebase frontend):', user);
                 try {
                     const [authSucess, data] = await backendService.register(idToken);
                     if (authSucess) {
                        localStorage.setItem('idToken', idToken);

                        alert('Registro com Email/Senha processado com sucesso pelo backend de autenticação!');
                        // TODO: Implementar redirecionamento para uma página autenticada
                        const plan = localStorage.getItem('plan');
                        if (plan === 'basic') {
                          window.location.href = 'https://buy.stripe.com/6oU5kCdM5gsceHh0lz9MY00';
                        } else if (plan === 'pro') {
                          window.location.href = '/https://buy.stripe.com/6oU8wOazT4JufLl0lz9MY01';
                        }
                     } else {
                      console.error('Resposta do outro backend não contém token de autenticação.', data);
                      alert('Erro ao processar registro: Token não recebido do servidor de autenticação.');

                     }

                 } catch (backendFetchError) {
                      console.error('Erro na comunicação com o outro backend:', backendFetchError);
                      alert('Ocorreu um erro de rede ao tentar comunicar com o servidor de autenticação durante o registro.');
                      // TODO: Mostrar erro na UI do popup
                 }

            } catch (firebaseError) {
                console.error('Erro no registro com Email/Senha (Firebase):', firebaseError);
                const errorMessage = firebaseError.message || 'Erro ao registrar';
                alert('Erro no registro: ' + errorMessage);
                // TODO: Mostrar erro na UI do popup
            }
        });
    }

    static setupGoogleAuth() {
      document.querySelectorAll('.google-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
          try {
            // 1. Iniciar o fluxo de login com Google usando o SDK do Firebase (no frontend)
            const result = await this.auth.signInWithPopup(this.googleProvider);
            const idToken = result.user.getIdToken();
            // TODO: Remover em produção
            console.log('Login com Google bem-sucedido (Firebase frontend):', idToken);
            console.log('Firebase ID Token obtido no frontend:', idToken);

            try {
                const [authSucess, data] = await backendService.login(idToken);
                if (authSucess) {
                    localStorage.setItem('idToken', idToken);
                    
                    const plan = localStorage.getItem('plan');
                    if (plan === 'basic') {
                      window.location.href = 'https://buy.stripe.com/6oU5kCdM5gsceHh0lz9MY00';
                    } else if (plan === 'pro') {
                      window.location.href = '/https://buy.stripe.com/6oU8wOazT4JufLl0lz9MY01';
                    }
                } else {
                    // TODO: Remover em produção
                     console.error('Resposta do outro backend não contém token de autenticação.', data);
                     alert('Erro ao processar login: Token não recebido do servidor de autenticação.');
                     // TODO: Mostrar erro na UI do popup
                }
            } catch (backendFetchError) {
                console.error('Erro na comunicação com o outro backend:', backendFetchError);
                alert('Ocorreu um erro de rede ao tentar comunicar com o servidor de autenticação.');
                // TODO: Mostrar erro na UI do popup
            }

          } catch (firebaseError) {
             // Erros do Firebase (ex: popup fechado pelo usuário, erro na autenticação Google)
             console.error('Erro no login com Google (Firebase frontend):', firebaseError);
             const errorMessage = firebaseError.message || 'Erro ao fazer login com Google';
             alert('Erro no login com Google: ' + errorMessage);
             // TODO: Mostrar erro na UI do popup
          }
        });
      });
    }
    
    static setupLogout() {
        // Encontre o botão de logout. Assumindo que ele tem a classe 'logout-button'
        document.querySelectorAll('.logout-button').forEach(button => {
            button.addEventListener('click', async () => {
                await AuthHandler.logout(); // Chama o método logout centralizado
        });
      });
    }
    
    // O manipulador handleAuth no main.js parece redundante com os manipuladores de submit aqui.
    // Podemos precisar revisar main.js depois.
  }