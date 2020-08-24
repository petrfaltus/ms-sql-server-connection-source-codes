@echo off

rem sqlcmd tool
docker exec -it mssql-db /opt/mssql-tools/bin/sqlcmd -S localhost -U sa -P Syst3mAdm1n!
echo.

pause
