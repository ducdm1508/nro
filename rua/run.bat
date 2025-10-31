@echo off
setlocal

echo ===============================
echo   Dang chay Server Game...
echo ===============================

REM --- Di chuyen toi thu muc goc project
cd /d "%~dp0"

REM --- Kiem tra thu muc src ton tai?
if not exist src (
    echo ERROR: Khong tim thay thu muc "src". Dat run.bat cung cap voi src.
    pause
    exit /b 1
)

REM --- Tao bin neu chua co
if not exist bin mkdir bin

REM --- Xoa file sources.txt neu ton tai
if exist sources.txt del sources.txt

REM --- Lay tat ca file .java trong src
for /R "src" %%F in (*.java) do @echo %%F>> sources.txt

REM --- Kiem tra neu rong
for /f "usebackq delims=" %%a in ("sources.txt") do set HAS_SOURCES=1
if not defined HAS_SOURCES (
    echo Khong tim thay file .java nao trong thu muc src.
    pause
    exit /b 1
)

REM --- Bien dich tat ca source
echo Dang bien dich tat ca source...
javac -encoding UTF-8 -cp "lib/*" -d bin @sources.txt

if %errorlevel% neq 0 (
    echo.
    echo !!! Loi khi bien dich. Kiem tra cac thong bao loi o tren.
    pause
    exit /b 1
)

echo Bien dich thanh cong.
echo Khoi dong server...

REM --- Chay server
java -cp "lib/*;bin" server.ServerManager

echo.
echo Server da dung hoac da crash. Nhan phim bat ky de dong.
pause
endlocal
