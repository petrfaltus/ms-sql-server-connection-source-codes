@echo off

rem https://hub.docker.com/_/microsoft-mssql-server

docker run -e "ACCEPT_EULA=Y" -e "SA_PASSWORD=Syst3mAdm1n!" -e "MSSQL_PID=Express" -d -p 1433:1433 --name mssql-db mcr.microsoft.com/mssql/server:2017-CU8-ubuntu
echo.

docker container ls
echo.

pause
