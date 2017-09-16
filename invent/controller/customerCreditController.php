<?php 
require "../../library/config.php";
require "../../library/functions.php";
include '../function/tools.php';

if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('caCode');
	deleteCookie('caName');
	echo 'success';	
}


?>