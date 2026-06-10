@echo off
setlocal EnableDelayedExpansion

echo.
echo  ==========================================
echo    DEV-FRAME - Build and Push
echo  ==========================================
echo.

:: --- [1/4] Kontrola prerekvizit ---
echo [1/4] Kontrola prerekvizit...

where git >nul 2>&1
if errorlevel 1 (
    echo  [CHYBA] Git neni dostupny v PATH.
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

for /f "tokens=*" %%v in ('node --version') do set "NODE_VER=%%v"
for /f "tokens=*" %%v in ('git --version') do set "GIT_VER=%%v"
echo  Node %NODE_VER%  /  %GIT_VER%  OK
echo.

:: Kontrola cistosti working tree (mimo public/build)
for /f %%i in ('git status --porcelain -- . ":(exclude)public/build"') do set "HAS_CHANGES=1"
if defined HAS_CHANGES (
    echo  [UPOZORNENI] Mas necommitovane zmeny:
    git status --short -- . ":(exclude)public/build"
    echo.
    set /p "CONFIRM=Pokracovat i tak? (a/N): "
    if /i not "!CONFIRM!"=="a" (
        echo  Preruseno.
        pause & exit /b 0
    )
    echo.
)

:: --- [2/4] Build frontendu ---
echo [2/4] Build frontendu (npm run build)...
call npm run build
if errorlevel 1 (
    echo  [CHYBA] npm run build selhal.
    pause & exit /b 1
)
echo.

:: --- [3/4] Commit public/build ---
echo [3/4] Commitovani zkompilovanch assetu...
call git add public/build
git diff --cached --quiet
if errorlevel 1 (
    call git commit -m "build: zkompiluj frontend assets"
    if errorlevel 1 (
        echo  [CHYBA] git commit selhal.
        pause & exit /b 1
    )
    echo  Assets zkommunikovany do repozitare.
) else (
    echo  Zadne zmeny v public/build, commit preskocen.
)
echo.

:: --- [4/4] Git push ---
echo [4/4] Push na GitHub (origin main)...
call git push origin main
if errorlevel 1 (
    echo  [CHYBA] git push selhal.
    pause & exit /b 1
)
echo.

:: --- Hotovo ---
for /f "tokens=*" %%v in ('git log -1 --format^=^"%%h %%s^"') do set "LAST_COMMIT=%%v"
echo  ==========================================
echo    Hotovo! Server muze byt nasazen.
echo  ==========================================
echo    Posledni commit: %LAST_COMMIT%
echo    Spust deploy.bat na serveru.
echo  ==========================================
echo.

pause
endlocal
