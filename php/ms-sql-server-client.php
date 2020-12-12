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

$db_update_column = "remark";
$db_update_column_variable = ":updatevar";

$db_column = "id";
$db_column_variable = ":var";
$db_column_value = 1;

$db_total_name = "total";

$db_factorial_variable = ":n";
$db_factorial_value = 4;

$db_result_name = "result";

$db_add_and_subtract_a_variable = ":a";
$db_add_and_subtract_a_value = 12;
$db_add_and_subtract_b_variable = ":b";
$db_add_and_subtract_b_value = 5;

$db_add_and_subtract_x_variable = ":x";
$db_add_and_subtract_x_value = -1;
$db_add_and_subtract_y_variable = ":y";
$db_add_and_subtract_y_value = -1;

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

   // UPDATE statement
   $new_comment = "PHP ".date("j.n.Y H:i:s");

   $stm0 = $conn->prepare("update ".$db_table." set ".$db_update_column."=".$db_update_column_variable." where ".$db_column."!=".$db_column_variable);
   $stm0->bindParam($db_update_column_variable, $new_comment, PDO::PARAM_STR);
   $stm0->bindParam($db_column_variable, $db_column_value, PDO::PARAM_INT);
   $stm0->execute();
   echo "Total updated rows: ".$stm0->rowCount().PHP_EOL;
   echo PHP_EOL;

   $stm0 = null;

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
   echo PHP_EOL;

   $stm2 = null;

   // SELECT function statement
   $stm3 = $conn->prepare("select dbo.factorial(".$db_factorial_variable.") as ".$db_result_name);
   $stm3->bindParam($db_factorial_variable, $db_factorial_value, PDO::PARAM_INT);
   $stm3->execute();
   echo "Total columns: ".$stm3->columnCount().PHP_EOL;

   echo "Fetch all rows ";
   $lines2 = $stm3->fetchAll(PDO::FETCH_ASSOC);
   if ($lines2 == false)
     print_r($stm3->errorInfo());
   else
     print_r($lines2);
   echo PHP_EOL;

   $stm3 = null;

   // EXECUTE procedure statement
   $stm4 = $conn->prepare("execute dbo.add_and_subtract ".$db_add_and_subtract_a_variable.", ".$db_add_and_subtract_b_variable.", ".$db_add_and_subtract_x_variable.", ".$db_add_and_subtract_y_variable);
   $stm4->bindParam($db_add_and_subtract_a_variable, $db_add_and_subtract_a_value, PDO::PARAM_INT);
   $stm4->bindParam($db_add_and_subtract_b_variable, $db_add_and_subtract_b_value, PDO::PARAM_INT);
   $stm4->bindParam($db_add_and_subtract_x_variable, $db_add_and_subtract_x_value, PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT, 20);
   $stm4->bindParam($db_add_and_subtract_y_variable, $db_add_and_subtract_y_value, PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT, 20);
   $stm4->execute();

   $stm4error = $stm4->errorInfo();
   if ((isset($stm4error[0])) and ($stm4error[0] === "00000"))
     {
      echo "X: ".$db_add_and_subtract_x_value.PHP_EOL;
      echo "Y: ".$db_add_and_subtract_y_value.PHP_EOL;
     }
   else
     print_r($stm4error);

   $stm4 = null;

   // Disconnect the database
   $conn = null;
  }
catch (PDOException $e)
  {
   echo $e->getMessage().PHP_EOL;
  }

?>
