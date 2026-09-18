# BRUCE FIRE - Ejecucion local

El backend Laravel incluye registro, inicio/cierre de sesion, perfil y cambio de contrasena. La autenticacion del frontend usa cookies HttpOnly y proteccion CSRF de Sanctum. Las nuevas cuentas reciben el rol Vendedor; el registro no permite asignarse roles elevados.

## Backend

Desde `api/` ejecutar `composer install`, configurar `.env` con `.env.example`, ejecutar `php artisan key:generate` y `php artisan migrate`. SQLite usa `api/database/database.sqlite`, que debe existir como archivo vacio antes de la primera migracion. No borrar este archivo: contiene los datos persistentes del equipo.

Desde la raiz, iniciar con `powershell -ExecutionPolicy Bypass -File scripts/start-api.ps1`. Este comando escucha en `http://127.0.0.1:8000` y evita el problema de `php artisan serve` con esta ruta de Windows.

Para PostgreSQL configurar `DB_CONNECTION=pgsql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` y ejecutar las migraciones contra una base existente. La ejecucion actual usa SQLite local; no es una base alojada externa.

## Frontend

Desde `web/` ejecutar `npm ci` y `npm run dev -- --port 5174 --strictPort`. Abrir `http://127.0.0.1:5174/login`, seleccionar Crear cuenta y registrar los datos propios. No hay credenciales predeterminadas.

Vite conecta `/api` y `/sanctum` con el backend de puerto 8000. La sesion dura 120 minutos y sobrevive a recargas. Configurar `API_PROXY_TARGET` en `web/.env.local` si cambia el origen del backend. Actualizar `SANCTUM_STATEFUL_DOMAINS` si cambia el puerto del frontend.

## Ajustes

La ruta `/ajustes` guarda tema (`light`, `dark`, `system`) y densidad (`comfortable`, `compact`) por usuario. `GET` y `PUT /api/v1/auth/preferences` requieren autenticacion. El tema se aplica despues de guardar y se conserva entre sesiones. El boton flotante muestra una guia especifica de Ajustes.

## Reportes y pruebas

Las migraciones incluyen `customers` (`id`, `name`, `document_number`, `email`, `phone`) y `products` (`id`, `sku`, `name`, `price`, `stock`). Los endpoints autenticados `/api/v1/customers/export/pdf`, `/api/v1/catalog/export/pdf` y sus variantes `/excel` descargan PDF/XLSX; `q` filtra nombre o documento/codigo. No hay registros de negocio de ejemplo ni pantallas CRUD en este alcance. Los reportes cargan los registros coincidentes en memoria; usar conjuntos acotados.

Pruebas backend: desde `api/`, `php vendor/phpunit/phpunit/phpunit`. Pruebas de renderizado: desde la raiz, `php api/tests/report-smoke.php`. Pruebas de navegador con Edge: desde `web/`, `node node_modules/@playwright/test/cli.js test`, con ambos servidores activos. Las pruebas de navegador crean cuentas identificadas como pruebas; PHPUnit usa una base en memoria.

Para despliegue configurar HTTPS, `APP_DEBUG=false`, `SESSION_SECURE_COOKIE=true`, el dominio de sesion y el origen de Sanctum. Mantener `.env` y la base SQLite fuera de Git.
