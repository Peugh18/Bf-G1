import { useEffect, useState, type FormEvent } from 'react';
import { NavLink, useNavigate } from 'react-router-dom';
import { Save, RotateCcw, LockKeyhole, Moon, Sun, Monitor } from 'lucide-react';
import { ApiError, request } from '../../api';
import { defaults, usePreferences, type Preferences } from '../../preferences';
export default function SettingsPage() {
  const { apply } = usePreferences();
  const [value, setValue] = useState<Preferences>(defaults);
  const [saved, setSaved] = useState<Preferences>(defaults);
  const [loading, setLoading] = useState(true);
  const [loaded, setLoaded] = useState(false);
  const [busy, setBusy] = useState(false);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState(false);
  const [attempt, setAttempt] = useState(0);
  const navigate = useNavigate();
  useEffect(() => {
    const controller = new AbortController(); setLoading(true); setError('');
    request<{ preferences: Preferences }>('/auth/preferences', { signal: controller.signal }).then(result => { setValue(result.preferences); setSaved(result.preferences); setLoaded(true); })
      .catch(cause => { if (controller.signal.aborted) return; if (cause instanceof ApiError && cause.status === 401) navigate('/login', { replace: true }); else setError('No se pudieron cargar tus ajustes.'); })
      .finally(() => { if (!controller.signal.aborted) setLoading(false); });
    return () => controller.abort();
  }, [attempt, navigate]);
  const dirty = value.theme !== saved.theme || value.density !== saved.density;
  async function submit(event: FormEvent) {
    event.preventDefault(); if (busy) return;
    setBusy(true); setError(''); setSuccess(false);
    try { const result = await request<{ preferences: Preferences }>('/auth/preferences', { method: 'PUT', body: JSON.stringify(value) }); setSaved(result.preferences); apply(result.preferences); setSuccess(true); }
    catch (cause) { setError(cause instanceof ApiError ? cause.message : 'No se pudieron guardar tus ajustes.'); }
    finally { setBusy(false); }
  }
  return <main><div className="page-heading"><span className="eyebrow">MI CUENTA</span><h1>Ajustes</h1><p>Preferencias de tu cuenta.</p></div>{success && <p className="notice success" role="status">Tus ajustes se guardaron correctamente.</p>}{error && <div className="notice error" role="alert">{error}{!loaded && <button onClick={() => setAttempt(attempt + 1)}>Reintentar</button>}</div>}
    {loading ? <p role="status">Cargando ajustes...</p> : loaded && <form onSubmit={submit}><section className="account-section"><h2>Apariencia</h2><fieldset disabled={busy}><legend>Tema</legend><div className="setting-options">{([['light', 'Claro', Sun], ['dark', 'Oscuro', Moon], ['system', 'Sistema', Monitor]] as const).map(([theme, label, Icon]) => <label key={theme}><input type="radio" name="theme" value={theme} checked={value.theme === theme} onChange={() => { setValue({ ...value, theme }); setSuccess(false); }}/><Icon size={18}/>{label}</label>)}</div></fieldset><fieldset disabled={busy}><legend>Densidad</legend><div className="setting-options">{([['comfortable', 'Comoda'], ['compact', 'Compacta']] as const).map(([density, label]) => <label key={density}><input type="radio" name="density" value={density} checked={value.density === density} onChange={() => { setValue({ ...value, density }); setSuccess(false); }}/>{label}</label>)}</div></fieldset></section><div className="settings-actions"><button className="primary" disabled={busy || !dirty}><Save size={17}/>{busy ? 'Guardando...' : 'Guardar ajustes'}</button><button type="button" disabled={busy} onClick={() => { setValue(defaults); setSuccess(false); }}><RotateCcw size={17}/>Restablecer valores</button></div></form>}
    <section className="account-section"><h2>Seguridad de la cuenta</h2><NavLink className="settings-link" to="/perfil"><LockKeyhole size={17}/>Contrasena y perfil</NavLink></section>
  </main>;
}
