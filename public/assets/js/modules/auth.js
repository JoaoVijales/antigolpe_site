// Gerencia fluxo de autenticação
import { ApiService } from '../services/api.js';
// TODO: Verificar a origem e a necessidade de SessionManager e Ui
// import { SessionManager } from './session.js'; // Exemplo

// Importar Firebase (se ainda não estiver importado via script tags no HTML)
// Se você estiver usando script tags globais no HTML como no exemplo anterior, não precisa desta importação.
// Se estiver usando bundler (Webpack, Parcel, Vite) e quer importar, use:
// import firebase from 'firebase/app';
// import 'firebase/auth';
// Se precisar do Firestore:
// import 'firebase/firestore';

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
    static db = null; // Adicionar instância do Firestore, se necessário
    static googleProvider = null;
    static currentUser = null; // Para armazenar o usuário logado
    static authStateObservers = []; // Para observadores do estado de autenticação

    static init() {
      // Inicialize o Firebase se ainda não foi inicializado globalmente
      if (typeof firebase === 'undefined' || !firebase.apps.length) {
         firebase.initializeApp(AuthHandler.firebaseConfig);
      }

      this.auth = firebase.auth();
      // Inicialize o Firestore se necessário (apenas se este backend PHP interagir com ele)
      // this.db = firebase.firestore();

      this.googleProvider = new firebase.auth.GoogleAuthProvider();

      this.setupLoginForm();
      this.setupRegisterForm();
      this.setupGoogleAuth();
      this.setupLogout(); // Adicionar configuração para logout

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
      // Usando o seletor do script inline para o formulário de login
      document.querySelector('.login-form')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        // Usando os IDs de input do script inline
        const email = document.getElementById('loginEmail').value; // Ajustado para loginEmail ID do index.php
        const password = document.getElementById('loginPassword').value; // Ajustado para loginPassword ID do index.php
        
        // SUBSTITUA 'URL_DO_SEU_OUTRO_BACKEND/endpoint_de_login_unificado' pela URL REAL do seu outro backend
        const YOUR_OTHER_BACKEND_AUTH_URL = 'URL_DO_SEU_OUTRO_BACKEND/endpoint_de_login_unificado'; // <<< SUBSTITUA AQUI

        try {
          // 1. Autenticar com Email/Senha usando Firebase SDK (no frontend)
          const userCredential = await this.auth.signInWithEmailAndPassword(email, password);
          const user = userCredential.user;

          console.log('Login com Email/Senha bem-sucedido (Firebase frontend):', user);

          // 2. Obter o ID Token do Firebase
          const idToken = await user.getIdToken();
          console.log('Firebase ID Token obtido no frontend (Email/Senha):', idToken);

          // 3. Enviar o ID Token para o SEU OUTRO BACKEND (mesmo fluxo do Google)
          try {
               const backendResponse = await fetch(YOUR_OTHER_BACKEND_AUTH_URL, {
                   method: 'POST',
                   headers: {
                       'Content-Type': 'application/json',
                       // Se o seu OUTRO backend exigir algum cabeçalho adicional, adicione aqui:
                       // 'X-CSRF-TOKEN': seuCSRFTokenAqui
                   },
                   body: JSON.stringify({ idToken: idToken }), // Envia o ID Token do Firebase
                });

                if (!backendResponse.ok) {
                     const errorData = await backendResponse.json();
                     throw new Error(errorData.error || 'Erro desconhecido no outro backend');
                }

                const data = await backendResponse.json();
                console.log('Resposta do OUTRO backend (Email/Senha):', data);

                // 4. Lidar com a resposta do OUTRO backend (Obter token, dados do usuário, etc.)
                const authTokenFromOtherBackend = data.auth_token; // Exemplo: o outro backend retorna um campo 'auth_token'
                const userDataFromOtherBackend = data.user; // Exemplo: o outro backend retorna dados do usuário

                if (authTokenFromOtherBackend) {
                    console.log('Token de autenticação do outro backend obtido:', authTokenFromOtherBackend);
                    // TODO: Armazenar este authTokenFromOtherBackend (ex: localStorage, cookies seguros) para usar em requisições futuras
                    // localStorage.setItem('otherBackendAuthToken', authTokenFromOtherBackend);
                    // TODO: Armazenar dados básicos do usuário, se necessário
                    // localStorage.setItem('userData', JSON.stringify(userDataFromOtherBackend));

                    alert('Login com Email/Senha processado com sucesso pelo backend de autenticação!');
                    // TODO: Implementar redirecionamento para uma página autenticada (ex: dashboard)
                    // Ex: window.location.href = '/dashboard';
                     // Opcional: Fechar o popup de login
                     // if (typeof PopupHandler !== 'undefined' && PopupHandler.closePopups) {
                     //     PopupHandler.closePopups();
                     // }
                } else {
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
        // Adicionando a configuração para o formulário de registro do script inline
        // Usando o seletor do script inline para o formulário de cadastro
        document.querySelector('.signup-form')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            // Usando os IDs de input do script inline
            const email = document.getElementById('email').value; // TODO: Verificar se o ID é 'email' no formulário de cadastro
            const password = document.getElementById('password').value; // TODO: Verificar se o ID é 'password' no formulário de cadastro
            // const name = document.getElementById('name')?.value; // Removido, Firebase Auth cria usuário básico primeiro

            // SUBSTITUA 'URL_DO_SEU_OUTRO_BACKEND/endpoint_de_registro_unificado' pela URL REAL do seu outro backend
            // Nota: Muitos backends usam um único endpoint para receber o token após *qualquer* método de login.
            // Se o seu outro backend tiver um endpoint específico para registro, use-o aqui.
            // Se o seu outro backend espera o token após o registro, use a mesma URL do login.
            const YOUR_OTHER_BACKEND_REGISTER_URL = 'URL_DO_SEU_OUTRO_BACKEND/endpoint_de_registro_unificado'; // <<< SUBSTITUA AQUI

            try {
                // 1. Criar usuário com Email/Senha usando Firebase SDK (no frontend)
                const userCredential = await this.auth.createUserWithEmailAndPassword(email, password);
                const user = userCredential.user;

                console.log('Registro com Email/Senha bem-sucedido (Firebase frontend):', user);

                 // Opcional: Atualizar perfil (nome, etc.) após a criação
                 // if (name) {
                 //     await user.updateProfile({ displayName: name });
                 //     console.log('Perfil do usuário atualizado com nome.');
                 // }

                // 2. Obter o ID Token do Firebase
                const idToken = await user.getIdToken();
                console.log('Firebase ID Token obtido no frontend (Registro Email/Senha):', idToken);

                // 3. Enviar o ID Token para o SEU OUTRO BACKEND (mesmo fluxo do Google/Login Email/Senha)
                 try {
                     const backendResponse = await fetch(YOUR_OTHER_BACKEND_REGISTER_URL, { // Pode ser a mesma URL do login
                         method: 'POST',
                         headers: {
                             'Content-Type': 'application/json',
                             // Se o seu OUTRO backend exigir algum cabeçalho adicional, adicione aqui:
                             // 'X-CSRF-TOKEN': seuCSRFTokenAqui
                         },
                         body: JSON.stringify({ idToken: idToken, email: email, password: password }), // Envia o ID Token (e opcionalmente email/senha se o backend precisar para lookup ou criação inicial)
                      });

                      if (!backendResponse.ok) {
                          const errorData = await backendResponse.json();
                          throw new Error(errorData.error || 'Erro desconhecido no outro backend');
                      }

                      const data = await backendResponse.json();
                      console.log('Resposta do OUTRO backend (Registro Email/Senha):', data);

                     // 4. Lidar com a resposta do OUTRO backend (Obter token, dados do usuário, etc.)
                     const authTokenFromOtherBackend = data.auth_token; // Exemplo
                     const userDataFromOtherBackend = data.user; // Exemplo

                     if (authTokenFromOtherBackend) {
                         console.log('Token de autenticação do outro backend obtido:', authTokenFromOtherBackend);
                         // TODO: Armazenar este authTokenFromOtherBackend
                         // TODO: Armazenar dados básicos do usuário

                         alert('Registro com Email/Senha processado com sucesso pelo backend de autenticação!');
                         // TODO: Implementar redirecionamento para uma página autenticada
                          // Ex: window.location.href = '/dashboard';
                          // Opcional: Fechar o popup de registro
                          // if (typeof PopupHandler !== 'undefined' && PopupHandler.closePopups) {\n                          //     PopupHandler.closePopups();\n                          // }\n                     } else {
                          console.error('Resposta do outro backend não contém token de autenticação.', data);
                          alert('Erro ao processar registro: Token não recebido do servidor de autenticação.');
                          // TODO: Mostrar erro na UI do popup
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
            const user = result.user;
            console.log('Login com Google bem-sucedido (Firebase frontend):', user);

            // 2. Obter o ID Token do Firebase
            const idToken = await user.getIdToken();
            console.log('Firebase ID Token obtido no frontend:', idToken);

            // 3. Enviar o ID Token para o SEU OUTRO BACKEND para verificação e criação de sessão lá
            // SUBSTITUA 'URL_DO_SEU_OUTRO_BACKEND/endpoint_de_login_google' pela URL REAL
            // Use a mesma URL unificada se o seu outro backend tiver um endpoint para receber tokens de qualquer origem
            const YOUR_OTHER_BACKEND_AUTH_URL = 'URL_DO_SEU_OUTRO_BACKEND/endpoint_de_login_unificado'; // <<< SUBSTITUA AQUI

            try {
                const backendResponse = await fetch(YOUR_OTHER_BACKEND_AUTH_URL, {
                   method: 'POST',
                   headers: {
                       'Content-Type': 'application/json',
                       // Se o seu OUTRO backend exigir algum cabeçalho adicional, adicione aqui:
                       // 'X-CSRF-TOKEN': seuCSRFTokenAqui
                   },
                   body: JSON.stringify({ idToken: idToken }), // Envia o ID Token do Firebase
                });

                if (!backendResponse.ok) {
                     // Lidar com erros retornados pelo OUTRO backend
                     const errorData = await backendResponse.json();
                     const errorMessage = errorData.error || 'Erro desconhecido no outro backend';
                     console.error('Erro no outro backend:', errorData);
                     alert('Erro ao processar login no servidor: ' + errorMessage);
                     // TODO: Mostrar erro na UI do popup
                     return; // Parar o fluxo em caso de erro no backend
                }
                const data = await backendResponse.json();
                console.log('Resposta do OUTRO backend:', data);

                // 4. Lidar com a resposta do OUTRO backend
                // O OUTRO backend deve retornar um token de autenticação para ser usado NESTE frontend
                // para requisições subsequentes ao SEU OUTRO BACKEND (ou talvez a este backend PHP, dependendo da arquitetura)
                const authTokenFromOtherBackend = data.auth_token; // Exemplo: o outro backend retorna um campo 'auth_token'
                const userDataFromOtherBackend = data.user; // Exemplo: o outro backend retorna dados do usuário

                if (authTokenFromOtherBackend) {
                    console.log('Token de autenticação do outro backend obtido:', authTokenFromOtherBackend);
                    // TODO: Armazenar este authTokenFromOtherBackend (ex: localStorage, cookies seguros) para usar em requisições futuras ao OUTRO backend
                    // localStorage.setItem('otherBackendAuthToken', authTokenFromOtherBackend);
                    // TODO: Armazenar dados básicos do usuário, se necessário
                    // localStorage.setItem('userData', JSON.stringify(userDataFromOtherBackend));
                    alert('Login processado com sucesso pelo backend de autenticação!');
                    // TODO: Implementar redirecionamento para uma página autenticada (ex: dashboard)
                    // Certifique-se de que a página de destino use o authTokenFromOtherBackend para requisições
                    // Ex: window.location.href = '/dashboard';
                     // Opcional: Fechar o popup de login
                     // if (typeof PopupHandler !== 'undefined' && PopupHandler.closePopups) {
                     //     PopupHandler.closePopups();
                     // }
                } else {
                     console.error('Resposta do outro backend não contém token de autenticação.', data);
                     alert('Erro ao processar login: Token não recebido do servidor de autenticação.');
                     // TODO: Mostrar erro na UI do popup
                }
            } catch (backendFetchError) {
                // Erro na comunicação HTTP com o OUTRO backend
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