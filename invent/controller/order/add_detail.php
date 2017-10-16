<?php
	$ds 			= $_POST['qty'];
	$result 		= TRUE;
	$error 		= "";
	$err_qty		= 0;

	if( count($ds) > 0 ){
		$order	= new order($_POST['id_order']);
		$stock 	= new stock();
		$disc		= new discount();
		$payment = new payment_method($order->id_payment);
		$credit = new customer_credit();
		startTransection();

		if($order->role == 1)
		{
			include 'order/add_order_detail.php';
		}

		if( $order->role == 4)
		{
			include 'order/add_sponsor_detail.php';
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
