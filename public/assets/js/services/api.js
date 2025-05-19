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