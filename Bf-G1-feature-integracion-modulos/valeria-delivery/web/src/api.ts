export interface User { id: number | string; name: string; email: string; role?: string | { name: string }; roles?: Array<string | { name: string }>; phone?: string; }
export class ApiError extends Error {
  constructor(message: string, public status: number, public errors: Record<string, string[]> = {}) { super(message); }
}
// Relative URLs use the same-origin Laravel session. Bearer tokens can be supplied by the host login flow.
export async function request<T>(path: string, options: RequestInit = {}): Promise<T> {
  const token = sessionStorage.getItem('bruce_fire_token');
  if (!token && options.method && !['GET', 'HEAD'].includes(options.method.toUpperCase())) {
    const csrfResponse = await fetch('/sanctum/csrf-cookie', { credentials: 'include', headers: { Accept: 'application/json' } });
    if (!csrfResponse.ok) throw new ApiError('No se pudo iniciar una sesion segura con el servidor.', csrfResponse.status);
  }
  const csrf = document.cookie.split('; ').find(value => value.startsWith('XSRF-TOKEN='))?.slice(11);
  const response = await fetch(`/api/v1${path}`, { ...options, credentials: 'include', headers: {
    Accept: 'application/json', ...(options.body ? { 'Content-Type': 'application/json' } : {}),
    ...(token ? { Authorization: `Bearer ${token}` } : {}), ...(csrf ? { 'X-XSRF-TOKEN': decodeURIComponent(csrf) } : {}), ...options.headers,
  } });
  const body = await response.json().catch(() => null);
  if (body === null && response.status !== 204) {
    throw new ApiError('No se pudo conectar con el servicio de autenticacion. El perfil estara disponible cuando el servidor este conectado.', response.status);
  }
  if (!response.ok) throw new ApiError(response.status === 429 ? 'Demasiados intentos. Espera un minuto y vuelve a intentarlo.' : body?.message || (response.status === 401 ? 'Tu sesion ha expirado. Inicia sesion nuevamente.' : 'No se pudo completar la solicitud.'), response.status, body?.errors);
  return body as T;
}
