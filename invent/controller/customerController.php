<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";

if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('cName');
	deleteCookie('cCode');
	deleteCookie('cProvince');
	echo "success";	
}

?>