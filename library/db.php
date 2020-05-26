<?php
require_once 'config.php';

$db2Host = 'localhost';
$db2User = 'root';
$db2Pass = 'ZT20o15u21c3H808';
$db2Name = 'warrix_sap';


$db2Conn = mysqli_connect ($db2Host, $db2User, $db2Pass, $db2Name) or die ('MySQL connect failed. ' . mysql_error());
mysqli_query($db2Conn,'SET NAMES utf8');
date_default_timezone_set('Asia/Bangkok');


function db2Query($sql2)
{
	global $db2Conn;
	$result = mysqli_query($db2Conn, $sql2);
	return $result;
}



function db2Error()
{
	global $db2Conn;
	return mysqli_error($db2Conn);
}




function db2AffectedRows()
{
	global $db2Conn;

	return mysqli_affected_rows($db2Conn);
}




function db2FetchArray($result) {
	return mysqli_fetch_array($result);
}




function db2FetchAssoc($result)
{
	return mysqli_fetch_assoc($result);
}




function db2FetchRow($result)
{
	return mysqli_fetch_row($result);
}




function db2FreeResult($result)
{
	return mysqli_free_result($result);
}




function db2FetchObject($result)
{
	return mysqli_fetch_object($result);
}



function db2NumRows($result)
{
	return mysqli_num_rows($result);
}



function db2Select($dbName)
{
	return mysqli_select_db($dbName);
}




function db2InsertId()
{
	global $db2Conn;
	return mysqli_insert_id($db2Conn);
}



function startTransection2()
{
	global $db2Conn;
	return mysqli_autocommit($db2Conn, FALSE);
}




function endTransection2()
{
	global $db2Conn;
	return mysqli_autocommit($db2Conn, TRUE);
}



function commitTransection2()
{
	global $db2Conn;
	return mysqli_commit($db2Conn);
}



function dbRollback2()
{
	global $db2Conn;
	return mysqli_rollback($db2Conn);
}


?>
