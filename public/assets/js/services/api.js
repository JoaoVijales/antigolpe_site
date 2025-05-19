// Gerencia chamadas à API
export class ApiService {
    // Função genérica para chamadas fetch à API (copiada do index.php)
    static async apiFetch(path, method = 'GET', body = null, token = null) {
      const headers = { 'Content-Type': 'application/json' };
      if (token) headers['Authorization'] = `Bearer ${token}`;
      const res = await fetch(`/api/${path}`, {
        method,
        headers,
        body: body ? JSON.stringify(body) : null
      });
      // O script original lançava um erro com o JSON da resposta se não fosse ok
      if (!res.ok) throw await res.json();
      return res.json();
    }

    // Função de login (adaptada do index.php)
    static async login(email, password) {
      // Usando a nova apiFetch
      const { idToken } = await this.apiFetch('firebase/LoginUser.php', 'POST', { 
          email, 
          password 
        });
      // O script original armazenava em sessionStorage
      sessionStorage.setItem('idToken', idToken);
      return idToken;
    }

    // Função de registro (adaptada do index.php)
    static async register(email, password, name) {
      // Usando a nova apiFetch
      const { idToken } = await this.apiFetch('firebase/CreateUser.php', 'POST', { email, password, name });
      // O script original armazenava em sessionStorage
      sessionStorage.setItem('idToken', idToken);
      return idToken;
    }

    // Função de login com Google (adaptada do index.php)
    static async googleLogin() {
       // O script original fazia uma chamada POST simples
       // TODO: Verificar se essa abordagem é a final para o fluxo Google Auth
      const { idToken } = await this.apiFetch('firebase/google-login', 'POST');
      // O script original armazenava em sessionStorage
      sessionStorage.setItem('idToken', idToken);
      return idToken;
    }
}