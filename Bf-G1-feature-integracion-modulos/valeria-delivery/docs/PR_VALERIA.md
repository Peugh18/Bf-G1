Perfil, ajustes y ayuda interactiva para usuarios autenticados.

Incluye `/perfil`, `/ajustes`, cambio de contrasena y guia flotante por modulo. Se agrega el backend Laravel que faltaba para registro, inicio/cierre de sesion y preferencias persistentes, asi como las exportaciones PDF/XLSX solicitadas y su contrato OpenAPI.

Verificacion: compilacion React, pruebas PHPUnit de autenticacion/preferencias/reportes y pruebas Edge en escritorio y movil para perfil, cambio de contrasena, ayuda y ajustes persistentes.

La ejecucion actual usa SQLite local. Los pasos de arranque y la opcion PostgreSQL estan en `docs/INTEGRACION.md`.

Revision solicitada al lider de proyecto: Jose Miguel Urcia. La rama destino debe ser `main`; al preparar esta entrega no existia en el repositorio, por lo que su creacion o confirmacion queda a cargo del lider.
