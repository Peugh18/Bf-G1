@echo off
setlocal enabledelayedexpansion

REM PostgreSQL backup. Existing environment variables take priority.
if "%PGHOST%"=="" set "PGHOST=localhost"
if "%PGPORT%"=="" set "PGPORT=5432"
if "%PGUSER%"=="" set "PGUSER=postgres"
if "%PGDATABASE%"=="" set "PGDATABASE=bruce_fire"
if "%BACKUP_DIR%"=="" set "BACKUP_DIR=%~dp0..\backups"

if not exist "%BACKUP_DIR%" mkdir "%BACKUP_DIR%"

for /f %%i in ('powershell -NoProfile -Command "Get-Date -Format yyyy-MM-dd_HHmmss"') do (
  set "STAMP=%%i"
)

set "FILE_NAME=bruce_fire_%STAMP%.dump"
set "OUTPUT_FILE=%BACKUP_DIR%\%FILE_NAME%"

where pg_dump >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
  echo Error: pg_dump no esta instalado o no esta en PATH.
  exit /b 1
)

echo Creando respaldo de %PGDATABASE% en %OUTPUT_FILE%
pg_dump -h "%PGHOST%" -p "%PGPORT%" -U "%PGUSER%" -d "%PGDATABASE%" -Fc -f "%OUTPUT_FILE%"

if !ERRORLEVEL! NEQ 0 (
  echo Error: no se pudo crear la copia de seguridad.
  exit /b 1
)

echo Respaldo creado correctamente: %OUTPUT_FILE%
exit /b 0
