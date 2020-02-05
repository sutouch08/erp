<?php
	$id_order = $_POST['id_order'];
	$item_code = $_POST['itemCode'];
	$qty = $_POST['qty'];

	$qr = "SELECT * FROM tbl_product WHERE code = '{$item_code}'";
	$qs = dbQuery($qr);

	$ds = array();

	if(dbNumRows($qs) == 1)
	{
		$item = dbFetchObject($qs);
		$ds[0][$item->id] = $qty;
	}

	$result 	= TRUE;
	$error 		= "";
	$err_qty		= 0;

	if( count($ds) > 0 ){
		$order	= new order($id_order);
		$stock 	= new stock();
		$disc		= new discount();
		$payment = new payment_method($order->id_payment);
		$credit = new customer_credit();
		startTransection();

		//---	ขาย ขายออนไลน์
		if($order->role == 1)
		{
			include 'order/add_order_detail.php';
		}

		//--- ฝากขาย
		if( $order->role == 2)
		{
			include 'order/add_consign_detail.php';
		}

		//---	เบิกอภินันท์
		if( $order->role == 3)
		{
			include 'order/add_support_detail.php';
		}

		//--	สปอนเซอร์
		if( $order->role == 4)
		{
			include 'order/add_sponsor_detail.php';
		}

		//---	เบิกแปรสภาพ
		if( $order->role == 5)
		{
			include 'order/add_transform_detail.php';
		}

		//---	ยืมสินค้า
		if( $order->role == 6)
		{
			include 'order/add_lend_detail.php';
		}


		if( $result === TRUE )
		{
			$order->changeStatus($order->id, 0); //--- เปลี่ยนกลับมาเป็นยังไม่เซฟอีกครั้ง
			commitTransection();
		}
		else
		{
			dbRollback();
		}
		endTransection();

	}
	else 	//--- if count
	{
		$error = "Error : No items founds";
	}

	$sc = $result ===  TRUE ? 'success' : ( $err_qty > 0 ? $error.' : '.$err_qty.' item(s)' : $error);
	echo $sc;

?>
