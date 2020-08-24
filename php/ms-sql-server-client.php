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
$db_table = "animals";

$db_column = "id";
$db_column_variable = ":var";
$db_column_value = 1;

$db_total_name = "total";

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

   // Full SELECT statement
   $stm1 = $conn->prepare("select * from ".$db_table);
   $stm1->execute();
   echo "Total columns: ".$stm1->columnCount().PHP_EOL;

   echo "Fetch all rows ";
   $lines1 = $stm1->fetchAll(PDO::FETCH_ASSOC);
   if ($lines1 == false)
     print_r($stm1->errorInfo());
   else
     print_r($lines1);
   echo PHP_EOL;

   $stm1 = null;

   // SELECT WHERE statement
   $stm2 = $conn->prepare("select count(*) as ".$db_total_name." from ".$db_table." where ".$db_column."!=".$db_column_variable);
   $stm2->bindParam($db_column_variable, $db_column_value, PDO::PARAM_INT);
   $stm2->execute();
   echo "Total columns: ".$stm2->columnCount().PHP_EOL;

   echo "Fetch all rows ";
   $lines2 = $stm2->fetchAll(PDO::FETCH_ASSOC);
   if ($lines2 == false)
     print_r($stm2->errorInfo());
   else
     print_r($lines2);

   $stm2 = null;

   // Disconnect the database
   $conn = null;
  }
catch (PDOException $e)
  {
   echo $e->getMessage().PHP_EOL;
  }

?>
