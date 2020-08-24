<?php

/*
1) Microsoft ODBC Driver 64bit (msodbcsql64.msi) installed

2) From SQLSRV58 to the PHP directory copied:
php_pdo_sqlsrv_74_nts_x64.dll (nts for nts, x64 for x64)

3) In the php.ini added lines:
[PHP]
extension_dir = "ext"
extension=php_pdo_sqlsrv_74_nts_x64.dll
[Date]
date.timezone = Europe/Prague

*/

$db_driver = "sqlsrv";

$db_host = "localhost";
$db_port = 1433;
$db_name = "testdb";
$db_username = "testuser";
$db_password = "T3stUs3r!";

$availableDrivers = PDO::getAvailableDrivers();

echo "Available PDO drivers ";
print_r($availableDrivers);
echo PHP_EOL;

if (!in_array($db_driver, $availableDrivers))
  {
   echo "PDO driver ".$db_driver." or it's subdriver is not available or has not been enabled in php.ini".PHP_EOL;
   exit;
  }

try
  {
   // Build the connection string and connect the database
   $dsn = $db_driver.":Server=".$db_host.",".$db_port.";Database=".$db_name;
   $conn = new PDO($dsn, $db_username, $db_password);

   echo "ATTR_CLIENT_VERSION = ";
   print_r($conn->getAttribute(PDO::ATTR_CLIENT_VERSION));
   echo "ATTR_DRIVER_NAME = ".$conn->getAttribute(PDO::ATTR_DRIVER_NAME).PHP_EOL;
   echo "ATTR_SERVER_INFO = ";
   print_r($conn->getAttribute(PDO::ATTR_SERVER_INFO));
   echo "ATTR_SERVER_VERSION = ".$conn->getAttribute(PDO::ATTR_SERVER_VERSION).PHP_EOL;
   echo PHP_EOL;

   // Disconnect the database
   $conn = null;
  }
catch (PDOException $e)
  {
   echo $e->getMessage().PHP_EOL;
  }

?>
