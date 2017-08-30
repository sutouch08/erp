<?php
if( ! isset( $_GET['id_receive_product'] ) )
{
	include 'include/page_error.php';
}
else
{
	include 'include/receive_product/receive_product_by_keyboard.php';	
}

?>