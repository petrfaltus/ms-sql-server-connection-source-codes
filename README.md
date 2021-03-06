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

### 3. Preparing the database
For the connection to the database use either the **sqlcmd tool** terminal or the [Microsoft SQL Server Management Studio]

#### Connection using sqlcmd tool
Use prepared Windows batches, every SQL command terminate by the keyword **GO**
```sql
SELECT @@version;
GO
```

#### Connection using Microsoft SQL Server Management Studio
User *sa* (default password *Syst3mAdm1n!*)

![user sa configuration](sql.server.management.studio.sa.png)

User *testuser* (default password *T3stUs3r!*)

![user testuser configuration](sql.server.management.studio.testuser.png)

#### SQL lines for sa
```sql
CREATE DATABASE testdb;
USE testdb;

CREATE LOGIN testuser WITH PASSWORD = 'T3stUs3r!';
CREATE USER testuser FOR LOGIN testuser;

GRANT SELECT TO testuser;
GRANT CREATE TABLE TO testuser;
GRANT INSERT TO testuser;
GRANT UPDATE TO testuser;
GRANT ALTER TO testuser;
GRANT EXECUTE TO testuser;

ALTER LOGIN testuser WITH DEFAULT_DATABASE=testdb;
```

#### SQL lines for testuser
```sql
USE testdb;

CREATE TABLE animals (
  name VARCHAR(40) NOT NULL,
  legs TINYINT NOT NULL,
  created DATETIME DEFAULT GETDATE(),
  updated DATETIME,
  remark VARCHAR(80),
  id INT IDENTITY(1,1) PRIMARY KEY NOT NULL
);

CREATE TRIGGER animals_update
  ON animals
  AFTER UPDATE
AS 
BEGIN
    SET NOCOUNT ON;
    UPDATE animals
    SET updated = GETDATE()
    WHERE id IN (SELECT id FROM Inserted)
END;

INSERT INTO animals (name, legs) VALUES ('chicken', 2);
INSERT INTO animals (name, legs) VALUES ('fox', 4);
INSERT INTO animals (name, legs) VALUES ('eagle', 2);
INSERT INTO animals (name, legs) VALUES ('ant', 6);
INSERT INTO animals (name, legs) VALUES ('horse', 4);

CREATE OR ALTER FUNCTION factorial(@n INT) RETURNS INT AS
BEGIN
  IF (@n < 0)
    RETURN -1;
  DECLARE @result INT = 1;
  IF (@n < 2)
    RETURN @result;
  DECLARE @ijk INT = 2;
  WHILE @ijk <= @n
    BEGIN
      SET @result = @result * @ijk;
      SET @ijk = @ijk + 1;
    END;
  RETURN @result;
END;

CREATE OR ALTER PROCEDURE add_and_subtract(@a INT, @b INT, @x INT OUT, @y INT OUT) AS
BEGIN
  SET @x = @a + @b;
  SET @y = @a - @b;
END;
```

#### optional SQL check lines for testuser
```sql
USE testdb;

SELECT * FROM animals;

SELECT count(*) FROM animals;
SELECT count(*) FROM animals WHERE id!=1;

SELECT dbo.factorial(2);
SELECT dbo.factorial(3);
SELECT dbo.factorial(4);

DECLARE @a INT = 12;
DECLARE @b INT = 5;
DECLARE @x INT;
DECLARE @y INT;
PRINT 'a = ' + CONVERT(VARCHAR(6), @a);
PRINT 'b = ' + CONVERT(VARCHAR(6), @b);
EXECUTE dbo.add_and_subtract @a, @b, @x OUT, @y OUT;
PRINT 'x = ' + CONVERT(VARCHAR(6), @x);
PRINT 'y = ' + CONVERT(VARCHAR(6), @y);
```

### 4. The Java client source code
- install [Java JDK] on your computer
- set the OS environment `%JAVA_HOME%` variable (must exist `"%JAVA_HOME%\bin\java.exe"`)

#### 4.1. Apache Maven
- install [Apache Maven] on your computer
- add the Maven directory (where the batch `mvn.cmd` locates) to the OS environment `%PATH%` variable

