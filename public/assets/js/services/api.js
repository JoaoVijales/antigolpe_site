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
}

export class backendService {
  static backendUrl = 'https://5851-2804-12a0-5005-c200-35f1-63e0-a86a-e924.ngrok-free.app ';


  static async login(idToken) {
    const response = await fetch(this.backendUrl + '/auth/login/', {
      method: 'POST',
      body: JSON.stringify({ 'firebaseToken':idToken })
    });
    const data = await response.json();
    const authSucess = response.status;
    return [authSucess, data];
  }

  static async register(idToken) {
    try {
      const response = await fetch(this.backendUrl + '/auth/register/', {
        method: 'POST',
        body: JSON.stringify({ 'firebaseToken':idToken })
    });
      const data = await response.json();
      const authSucess = response.status;
      return [authSucess, data];
    } catch (error) {
      console.error('Erro ao registrar:', error);
      return [false, { error: 'Erro ao registrar' }];
    }
  }
}
