import React from 'react';
import { createRoot } from 'react-dom/client';
import { BrowserRouter, Navigate, NavLink, Route, Routes, useLocation, useNavigate } from 'react-router-dom';
import { Flame, UserRound, LogOut, Settings } from 'lucide-react';
import ProfilePage from './pages/Profile/ProfilePage';
import HelpModal from './components/ui/HelpModal';
import './styles.css';
import './settings.css';
import SettingsPage from './pages/Settings/SettingsPage';
import { PreferencesProvider } from './preferences';
import AuthPage from './pages/Auth/AuthPage';
import { request } from './api';
function App() {
  const { pathname } = useLocation();
  const navigate = useNavigate();
  const [busy, setBusy] = React.useState(false);
  const [error, setError] = React.useState('');
  async function logout() {
    setBusy(true); setError('');
    try { await request('/auth/logout', { method: 'POST' }); sessionStorage.removeItem('bruce_fire_token'); navigate('/login'); }
    catch { setError('No se pudo cerrar la sesion. Intenta nuevamente.'); }
    finally { setBusy(false); }
  }
  return <><header className="app-header"><NavLink className="brand" to="/perfil"><Flame size={26}/>BRUCE FIRE<span>S.A.C.</span></NavLink><nav><NavLink to="/perfil"><UserRound size={18}/><span>Mi perfil</span></NavLink><NavLink to="/ajustes" title="Ajustes" aria-label="Ajustes"><Settings size={19}/></NavLink>{['/perfil', '/ajustes'].includes(pathname) && <button className="icon-button" title="Cerrar sesion" aria-label="Cerrar sesion" onClick={logout} disabled={busy}><LogOut size={19}/></button>}</nav></header>{error && <p role="alert" className="notice error">{error}</p>}<Routes><Route path="/" element={<Navigate to="/perfil" replace/>}/><Route path="/login" element={<AuthPage/>}/><Route path="/perfil" element={<ProfilePage/>}/><Route path="/ajustes" element={<SettingsPage/>}/><Route path="*" element={<main><h1>Pagina no encontrada</h1><NavLink to="/perfil">Volver a mi perfil</NavLink></main>}/></Routes><HelpModal/></>;
}
createRoot(document.getElementById('root')!).render(<React.StrictMode><BrowserRouter><PreferencesProvider><App/></PreferencesProvider></BrowserRouter></React.StrictMode>);
