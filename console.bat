@echo off

rem -------------------------------------------------------------
rem  command line bootstrap script for Windows.
rem -------------------------------------------------------------

@setlocal

set ROOT_PATH=%~dp0

if "%PHP_COMMAND%" == "" set PHP_COMMAND=php.exe

"%PHP_COMMAND%" "%ROOT_PATH%console\bin\console" %*

@endlocal
