import { useEffect, useState } from 'react';
import { LockKeyhole, RefreshCw, ShieldCheck, UserRound } from 'lucide-react';
import { ApiError, request, type User } from '../../api';
import ChangePasswordModal from './ChangePasswordModal';
import { Navigate } from 'react-router-dom';
export default function ProfilePage() {
  const [user, setUser] = useState<User | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [unauthorized, setUnauthorized] = useState(false);
  const [modal, setModal] = useState(false);
  const [success, setSuccess] = useState(false);
  const [attempt, setAttempt] = useState(0);
  useEffect(() => {
    const controller = new AbortController(); setLoading(true); setError(''); setUnauthorized(false);
    request<User | { user: User } | { data: User }>('/auth/me', { signal: controller.signal })
      .then(result => { const profile = 'user' in result ? result.user : 'data' in result ? result.data : result; if (!profile?.name || !profile?.email) throw new Error('invalid profile'); setUser(profile); })
      .catch(cause => { if (controller.signal.aborted) return; setUser(null); setUnauthorized(cause instanceof ApiError && cause.status === 401); setError(cause instanceof ApiError ? cause.message : 'No se pudo cargar tu perfil. Comprueba la conexion con el servidor.'); })
      .finally(() => { if (!controller.signal.aborted) setLoading(false); });
    return () => controller.abort();
  }, [attempt]);
  const roles = user?.roles || (user?.role ? [user.role] : []);
  if (!loading && unauthorized) return <Navigate to="/login" replace/>;
  return <main><div className="page-heading"><span className="eyebrow">MI CUENTA</span><h1>Perfil de usuario</h1><p>Informacion personal y seguridad de tu cuenta.</p></div>
    {success && <p className="notice success" role="status">Tu contrasena se actualizo correctamente.</p>}
    {loading ? <p role="status" className="notice">Cargando perfil...</p> : error ? <section className="notice error" role="alert"><p>{error}</p>{unauthorized ? <a href="/login">Iniciar sesion</a> : <button onClick={() => setAttempt(attempt + 1)}><RefreshCw size={16}/>Reintentar</button>}</section> : user && <>
      <section className="profile-summary"><div className="avatar"><UserRound size={30}/></div><div><h2>{user.name}</h2><p>{user.email}</p></div><span className="role"><ShieldCheck size={16}/>{roles.map(role => typeof role === 'string' ? role : role.name).join(', ') || 'Sin rol asignado'}</span></section>
      <section className="account-section"><h2>Datos personales</h2><dl><div><dt>Nombre completo</dt><dd>{user.name}</dd></div><div><dt>Correo electronico</dt><dd>{user.email}</dd></div><div><dt>Telefono</dt><dd>{user.phone || 'No registrado'}</dd></div><div><dt>Rol asignado</dt><dd>{roles.map(role => typeof role === 'string' ? role : role.name).join(', ') || 'Sin rol asignado'}</dd></div></dl></section>
      <section className="account-section security"><div><h2>Seguridad</h2><p>Contrasena de acceso</p></div><button onClick={() => { setSuccess(false); setModal(true); }}><LockKeyhole size={17}/>Cambiar contrasena</button></section>
    </>}
    {modal && <ChangePasswordModal onClose={() => setModal(false)} onSuccess={() => { setModal(false); setSuccess(true); }}/ >}
  </main>;
}
