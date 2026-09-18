# BRUCE FIRE — Módulos Complementarios y Componentes del Sistema

Bienvenido al repositorio de trabajo colaborativo para el "Sistema web para la gestión de ventas en BRUCE FIRE S.A.C." (Proyecto Capstone UPN).

---

## 📌 Reglas de Trabajo en GitHub (Obligatorio)

1. PROHIBIDO hacer push directo a la rama `main`.
2. Cada integrante debe trabajar en su propia rama siguiendo el formato:
   - `feature/nombre-integrante-tarea` (Ejemplo: `feature/jeanpiere-export-pdf`)
3. Al terminar una tarea, se debe abrir un Pull Request (PR) hacia la rama `main` y solicitar la revisión al Líder de Proyecto (Jose Miguel Urcia).
4. Todo código debe incluir pruebas básicas o verificación de funcionamiento antes de enviar el PR.

---

## 👥 Asignación de Tareas de Desarrollo por Integrante

### 1. Jean Piere A. Corcuera Tarazona (`Arquitecto de BD / Backend`)
**Objetivo:** Desarrollar el módulo de exportación de reportes y documentación de API.

* Tarea 1.1 (Backend): Crear el servicio de exportación de Clientes y Catálogo a PDF/Excel.
  - Ubicación: `api/app/Services/Reports/CustomerExportService.php` y `api/app/Services/Reports/CatalogExportService.php`
  - Endpoint a implementar:
    - `GET /api/v1/customers/export/pdf`
    - `GET /api/v1/catalog/export/pdf`
* Tarea 1.2 (Documentación API): Crear y mantener la especificación OpenAPI / Swagger de las rutas del sistema.
  - Ubicación: `docs/swagger/openapi.json` (o archivo Postman Collection v2.1).

### 2. Valeria Jazmin Malca Saldaña (`UI/UX / Frontend`)
**Objetivo:** Desarrollar la vista de Perfil de Usuario, Ajustes y Componentes de Ayuda Interactiva.

* Tarea 2.1 (Frontend React): Desarrollar la pantalla de Perfil de Usuario y Cambio de Contraseña.
  - Ubicación: `web/src/pages/Profile/ProfilePage.tsx` y `web/src/pages/Profile/ChangePasswordModal.tsx`
  - Ruta: `/perfil`
  - Funcionalidad: Permitir al usuario logueado ver sus datos, su rol asignado y cambiar su contraseña mediante el endpoint `POST /api/v1/auth/change-password`.
* Tarea 2.2 (Frontend React): Componente de Modal de Ayuda / Guía Rápida de Uso.
  - Ubicación: `web/src/components/ui/HelpModal.tsx`
  - Funcionalidad: Botón Flotante de Ayuda en la interfaz que despliega los pasos básicos de uso según el módulo activo.

### 3. Deivi Jair Ferrer Pajilla (`DevOps / Integrador`)
**Objetivo:** Implementar endpoints de Healthcheck, automatización CI y scripts de respaldo.

* Tarea 3.1 (Backend / DevOps): Implementar el controlador de estado del sistema (Healthcheck).
  - Ubicación: `api/app/Http/Controllers/Api/V1/HealthCheckController.php`
  - Endpoint: `GET /api/v1/health`
  - Funcionalidad: Verificar conexión a PostgreSQL, disponibilidad de storage y estado de variables de entorno, retornando `{ status: "ok", timestamp, db_status }`.
* Tarea 3.2 (CI / GitHub Actions): Configurar el workflow de integración continua.
  - Ubicación: `.github/workflows/ci.yml`
  - Funcionalidad: Ejecutar automáticamente `php artisan test` en el backend y `npm run build` en el frontend ante cada Pull Request.
* Tarea 3.3 (Scripts DevOps): Script de copia de seguridad automatizada de PostgreSQL.
  - Ubicación: `scripts/backup_db.bat` (o `.sh`)

### 4. Junior Jair Quiroz Castañeda (`QA / BI / Ingeniero de Datos`)
**Objetivo:** Desarrollar suite de pruebas automatizadas en PHPUnit y endpoints de métricas básicas.

* Tarea 4.1 (Backend QA): Crear pruebas automatizadas de cobertura para los módulos de Auth y Usuarios.
  - Ubicación: `api/tests/Feature/AuthenticationTest.php` y `api/tests/Feature/UserManagementTest.php`
  - Funcionalidad: Cobertura de tests para login correcto, login con clave errónea, bloqueo de cuenta tras intentos fallidos y permisos de roles.
* Tarea 4.2 (Backend BI): Implementar endpoint de resumen de métricas para Dashboard.
  - Ubicación: `api/app/Http/Controllers/Api/V1/DashboardMetricsController.php`
  - Endpoint: `GET /api/v1/dashboard/metrics`
  - Funcionalidad: Retornar total de clientes activos, total de productos en catálogo y conteo de usuarios por rol.

---

## 🛠️ Flujo de Trabajo Git Paso a Paso

1. Clonar el repositorio.
2. Crear la rama del trabajo:
   ```bash
   git checkout -b feature/nombre-integrante-tarea
   ```
3. Desarrollar la tarea con validación local.
4. Ejecutar pruebas o verificaciones básicas.
5. Confirmar cambios y abrir un Pull Request hacia `main`.
6. Solicitar revisión al líder del proyecto.

```bash
# Ejemplo de flujo

git clone <URL_DE_ESTE_REPOSITORIO>
cd <NOMBRE_CARPETA>
git checkout -b feature/jeanpiere-export-pdf
# trabajo...
git add .
git commit -m "feat: export reports"
git push origin feature/jeanpiere-export-pdf
```

---

## ✅ Estructura de trabajo prevista

```text
api/
  app/
    Http/Controllers/Api/V1/
    Services/Reports/
  tests/Feature/
web/
  src/
    components/ui/
    pages/Profile/
docs/
  swagger/
scripts/
.github/workflows/
```

Este repositorio queda preparado para seguir el flujo recomendado de ramas, PRs y control de entregas del proyecto BRUCE FIRE.
