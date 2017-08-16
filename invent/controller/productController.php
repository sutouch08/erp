<?php
require "../../library/config.php";
require"../../library/functions.php";
require "../function/tools.php";
require "../function/product_helper.php";

if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('sProductCode');
	deleteCookie('sProductName');
	deleteCookie('sProductGroup');
	deleteCookie('sProductCategory');
	echo 'done';	
}

?>