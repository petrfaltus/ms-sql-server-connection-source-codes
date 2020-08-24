@echo off

set OUT=bin

set SOURCE=MicrosoftSQLserverClient
set EXECUTABLE=%OUT%\%SOURCE%.exe
set ICON=ico\database.ico
set DOTNET_HOME=C:\WINDOWS\Microsoft.NET\Framework64\v4.0.30319

mkdir %OUT%
"%DOTNET_HOME%\csc.exe" /win32icon:%ICON% /out:%EXECUTABLE% %SOURCE%.cs
