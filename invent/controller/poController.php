<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";


if( isset( $_GET['closePO'] ) )
{
	$reference 	= $_GET['reference'];
	$bookcode	= $_GET['bookcode'];
	$po = new po();
	$sc = $po->close($bookcode, $reference) === TRUE ? 'success' : 'ปิดใบสั่งซื้อไม่สำเร็จ';
	echo $sc;
}


if( isset( $_GET['unClosePO'] ) )
{
	$reference 	= $_GET['reference'];
	$bookcode	= $_GET['bookcode'];
	$po = new po();
	$sc = $po->unClose($bookcode, $reference) === TRUE ? 'success' : 'ยกเลิกการปิดใบสั่งซื้อไม่สำเร็จ';
	echo $sc;
}




if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('sPoCode');
	deleteCookie('sPoName');
	deleteCookie('sFrom');
	deleteCookie('sTo');
	deleteCookie('sPoStatus');
	echo 'done';	
}

?>