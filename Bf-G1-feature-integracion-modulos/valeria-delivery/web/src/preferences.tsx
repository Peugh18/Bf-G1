import { createContext, useContext, useEffect, useState, type ReactNode } from 'react';
import { useLocation } from 'react-router-dom';
import { request } from './api';
export type Preferences = { theme: 'light' | 'dark' | 'system'; density: 'comfortable' | 'compact' };
export const defaults: Preferences = { theme: 'system', density: 'comfortable' };
const Context = createContext<{ preferences: Preferences; apply: (value: Preferences) => void }>({ preferences: defaults, apply: () => {} });
export const usePreferences = () => useContext(Context);
export function PreferencesProvider({ children }: { children: ReactNode }) {
  const [preferences, apply] = useState<Preferences>(defaults);
  const { pathname } = useLocation();
  useEffect(() => {
    const controller = new AbortController();
    if (pathname === '/login') { apply(defaults); return; }
    request<{ preferences: Preferences }>('/auth/preferences', { signal: controller.signal }).then(result => apply(result.preferences)).catch(() => {});
    return () => controller.abort();
  }, [pathname]);
  useEffect(() => {
    const query = matchMedia('(prefers-color-scheme: dark)');
    const update = () => { document.documentElement.dataset.theme = preferences.theme === 'system' ? query.matches ? 'dark' : 'light' : preferences.theme; document.documentElement.dataset.density = preferences.density; };
    update(); query.addEventListener('change', update);
    return () => query.removeEventListener('change', update);
  }, [preferences]);
  return <Context.Provider value={{ preferences, apply }}>{children}</Context.Provider>;
}
