import { useState, type FormEvent } from 'react';
import { useNavigate } from 'react-router-dom';
import { Eye, EyeOff, LogIn, UserPlus } from 'lucide-react';
import { ApiError, request } from '../../api';

export default function AuthPage() {
  const [register, setRegister] = useState(false);
  const [busy, setBusy] = useState(false);
  const [visible, setVisible] = useState(false);
  const [error, setError] = useState('');
  const [errors, setErrors] = useState<Record<string, string[]>>({});
  const [values, setValues] = useState({ name: '', email: '', phone: '', password: '', password_confirmation: '' });
  const navigate = useNavigate();
  async function submit(event: FormEvent) {
    event.preventDefault(); if (busy) return;
    setError(''); setErrors({});
    if (register && values.password !== values.password_confirmation) { setErrors({ password_confirmation: ['Las contrasenas no coinciden.'] }); return; }
    setBusy(true);
    try {
      sessionStorage.removeItem('bruce_fire_token');
      await request(register ? '/auth/register' : '/auth/login', { method: 'POST', body: JSON.stringify(register ? values : { email: values.email, password: values.password }) });
      navigate('/perfil', { replace: true });
    } catch (cause) {
      setError(cause instanceof ApiError ? cause.message : 'No se pudo conectar con el servidor.');
      if (cause instanceof ApiError) setErrors(cause.errors);
    } finally { setBusy(false); }
  }
  const fields = register ? ['name', 'email', 'phone', 'password', 'password_confirmation'] as const : ['email', 'password'] as const;
  const labels = { name: 'Nombre completo', email: 'Correo electronico', phone: 'Telefono (opcional)', password: 'Contrasena', password_confirmation: 'Confirmar contrasena' };
  return <main className="auth-page"><span className="eyebrow">BRUCE FIRE S.A.C.</span><h1>{register ? 'Crear cuenta' : 'Iniciar sesion'}</h1>
    <form onSubmit={submit}>
      {error && <p className="notice error" role="alert">{error}</p>}
      {fields.map(key => <label className="field" key={key} htmlFor={`auth-${key}`}>{labels[key]}
        <input id={`auth-${key}`} name={key} type={key.includes('password') ? visible ? 'text' : 'password' : key === 'email' ? 'email' : key === 'phone' ? 'tel' : 'text'} required={key !== 'phone'} autoComplete={key.includes('password') ? register ? 'new-password' : 'current-password' : key === 'name' ? 'name' : key === 'phone' ? 'tel' : 'email'} minLength={register && key.includes('password') ? 8 : undefined} maxLength={key.includes('password') ? 72 : key === 'name' ? 150 : key === 'phone' ? 30 : 255} value={values[key]} disabled={busy} aria-invalid={!!errors[key]} aria-describedby={errors[key] ? `auth-${key}-error` : undefined} onChange={event => setValues({ ...values, [key]: event.target.value })}/>
        {errors[key] && <span className="field-error" id={`auth-${key}-error`}>{errors[key].join(' ')}</span>}
      </label>)}
      <button className="text-button" type="button" onClick={() => setVisible(!visible)}>{visible ? <EyeOff size={16}/> : <Eye size={16}/>} {visible ? 'Ocultar contrasena' : 'Mostrar contrasena'}</button>
      <div className="auth-actions"><button className="primary" disabled={busy}>{register ? <UserPlus size={18}/> : <LogIn size={18}/>} {busy ? 'Procesando...' : register ? 'Crear cuenta' : 'Ingresar'}</button><button type="button" disabled={busy} onClick={() => { setRegister(!register); setError(''); setErrors({}); setValues({ ...values, password: '', password_confirmation: '' }); }}>{register ? 'Ya tengo cuenta' : 'Crear cuenta'}</button></div>
    </form>
  </main>;
}
