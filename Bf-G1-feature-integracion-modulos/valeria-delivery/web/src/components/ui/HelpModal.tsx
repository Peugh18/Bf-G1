import { useState } from 'react';
import { CircleHelp, ChevronLeft, ChevronRight } from 'lucide-react';
import { useLocation } from 'react-router-dom';
import { Modal } from './Modal';
const guides: Record<string, { title: string; steps: string[] }> = {
  perfil: { title: 'Mi perfil', steps: ['Consulta tu nombre, correo y rol asignado en los datos de tu cuenta.', 'Selecciona Cambiar contrasena e ingresa tu contrasena actual.', 'Escribe una nueva contrasena de al menos 8 caracteres y confirmala.', 'Selecciona Guardar contrasena y espera el mensaje de confirmacion.'] },
  clientes: { title: 'Clientes', steps: ['Busca al cliente por nombre o documento.', 'Abre su registro para consultar sus datos.', 'Completa los campos requeridos al crear o editar un cliente.', 'Guarda los cambios y comprueba el registro actualizado.'] },
  catalogo: { title: 'Catalogo', steps: ['Busca un producto por nombre o codigo.', 'Consulta su precio y disponibilidad.', 'Abre el producto para revisar sus detalles.'] },
  ventas: { title: 'Ventas', steps: ['Selecciona el cliente de la venta.', 'Agrega los productos y revisa las cantidades.', 'Comprueba el total y confirma la venta.'] },
  ajustes: { title: 'Ajustes', steps: ['Revisa los ajustes disponibles para tu rol.', 'Modifica el valor que deseas actualizar.', 'Guarda los cambios y comprueba la confirmacion.'] },
};
export default function HelpModal({ activeModule }: { activeModule?: string }) {
  const { pathname } = useLocation();
  const module = activeModule || pathname.split('/').filter(Boolean)[0] || 'perfil';
  const guide = guides[module] || { title: 'Guia rapida', steps: ['Selecciona el modulo que deseas consultar en la navegacion.', 'Revisa los datos antes de confirmar cualquier cambio.', 'Contacta al administrador si necesitas permisos adicionales.'] };
  const [open, setOpen] = useState(false);
  const [step, setStep] = useState(0);
  const index = Math.min(step, guide.steps.length - 1);
  return <><button className="help-button" title="Ayuda del modulo" aria-label="Abrir guia de ayuda" onClick={() => { setStep(0); setOpen(true); }}><CircleHelp size={24}/></button>
    {open && <Modal title={`Ayuda: ${guide.title}`} onClose={() => setOpen(false)}><div className="help-content"><p className="eyebrow">PASO {index + 1} DE {guide.steps.length}</p><p aria-live="polite">{guide.steps[index]}</p><progress value={index + 1} max={guide.steps.length}/></div><footer className="modal-actions"><button disabled={index === 0} onClick={() => setStep(index - 1)}><ChevronLeft size={16}/>Anterior</button>{index === guide.steps.length - 1 ? <button className="primary" onClick={() => setOpen(false)}>Finalizar</button> : <button className="primary" onClick={() => setStep(index + 1)}>Siguiente<ChevronRight size={16}/></button>}</footer></Modal>}
  </>;
}
