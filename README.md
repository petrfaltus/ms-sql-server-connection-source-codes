# Microsoft SQL Server connection source codes
Small example console source codes how to connect to the Microsoft SQL Server, how to update rows and how to read the table.

## Running under Windows
1. clone this repository to your computer
2. install the **Microsoft SQL Server** (as a **Docker** container)
3. prepare the user, the table and rows in the database
4. build and run the example **Java** code
5. compile and run the example **.NET C#** code
6. run the example **PHP** code

### 1. Cloning to your computer
- install [GIT] on your computer
- clone this repository to your computer by the GIT command
  `git clone https://github.com/petrfaltus/ms-sql-server-connection-source-codes.git`

### 2. Installation of the Microsoft SQL Server (as a Docker container)
- install [docker desktop] on your computer
- refer the [Microsoft SQL Server image]

The subdirectory `docker-database` contains prepared Windows batches:
- `01-run-database.cmd` - pulls the image and runs the container **at the first time**
- `02-switch-database-OFF.cmd` - stops the already existing container
- `02-switch-database-ON.cmd` - starts the already existing container
- `03-inspect-database.cmd` - shows details for already existing container
- `04-exec-connection-to-database-sa.cmd` - executes the **sqlcmd tool** terminal into running database container (as the user *sa*)
- `04-exec-connection-to-database-testuser.cmd` - executes the **sqlcmd tool** terminal into running database container (as the user *testuser*)
- `containers.cmd` - lists currently running containers and list of all existing containers

## Versions
Now in August 2020 I have the computer with **Windows 10 Pro 64bit**, **12GB RAM** and available **50GB free HDD space**

| Tool | Version | Setting |
| ------ | ------ | ------ |
| [GIT] | 2.26.0.windows.1 | |
| [docker desktop] | 2.3.0.4 (46911) stable | 2 CPUs, 3GB memory, 1GB swap, 48GB disc image size |
| [Microsoft SQL Server image] | 2017-CU8-ubuntu | password for sa: Syst3mAdm1n! |

## To do (my plans to the future)


[GIT]: <https://git-scm.com>
[docker desktop]: <https://docs.docker.com/desktop/>
[Microsoft SQL Server image]: <https://hub.docker.com/_/microsoft-mssql-server>
