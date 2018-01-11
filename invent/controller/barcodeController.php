<?php
require '../../library/config.php';
require '../../library/functions.php';
require "../function/tools.php";


if( isset( $_GET['deleteBarcode'] ) )
{
	$sc = 'fail';
	$barcode = $_POST['barcode'];
	$bc	= new barcode();

	if( $bc->delete($barcode) )
	{
		$sc = 'success';
	}
	
	echo $sc;
}


if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('sProduct');
	deleteCookie('sBarcode');
	deleteCookie('sUnit');
	echo "success";
}

?>
