# Plan de Trabajo — Deivi Jair Ferrer Pajilla (DevOps / Integrador)

**Proyecto:** Sistema web para la gestión de ventas en BRUCE FIRE S.A.C.  
**Rol:** DevOps / Integrador  
**Rama asignada:** `feature/deivi-devops`  
**Objetivo General:** Implementar endpoints de Healthcheck, automatización de CI y scripts de respaldo para la infraestructura del sistema.

---

## 📋 Resumen de Tareas Asignadas

| # | Tarea | Componente | Ubicación Principal | Estado |
|---|---|---|---|---|
| **3.1** | Controlador de Healthcheck | Backend / API | `api/app/Http/Controllers/Api/V1/HealthCheckController.php` | Implementado |
| **3.2** | Workflow de CI (Integración Continua) | GitHub Actions | `.github/workflows/ci.yml` | Implementado |
| **3.3** | Script de Respaldo PostgreSQL | DevOps / Scripts | `scripts/backup_db.bat` / `scripts/backup_db.sh` | Implementado |

---

## 🛠️ Detalle de Tareas

### Tarea 3.1: Controlador de Estado del Sistema (Healthcheck)
* **Objetivo:** Monitorear la salud operativa del backend y sus servicios dependientes.
* **Ubicación:** `api/app/Http/Controllers/Api/V1/HealthCheckController.php`
* **Ruta de API / Endpoint:** `GET /api/v1/health`
* **Funcionalidad requerida:**
  - Verificación de conectividad a la base de datos PostgreSQL mediante consulta ping.
  - Verificación de disponibilidad del almacenamiento (`storage`) para lectura/escritura de archivos temporales.
  - Verificación del estado de variables de entorno críticas del sistema.
  - Retorno de carga útil en formato JSON con la siguiente estructura:
    ```json
    {
      "status": "ok",
      "timestamp": "2026-09-18T00:16:00Z",
      "db_status": "connected",
      "storage_status": "writable",
      "environment": "production"
    }
    ```
  - Manejo de errores con código HTTP 503 (`Service Unavailable`) en caso de fallo crítico en base de datos.

---

### Tarea 3.2: Workflow de Integración Continua (CI / GitHub Actions)
* **Objetivo:** Automatizar las pruebas y validaciones de construcción en cada Pull Request para garantizar la calidad del código.
* **Ubicación:** `.github/workflows/ci.yml`
* **Disparadores:** Eventos `pull_request` dirigidos a la rama `main` y `push` a ramas base.
* **Funcionalidad requerida:**
  - **Pipeline Backend:**
    - Configurar entorno PHP (versión 8.2+) con extensiones necesarias (`pdo`, `pgsql`, `mbstring`, etc.).
    - Instalar dependencias con Composer (`composer install`).
    - Ejecutar suite de pruebas unitarias y de integración (`php artisan test`).
  - **Pipeline Frontend:**
    - Configurar Node.js (versión 20.x).
    - Instalar dependencias con `npm install` o `npm ci`.
    - Compilar y validar el empaquetado del frontend (`npm run build`).

---

### Tarea 3.3: Script de Copia de Seguridad Automatizada de PostgreSQL
* **Objetivo:** Proveer una herramienta de respaldo confiable para la base de datos de producción y desarrollo.
* **Ubicación:** 
  - `scripts/backup_db.bat` (entornos Windows)
  - `scripts/backup_db.sh` (entornos Linux/Docker/macOS)
* **Funcionalidad requerida:**
  - Conexión a PostgreSQL utilizando variables de entorno (`PGHOST`, `PGPORT`, `PGUSER`, `PGDATABASE`).
  - Generación de volcado con `pg_dump` en formato comprimido/personalizado (`-Fc`).
  - Creación automática del directorio de destino `backups/` si no existe.
  - Generación de nombres de archivo con estampas de tiempo normalizadas (ejemplo: `bruce_fire_YYYY-MM-DD_HHMMSS.dump`).
  - Comprobación de código de salida (`exit code`) y reporte de estado.

---

## 🚀 Flujo de Trabajo Git para la Entrega

1. **Creación y desarrollo en rama propia:**
   ```bash
   git checkout -b feature/deivi-devops
   ```
2. **Commit de avances con Conventional Commits:**
   ```bash
   git add .
   git commit -m "docs: define plan de trabajo para devops e integracion"
   ```
3. **Publicación de la rama:**
   ```bash
   git push -u origin feature/deivi-devops
   ```
4. **Pull Request:**
   - Crear el Pull Request desde `feature/deivi-devops` hacia `main` (o rama base de integración).
   - Solicitar revisión al Líder de Proyecto (Jose Miguel Urcia).
