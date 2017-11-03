
<?php
ini_set('display_errors', 'On');

date_default_timezone_set('Asia/Bangkok');
//ob_start("ob_gzhandler");
ob_start();
error_reporting(E_ALL);

// start the session
//session_start();

 //database connection config
$dbHost = '172.20.11.14';
$dbUser = 'fm1234';
$dbPass = 'x2y2';
$dbName = 'formula';

include '../library/database.php';

$qs = dbQuery("SELECT * FROM PROD LIMIT 1");

print_r($qs);

?>
