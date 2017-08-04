<?php
require "../../library/config.php";
require "../../library/functions.php";
require '../function/tools.php';

if( isset( $_GET['deleteStyle'] ) )
{
	$id = $_POST['id'];
	$st = new style();
	$sc = 'success';
	if( $st->delete($id) === FALSE )
	{
		$sc = $st->error;
	}
	echo $sc;
}


if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('stCode');
	deleteCookie('stName');
	echo 'success';	
}

?>