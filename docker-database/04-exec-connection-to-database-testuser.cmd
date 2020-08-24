@echo off

rem sqlcmd tool
docker exec -it mssql-db /opt/mssql-tools/bin/sqlcmd -S localhost -U testuser -P T3stUs3r!
echo.

pause
