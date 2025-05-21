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
      if (!res.ok) throw await res.json(
        {
          error: 'Erro ao chamar a API',
          status: res.status,
          message: res.statusText
        }
      );
      return res.json();
    }
}

export class backendService {
  /* TODO: Refatorar: Este URL ngrok é temporário. 
  Substituir pelo URL de produção do backend quando disponível. */
  static backendUrl = 'https://5851-2804-12a0-5005-c200-35f1-63e0-a86a-e924.ngrok-free.app';

  static async login(idToken) {
    const response = await fetch(this.backendUrl + '/auth/login/', {
      method: 'POST',
      // TODO: Refatorar: Este cabeçalho é específico para ngrok gratuito. Remover em produção.
      headers: { 'ngrok-skip-browser-warning': 'true' }, 
      body: JSON.stringify({ 'firebaseToken':idToken })
    });
    const data = await response.json();
    const authSucess = response.status;
    return [authSucess, data];
  }

  static async login_google(idToken) {
    const response = await fetch(this.backendUrl + '/auth/login_google/', {
      method: 'POST',
      // TODO: Refatorar: Este cabeçalho é específico para ngrok gratuito. Remover em produção.
      headers: { 'ngrok-skip-browser-warning': 'true' },
      body: JSON.stringify({ 'firebaseToken':idToken })
    });
    const data = await response.json();
    const authSucess = response.status;
    return [authSucess, data];
  }

  static async update_phone(phone, code, idToken) {
    const response = await fetch(this.backendUrl + '/auth/update-phone/', {
      method: 'POST',
      // TODO: Refatorar: Este cabeçalho é específico para ngrok gratuito. Remover em produção.
      headers: { 'ngrok-skip-browser-warning': 'true' },
      body: JSON.stringify({ 'phone':phone, 'code':code, 'firebaseToken':idToken })
    });
    const data = await response.json();
    const authSucess = response.status;
    return [authSucess, data];
  }

  static async verify_phone(phone, idToken) {
    const response = await fetch(this.backendUrl + '/auth/verify-phone/', {
      method: 'POST',
      headers: { 'ngrok-skip-browser-warning': 'true' },
      body: JSON.stringify({ 'phone':phone, 'firebaseToken':idToken })
    });
    const data = await response.json();
    const authSucess = response.status;
    return [authSucess, data];
  }

  static async register(idToken) {
    try {
      const response = await fetch(this.backendUrl + '/auth/register/', {
        method: 'POST',
        // TODO: Refatorar: Este cabeçalho é específico para ngrok gratuito. Remover em produção.
        headers: { 'ngrok-skip-browser-warning': 'true' }, 
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


