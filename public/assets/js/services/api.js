// Gerencia chamadas à API
export class ApiService {
    static async login(email, password) {
      const response = await fetch('/api/auth/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password })
      });
      return response.json();
    }
  
    static async googleAuth() {
      // Implementação do fluxo OAuth
    }
  }