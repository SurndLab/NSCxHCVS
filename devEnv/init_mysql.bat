@echo off
:: 強制切換編碼為 UTF-8
chcp 65001 > nul

:: 設定標題避免混淆
title MySQL 初始化工具

:: 取得批次檔所在目錄的「短路徑」 (Short Name) 如 "DEVENV~1" 避免中文編碼亂碼問題
for %%I in ("%~dp0.") do set "SHORT_PATH=%%~sI"


:: MySQL 主程式所在資料夾 (名稱需完全一樣)
set "MYSQL_SUBDIR=mysql-8.4.8-winx64"

echo [訊息] 正在執行 MySQL 8.4.8 初始化...
echo --------------------------------------------------

:: 切換到短路徑下的子目錄
cd /d "%SHORT_PATH%\%MYSQL_SUBDIR%"

:: 執行初始化
".\bin\mysqld.exe" --defaults-file=".\my.ini" --initialize-insecure --console

echo --------------------------------------------------
echo [完成] 初始化程序結束。
echo 如果上方沒噴 Error ，請檢查 %MYSQL_SUBDIR%\data 資料夾是否出現。
echo --------------------------------------------------

:: 回到最外層目錄並恢復原始路徑
cd /d "%~dp0"