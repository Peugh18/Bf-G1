# BRUCE FIRE React

`npm ci` y `npm run dev -- --port 5174 --strictPort`. Abrir `/login` y crear una cuenta para acceder a `/perfil`. El backend debe ejecutarse en el puerto 8000; ver `docs/INTEGRACION.md`.

El registro usa `name`, `email`, `phone` opcional, `password`, `password_confirmation`. El login usa `email`, `password`. Las solicitudes mutantes obtienen la cookie CSRF de Sanctum y envian `X-XSRF-TOKEN`; la cookie de sesion HttpOnly identifica al usuario sin guardar contrasenas en el navegador.

`GET /api/v1/auth/me` retorna `{ user }` con `id`, `name`, `email`, `phone`, `role`. `POST /api/v1/auth/change-password` recibe `current_password`, `new_password`, `confirm_password`. Los errores usan `{ message, errors }`.

El proxy Vite dirige `/api` y `/sanctum` a `http://127.0.0.1:8000`. Se puede configurar `API_PROXY_TARGET` en `.env.local` y reiniciar Vite. En despliegue servir API y frontend desde el mismo origen con fallback SPA para `/perfil` y `/login`.

`HelpModal` lee el modulo activo de la ruta o acepta `activeModule` (`perfil`, `clientes`, `catalogo`, `ventas`, `ajustes`). Montar dentro del router. Build: `npm run build`. Pruebas escritorio/movil con Edge: `node node_modules/@playwright/test/cli.js test`.