The subdirectory `java-maven` contains prepared Windows batches:
- `01-build.cmd` - cleans, compiles and builds the Maven project
- `02-run.cmd` - runs the built Java archive (JAR)
- `03-clean.cmd` - cleans the Maven project

#### 4.2. Gradle Build Tool
- install [Gradle Build Tool] on your computer
- add the Gradle directory (where the batch `gradle.bat` locates) to the OS environment `%PATH%` variable

The subdirectory `java-gradle` contains prepared Windows batches:
- `01-build.cmd` - cleans, compiles and builds the Gradle project
- `02-run.cmd` - runs the built Java archive (JAR)
- `03-clean.cmd` - cleans the Gradle project

### 5. The .NET C# client source code
- use the `csc.exe` .NET C# compiler that is the part of Microsoft .NET Framework (part of OS)

The subdirectory `csharp` contains prepared Windows batches:
- `01-compile.cmd` - compiles the source code (contains the path definition to the `csc.exe` compiler)
- `02-run.cmd` - runs the Windows executable
- `03-clean.cmd` - deletes the Windows executable

### 6. The PHP client source code
- install [PHP] on your computer
- set the OS environment `%PHP_HOME%` variable (must exist `"%PHP_HOME%\php.exe"`)
- install [Microsoft ODBC Driver] (or already installed as a part of [Microsoft SQL Server Management Studio])
- install [Microsoft Drivers for PHP for SQL Server]
- copy `php_pdo_sqlsrv_74_nts_x64.dll` from [Microsoft Drivers for PHP for SQL Server] to the PHP directory `%PHP_HOME%`

To the `php.ini` in the PHP directory `%PHP_HOME%` add lines
```
[PHP]
extension_dir = "ext"
extension=php_pdo_sqlsrv_74_nts_x64.dll

[Date]
date.timezone = Europe/Prague
```

The subdirectory `php` contains prepared Windows batch:
- `01-run.cmd` - runs the code through the PHP interpreter

## Versions
Now in August 2020 I have the computer with **Windows 10 Pro 64bit**, **12GB RAM** and available **50GB free HDD space**

| Tool | Version | Setting |
| ------ | ------ | ------ |
| [GIT] | 2.26.0.windows.1 | |
| [docker desktop] | 2.3.0.4 (46911) stable | 2 CPUs, 3GB memory, 1GB swap, 48GB disc image size |
| [Microsoft SQL Server image] | 2017-CU8-ubuntu (14.00.3029) | password for sa: Syst3mAdm1n! |
| [Microsoft SQL Server Management Studio] | 18.6 | |
| [Java JDK] | 14.0.1 | Java(TM) SE Runtime Environment (build 14.0.1+7) |
| [Apache Maven] | 3.6.3 | |
| [Gradle Build Tool] | 6.3 | |
| [PHP] | 7.4.4 | 7.4.4-nts-Win32-vc15-x64 |
| [Microsoft ODBC Driver] | 17.06.0001 | msodbcsql64.msi, msodbcsql17.dll (version 03.80) |
| [Microsoft Drivers for PHP for SQL Server] | 5.8 (5.8.0+12928) | SQLSRV58.exe |

## To do (my plans to the future)


[GIT]: <https://git-scm.com/>
[docker desktop]: <https://docs.docker.com/desktop/>
[Microsoft SQL Server image]: <https://hub.docker.com/_/microsoft-mssql-server>
[Microsoft SQL Server Management Studio]: <https://docs.microsoft.com/en-us/sql/ssms/download-sql-server-management-studio-ssms?view=sql-server-ver15>
[Java JDK]: <https://www.oracle.com/java/technologies/javase-downloads.html>
[Apache Maven]: <https://maven.apache.org/>
[Gradle Build Tool]: <https://gradle.org/>
[PHP]: <https://www.php.net/>
[Microsoft ODBC Driver]: <https://docs.microsoft.com/en-us/sql/connect/odbc/download-odbc-driver-for-sql-server?view=sql-server-ver15>
[Microsoft Drivers for PHP for SQL Server]: <https://docs.microsoft.com/en-us/sql/connect/php/microsoft-php-driver-for-sql-server?view=sql-server-ver15>
