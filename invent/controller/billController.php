<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";
require "../function/bill_helper.php";
require "../function/support_helper.php";
require "../function/sponsor_helper.php";
require "../function/lend_helper.php";

if( isset( $_GET['clearFilter']))
{
	deleteCookie('sOrderCode');
	deleteCookie('sCustomerName');
	deleteCookie('sOrderEmp');
	deleteCookie('sOrderRole');
	deleteCookie('fromDate');
	deleteCookie('toDate');
	echo 'done';
}



?>
