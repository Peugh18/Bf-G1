import { useState, type FormEvent } from 'react';
import { Eye, EyeOff, LockKeyhole } from 'lucide-react';
import { ApiError, request } from '../../api';
import { Modal } from '../../components/ui/Modal';
export default function ChangePasswordModal({ onClose, onSuccess }: { onClose: () => void; onSuccess: () => void }) {
  const [values, setValues] = useState({ current_password: '', password: '', password_confirmation: '' });
  const [visible, setVisible] = useState(false);
  const [busy, setBusy] = useState(false);
  const [error, setError] = useState('');
  const [errors, setErrors] = useState<Record<string, string[]>>({});
  async function submit(event: FormEvent) {
    event.preventDefault();
    if (busy) return;
    setError(''); setErrors({});
    if (values.password !== values.password_confirmation) { setErrors({ password_confirmation: ['Las contrasenas no coinciden.'] }); return; }
    if (values.current_password === values.password) { setErrors({ password: ['Elige una contrasena diferente a la actual.'] }); return; }
    setBusy(true);
    try { await request('/auth/change-password', { method: 'POST', body: JSON.stringify({ current_password: values.current_password, new_password: values.password, confirm_password: values.password_confirmation }) }); onSuccess(); }
    catch (cause) { setError(cause instanceof ApiError ? cause.message : 'No hay conexion con el servidor. Intenta nuevamente.'); if (cause instanceof ApiError) setErrors({ ...cause.errors, password: cause.errors.new_password || cause.errors.password, password_confirmation: cause.errors.confirm_password || cause.errors.password_confirmation }); }
    finally { setBusy(false); }
  }
  return <Modal title="Cambiar contrasena" onClose={onClose} busy={busy}><form onSubmit={submit}>
    {error && <p className="notice error" role="alert">{error}</p>}
    {(['current_password', 'password', 'password_confirmation'] as const).map((key, index) => <label className="field" key={key} htmlFor={key}>
      {['Contrasena actual', 'Nueva contrasena', 'Confirmar nueva contrasena'][index]}
      <input id={key} type={visible ? 'text' : 'password'} value={values[key]} required minLength={index ? 8 : undefined} autoComplete={index ? 'new-password' : 'current-password'} disabled={busy} aria-invalid={!!errors[key]} aria-describedby={errors[key] ? `${key}-error` : undefined} onChange={event => setValues({ ...values, [key]: event.target.value })}/>
      {errors[key] && <span id={`${key}-error`} className="field-error">{errors[key].join(' ')}</span>}
    </label>)}
    <button type="button" className="text-button" onClick={() => setVisible(!visible)}>{visible ? <EyeOff size={16}/> : <Eye size={16}/>} {visible ? 'Ocultar contrasenas' : 'Mostrar contrasenas'}</button>
    <footer className="modal-actions"><button type="button" onClick={onClose} disabled={busy}>Cancelar</button><button className="primary" disabled={busy}><LockKeyhole size={16}/>{busy ? 'Guardando...' : 'Guardar contrasena'}</button></footer>
  </form></Modal>;
}
