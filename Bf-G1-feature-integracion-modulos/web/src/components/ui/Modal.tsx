import { useEffect, useRef, type ReactNode } from 'react';
import { X } from 'lucide-react';
export function Modal({ title, children, onClose, busy = false }: { title: string; children: ReactNode; onClose: () => void; busy?: boolean }) {
  const ref = useRef<HTMLDialogElement>(null);
  useEffect(() => { const dialog = ref.current!; dialog.showModal(); return () => dialog.close(); }, []);
  return <dialog ref={ref} onCancel={event => { event.preventDefault(); if (!busy) onClose(); }} onClick={event => { if (event.target === event.currentTarget && !busy) onClose(); }} aria-labelledby="modal-title">
    <header className="modal-header"><h2 id="modal-title">{title}</h2><button className="icon-button" aria-label="Cerrar" title="Cerrar" onClick={onClose} disabled={busy}><X size={20}/></button></header>{children}
  </dialog>;
}
