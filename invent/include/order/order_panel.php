<?php 
if( $order->isOnline == 1 )
{
	include 'include/order/order_online_panel.php';	
}
else
{
	include 'include/order/order_state.php';	
}
?>

