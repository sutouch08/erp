<?php
	$sc = "สร้างออเดอร์ไม่สำเร็จ";
	$order = new order();
	$customer = new customer( $_POST['id_customer'] );
	$isOnline = $_POST['isOnline'] == 1 ? 1 : 0;
	$reference = $order->getNewReference();
	$arr = array(
					"bookcode"		=> getConfig('BOOKCODE_SO'),
					"reference"		=> $reference,
					"id_customer"	=> $_POST['id_customer'],
					"id_sale"			=> $customer->id_sale,
					"id_employee"	=> getCookie('user_id'),
					"id_payment"	=> $_POST['paymentMethod'],
					"id_channels"	=> $_POST['channels'],
					"isOnline"			=> $isOnline,
					"date_add"		=> dbDate($_POST['dateAdd']),
					"remark"			=> $_POST['remark'],
					"online_code"	=> $_POST['customerName']
					);
	if( $order->add($arr) === TRUE )
	{
		$id = $order->get_id($reference);
		$sc = $id;
	}
	
	echo $sc;	
?>