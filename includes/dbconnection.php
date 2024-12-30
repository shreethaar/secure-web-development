<?php 
// DB credentials.
define('DB_USER','surveyuser');
define('DB_PASS','your_password');

define('DB_HOST','localhost');
define('DB_NAME','survey_db');
// Establish database connection.
try
{
$dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
}
catch (PDOException $e)
{
exit("Error: " . $e->getMessage());
}
?>
