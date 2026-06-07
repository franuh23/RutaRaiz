const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000';
// Centraliza el cliente HTTP de la aplicación.

export const apiFetch = (path, options = {}) => {
  return fetch(`${API_URL}${path}`, options);
};