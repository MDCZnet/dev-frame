@echo off
setlocal EnableDelayedExpansion

echo.
echo  ==========================================
echo    DEV-FRAME - Deploy Script
echo  ==========================================
echo.

:: --- [1/5] Kontrola prerekvizit ---
echo [1/5] Kontrola prerekvizit...

where git >nul 2>&1
if errorlevel 1 (
    echo  [CHYBA] Git neni dostupny v PATH.
    pause & exit /b 1
)

where php >nul 2>&1
if errorlevel 1 (
    echo  [CHYBA] PHP neni dostupne v PATH.
    pause & exit /b 1
)

where composer >nul 2>&1
if errorlevel 1 (
    echo  [CHYBA] Composer neni dostupny v PATH.
    pause & exit /b 1
)

for /f "tokens=*" %%v in ('php -r "echo PHP_VERSION;"') do set "PHP_VER=%%v"
for /f "tokens=*" %%v in ('git --version') do set "GIT_VER=%%v"
echo  PHP %PHP_VER%  /  %GIT_VER%  OK
echo.

:: Kontrola existence .env
if not exist ".env" (
    echo  [CHYBA] Soubor .env nenalezen.
    echo         Na serveru je potreba nejprve nakonfigurovat .env.
    pause & exit /b 1
)

:: --- [2/5] Git pull ---
echo [2/5] Stahovani aktualizaci z repozitare...
call git pull origin main
if errorlevel 1 (
    echo  [CHYBA] git pull selhal. Zkontroluj pripojeni nebo konflikty.
    pause & exit /b 1
)
echo.

:: --- [3/5] Composer install ---
echo [3/5] Instalace PHP zavislosti (produkce)...
call composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist
if errorlevel 1 (
    echo  [CHYBA] composer install selhal.
    pause & exit /b 1
)
echo.

:: --- [4/5] Publikace assetu + migrace ---
echo [4/5] Publikace CSS assetu...
call php artisan vendor:publish --tag=dev-frame-assets --force
if errorlevel 1 (
    echo  [CHYBA] vendor:publish selhal.
    pause & exit /b 1
)

echo  Migrace databaze...
call php artisan migrate --force
if errorlevel 1 (
    echo  [CHYBA] Migrace selhaly.
    pause & exit /b 1
)
echo.

:: --- [5/5] Optimalizace cache ---
echo [5/5] Optimalizace (cache)...
call php artisan config:cache
call php artisan route:cache
call php artisan view:cache
echo.

:: --- Hotovo ---
for /f "tokens=*" %%v in ('git log -1 --format^=^"%%h %%s^"') do set "LAST_COMMIT=%%v"
echo  ==========================================
echo    Deploy dokoncen!
echo  ==========================================
echo    Posledni commit: %LAST_COMMIT%
echo  ==========================================
echo.

pause
endlocal
