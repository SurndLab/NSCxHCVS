@echo off
:: 1. 強制 UTF-8 編碼，確保中文路徑與提示字元不亂碼
chcp 65001 > nul
title 模擬競賽開發環境

:: 2. 取得短路徑 (避開「複製」或「中文」路徑導致的 MySQL/PHP 載入失敗)
for %%I in ("%~dp0.") do set "SHORT_PATH=%%~sI"

:: 設定子目錄變數
set "PHP_DIR=%SHORT_PATH%\php-8.4.6-Win32-vs17-x64"
set "MYSQL_DIR=%SHORT_PATH%\mysql-8.4.8-winx64"

echo [系統] 正在準備開發環境...
echo --------------------------------------------------

:: 3. 環境檢查：PHP 執行檔
if not exist "%PHP_DIR%\php.exe" (
    echo [錯誤] 找不到 PHP 執行檔，請檢查資料夾名稱。
    goto :eof
)

:: 4. 環境檢查：MySQL 初始化狀態 (若無 data 資料夾則報錯，提醒先執行 init)
if not exist "%MYSQL_DIR%\data" (
    echo [錯誤] 偵測到 MySQL 尚未初始化！
    echo 請先執行 init_mysql.bat 產生資料庫目錄。
    goto :eof
)

:: 5. 載入路徑至 Path (僅限此視窗)
set "PATH=%PHP_DIR%;%PATH%"

:: 6. 另開視窗啟動 MySQL (解決 267 錯誤：必須先進入短路徑目錄，再用相對路徑 start 以避開可能的中文路徑編碼問題)
echo [1/2] 正在另開視窗啟動 MySQL 伺服器...
cd /d "%MYSQL_DIR%"
start "MySQL Server" ".\bin\mysqld.exe" --defaults-file=".\my.ini" --console
cd /d "%~dp0"

:: 7. 檢查 Composer (直接執行一次版本顯示當作測試)
echo [2/2] 正在檢查元件狀態...
call composer -V >nul 2>&1
if %errorlevel% neq 0 (
    echo [警告] 找不到 Composer，請確認 php 資料夾內是否有 composer.bat/phar。
    goto :eof
) else (
    echo [成功] Composer 已就緒。
    call composer -V
)

:: 8. 設定自定義提示字元 (提醒選手當前在環境中)
:: $P 代表路徑，$G 代表 >
PROMPT (Competition_Env) $P$G

echo --------------------------------------------------
echo 環境載入完成！您現在可以開始開發。
echo MySQL 預設的帳號為 root 密碼是空的，當 MySQL 視窗跳出來之後請直接使用 Workbench 來連線資料庫。
echo 提示：請將開發資料夾放到這個環境資料夾底下，注意不要多一層資料夾，以方便進行程式開發。
echo 提示：關閉此視窗「不會」關閉 MySQL 視窗，請分開關閉。
echo --------------------------------------------------

:: 保持視窗開啟並進入交互模式
cmd /k