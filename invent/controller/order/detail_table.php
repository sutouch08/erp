<?php
	$sc = "no data found";
	$order 		= new order($_GET['id_order']);
	$qs  			= $order->getDetails($order->id);
	if( dbNumRows($qs) > 0 )
	{
		$no = 1;
		$total_qty = 0;
		$total_discount = 0;
		$total_amount = 0;
		$total_order = 0;
		$image = new image();
		$ds = array();
		while( $rs = dbFetchObject($qs) )
		{
			$arr = array(
							"id"		=> $rs->id,
							"no"	=> $no,
							"imageLink"	=> $image->getProductImage($rs->id_product, 1),
							"productCode"	=> $rs->product_code,
							"productName"	=> $rs->product_name,
							"price"	=> number_format($rs->price, 2),
							"qty"	=> number_format($rs->qty),
							"discount"	=> ($order->role == 2 ? $rs->gp .' %' : $rs->discount),
							"amount"	=> number_format($rs->total_amount, 2)
							);
			array_push($ds, $arr);
			$total_qty += $rs->qty;
			$total_discount += $rs->discount_amount;
			$total_amount += $rs->total_amount;
			$total_order += $rs->qty * $rs->price;
			$no++;
		}
		$arr = array(
					"total_qty" => number_format($total_qty),
					"order_amount" => number_format($total_order, 2),
					"total_discount" => number_format($total_discount, 2),
					"total_amount" => number_format($total_amount, 2)
				);
		array_push($ds, $arr);
		$sc = json_encode($ds);
	}
	echo $sc;

?>
