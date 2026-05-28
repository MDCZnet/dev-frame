@echo off
setlocal EnableDelayedExpansion

echo.
echo  ==========================================
echo    DEV-FRAME - Setup Script
echo  ==========================================
echo.

:: Detect project folder name for Laragon URL
for %%I in ("%~dp0.") do set "FOLDER_NAME=%%~nxI"
set "APP_URL=http://%FOLDER_NAME%.test"

echo  Projekt:  %FOLDER_NAME%
echo  URL:      %APP_URL%
echo.

:: --- [1/7] Kontrola prerekvizit ---
echo [1/7] Kontrola prerekvizit...

where php >nul 2>&1
if errorlevel 1 (
    echo  [CHYBA] PHP neni dostupne v PATH.
    echo         Spust Laragon a zkus znovu, nebo pridej PHP do PATH.
    pause & exit /b 1
)

where composer >nul 2>&1
if errorlevel 1 (
    echo  [CHYBA] Composer neni dostupny v PATH.
    pause & exit /b 1
)

where node >nul 2>&1
if errorlevel 1 (
    echo  [CHYBA] Node.js neni dostupny v PATH.
    pause & exit /b 1
)

where npm >nul 2>&1
if errorlevel 1 (
    echo  [CHYBA] npm neni dostupny v PATH.
    pause & exit /b 1
)

for /f "tokens=*" %%v in ('php -r "echo PHP_VERSION;"') do set "PHP_VER=%%v"
for /f "tokens=*" %%v in ('node --version') do set "NODE_VER=%%v"
echo  PHP %PHP_VER%  /  Node %NODE_VER%  OK
echo.

:: --- [2/7] .env soubor ---
echo [2/7] Priprava .env souboru...

if not exist ".env" (
    if not exist ".env.example" (
        echo  [CHYBA] Soubor .env.example nenalezen.
        pause & exit /b 1
    )
    copy ".env.example" ".env" >nul
    echo  .env vytvoren z .env.example
) else (
    echo  .env jiz existuje, preskakuji
)

powershell -NoProfile -Command "(Get-Content '.env') -replace '^APP_URL=.*', 'APP_URL=%APP_URL%' | Set-Content -Encoding UTF8 '.env'"
echo  APP_URL nastaveno na: %APP_URL%
echo.

:: --- [3/7] Composer install ---
echo [3/7] Instalace PHP zavislosti...
call composer install --no-interaction --prefer-dist
if errorlevel 1 (
    echo  [CHYBA] composer install selhal.
    pause & exit /b 1
)
echo.

:: --- [4/7] APP_KEY ---
echo [4/7] Generovani APP_KEY...
call php artisan key:generate --ansi
if errorlevel 1 (
    echo  [CHYBA] key:generate selhal.
    pause & exit /b 1
)
echo.

:: --- [5/7] Publikace assetu ---
echo [5/7] Publikace CSS assetu (vendor:publish)...
call php artisan vendor:publish --tag=dev-frame-assets --force
if errorlevel 1 (
    echo  [CHYBA] vendor:publish selhal.
    pause & exit /b 1
)
echo.

:: --- [6/8] SQLite databaze + migrace ---
echo [6/8] Priprava databaze...

if not exist "database" mkdir database
if not exist "database\database.sqlite" (
    type nul > "database\database.sqlite"
    echo  Soubor database\database.sqlite vytvoren
) else (
    echo  database.sqlite jiz existuje
)

call php artisan migrate --force
if errorlevel 1 (
    echo  [CHYBA] Migrace selhaly.
    pause & exit /b 1
)
echo.

:: --- [7/8] npm install ---
echo [7/8] Instalace JS zavislosti...
call npm install
if errorlevel 1 (
    echo  [CHYBA] npm install selhal.
    pause & exit /b 1
)
echo.

:: --- [8/8] npm run build ---
echo [8/8] Build frontendu...
call npm run build
if errorlevel 1 (
    echo  [CHYBA] npm run build selhal.
    pause & exit /b 1
)
echo.

:: --- Hotovo ---
echo  ==========================================
echo    Instalace dokoncena!
echo  ==========================================
echo    Otevri: %APP_URL%
echo    Dev server: composer dev
echo  ==========================================
echo.

pause
endlocal
