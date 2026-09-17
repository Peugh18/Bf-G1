@echo off
setlocal enabledelayedexpansion

REM Configuracion del respaldo PostgreSQL
set PGHOST=localhost
set PGPORT=5432
set PGUSER=postgres
set PGDATABASE=bruce_fire
set BACKUP_DIR=%~dp0backups

if not exist "%BACKUP_DIR%" mkdir "%BACKUP_DIR%"

for /f "tokens=1-3 delims=/ " %%a in ("%date%") do (
  set DAY=%%a
  set MONTH=%%b
  set YEAR=%%c
)

set FILE_NAME=bruce_fire_%YEAR%-%MONTH%-%DAY%_%time:~0,2%%time:~3,2%%time:~6,2%.dump
set FILE_NAME=%FILE_NAME: =0%

pg_dump -h %PGHOST% -p %PGPORT% -U %PGUSER% -d %PGDATABASE% -Fc -f "%BACKUP_DIR%\%FILE_NAME%"

if %ERRORLEVEL% NEQ 0 (
  echo Error: no se pudo crear la copia de seguridad.
  exit /b 1
)

echo Respaldo creado correctamente: %BACKUP_DIR%\%FILE_NAME%
exit /b 0
