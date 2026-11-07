@echo off
title Iniciando Sistema Gestao de TI...
echo ===========================================
echo   Iniciando o Servidor do Sistema Gestao...
echo ===========================================
echo.

:: Aguarda 2 segundos e abre o navegador automaticamente na página do sistema
timeout /t 2 /nobreak >nul
start http://localhost:8000

:: Executa o Laravel usando o PHP do próprio XAMPP
C:\xampp\php\php.exe artisan serve