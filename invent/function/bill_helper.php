<?php

function order_sold($id_order)
{
	$order 		= new order($id_order);
	$product		= new product();
	$ref			= $order->reference;
	$id_cus		= $order->id_customer;
	$id_sale		= get_id_sale_by_customer($id_cus);
	$role			= $order->role;
	$id_emp		= $order->id_employee;
	
	$result	= TRUE;
	$qr  = "SELECT id_product_attribute, product_qty AS qty FROM tbl_order_detail ";
	$qr .= "JOIN tbl_product ON tbl_order_detail.id_product = tbl_product.id_product ";
	$qr .= "WHERE id_order = ".$id_order." AND is_visual = 1";
	$qr  = dbQuery($qr);
	if( dbNumRows($qr) > 0 )
	{
		while( $rs = dbFetchArray($qr) )
		{
			$id_pa 	= $rs['id_product_attribute'];
			$qty	 	= $rs['qty'];
			sold_product($id_order, $id_pa, $qty, $ref, $id_cus, $role, $id_emp);
		}
	}
	$qs 		= dbQuery('SELECT id_product_attribute, SUM(qty) AS qty FROM tbl_qc WHERE id_order = '.$id_order.' AND valid = 1 GROUP BY id_product_attribute');
	if( dbNumRows($qs) > 0 )
	{
		while( $rs = dbFetchArray($qs) )
		{
			$id_pa 	= $rs['id_product_attribute'];
			$qty	 	= $rs['qty'];
			$ds		= get_detail_from_order($id_order, $id_pa);
			if( $ds !== FALSE )
			{
				$id_pd 			= $ds['id_product'];
				$p_name			= $ds['product_name'];	
				$p_ref			= $ds['product_reference'];
				$order_qty		= $ds['product_qty'];
				$barcode		= $ds['barcode'];
				$price			= $ds['product_price'];
				$p_dis			= $ds['reduction_percent'];
				$a_dis			= $ds['reduction_amount'];
				$dis_amount		= $p_dis > 0 ? $qty * ($price * ($p_dis * 0.01)) : $qty * $a_dis;
				$final_price		= $p_dis > 0 ? $price - ($price * ($p_dis * 0.01)) : $price - $a_dis;
				$total_amount	= $qty * $final_price;
				$cost 			= $product->get_product_cost($id_pa);
				$total_cost		= $qty * $cost;	
			
				$qr = "INSERT INTO tbl_order_detail_sold ";
				$qr .= "(id_order, reference, id_role, id_customer, id_employee, id_sale, id_product, ";
				$qr .= "id_product_attribute, product_name, product_reference, barcode, product_price, ";
				$qr .= "order_qty, sold_qty, reduction_percent, reduction_amount, discount_amount, final_price, total_amount, cost, total_cost)";
				$qr .= " VALUES ";
				$qr .= "(".$id_order.", '".$ref."', ".$role.", ".$id_cus.", ".$id_emp.", ".$id_sale.", ".$id_pd.", ".$id_pa.", '".$p_name."', '".$p_ref."', '".$barcode."', ";
				$qr .= $price.", ".$order_qty.", ".$qty.", ".$p_dis.", ".$a_dis.", ".$dis_amount.", ".$final_price.", ".$total_amount.", ".$cost.", ".$total_cost.")";
				
				$sc = dbQuery($qr);
				if( ! $sc )
				{
					$result = FALSE;	
				}
			} // end if
		}// end while		
	}
	else
	{
		$result = FALSE;	
	}
	
	return $result;
}



function sold_product($id_order, $id_pa, $qty, $reference = '', $id_customer = 0, $role = 1, $id_employee = '' )
{
	if( $reference == '' )
	{
		$order 			= new order($id_order);
		$reference		= $order->reference;
		$id_customer	= $order->id_customer;
		$role				= $order->role;
		$id_employee	= $order->id_employee;
	}
	
	$product	= new product();
	$id_sale	= get_id_sale_by_customer($id_customer);
	
	$sc		= TRUE;
	$ds		= get_detail_from_order($id_order, $id_pa); /// ตรวจสอบว่ามีสินค้านี้อยูทในออเดอร์หรือป่าว
	if( $ds !== FALSE )
	{
		$id_pd 			= $ds['id_product'];
		$p_name			= $ds['product_name'];	
		$p_ref			= $ds['product_reference'];
		$order_qty		= $ds['product_qty'];
		$barcode		= $ds['barcode'];
		$price			= $ds['product_price'];
		$p_dis			= $ds['reduction_percent'];
		$a_dis			= $ds['reduction_amount'];
		$dis_amount		= $p_dis > 0 ? $qty * ($price * ($p_dis * 0.01)) : $qty * $a_dis;
		$final_price		= $p_dis > 0 ? $price - ($price * ($p_dis * 0.01)) : $price - $a_dis;
		$total_amount	= $qty * $final_price;
		$cost 			= $product->get_product_cost($id_pa);
		$total_cost		= $qty * $cost;	
					
		$qr = "INSERT INTO tbl_order_detail_sold ";
		$qr .= "(id_order, reference, id_role, id_customer, id_employee, id_sale, id_product, ";
		$qr .= "id_product_attribute, product_name, product_reference, barcode, product_price, ";
		$qr .= "order_qty, sold_qty, reduction_percent, reduction_amount, discount_amount, final_price, total_amount, cost, total_cost)";
		$qr .= " VALUES ";
		$qr .= "(".$id_order.", '".$reference."', ".$role.", ".$id_customer.", ".$id_employee.", ".$id_sale.", ".$id_pd.", ".$id_pa.", '".$p_name."', '".$p_ref."', '".$barcode."', ";
		$qr .= $price.", ".$order_qty.", ".$qty.", ".$p_dis.", ".$a_dis.", ".$dis_amount.", ".$final_price.", ".$total_amount.", ".$cost.", ".$total_cost.")";		
		$sc = dbQuery($qr);
	}
	else
	{
		$sc = FALSE;	
	}
	return $sc;
}


function getBillAmount($id_order)
{
	$sc = 0;
	$qr = "SELECT SUM(final_price) AS amount FROM tbl_qc ";
	$qr .= "JOIN tbl_order_detail ON tbl_qc.id_order = tbl_order_detail.id_order ";
	$qr .= "AND tbl_qc.id_product_attribute = tbl_order_detail.id_product_attribute ";
	$qr .= "WHERE tbl_qc.id_order = ".$id_order." AND tbl_qc.valid = 1";
	$qs = dbQuery($qr);
	if( dbNumRows($qs) > 0 )
	{
		list( $rs ) = dbFetchArray($qs);
		if( ! is_null($rs) )
		{
			$sc = $rs;
		}
	}
	
	return $sc;
}

function get_current_state($id_order)
{
	$qs = dbQuery("SELECT current_state FROM tbl_order WHERE id_order = ".$id_order);
	list($state) = dbFetchArray($qs);
	return $state;	
}






function show_discount($percent, $amount)
{
	 $unit 	= " %";
	 $dis	= 0.00;
	if($percent != 0.00){ $dis = $percent; }else{ $dis = number_format($amount, 2); $unit = ""; }
	return $dis.$unit;
}







function get_temp_qty($id_order, $id_product_attribute)
{
	$qty = 0;
	$qs = dbQuery("SELECT SUM(qty) AS qty FROM tbl_temp WHERE id_order = ".$id_order." AND id_product_attribute = ".$id_product_attribute);
	if(dbNumRows($qs) == 1 )
	{
		$rs = dbFetchArray($qs);
		$qty = $rs['qty'];
	}
	return $qty;
}	







function get_sold_data($id_order, $id_product_attribute)
{
	$rs = false;
	$role = order_role($id_order);
	if($role == 5 )
	{
		$qr = "SELECT SUM(qty) AS sold_qty, reduction_percent, reduction_amount ";
		$qr .= "FROM tbl_qc JOIN tbl_order_detail ON tbl_qc.id_order = tbl_order_detail.id_order AND tbl_qc.id_product_attribute = tbl_order_detail.id_product_attribute ";
		$qr .= "WHERE tbl_qc.id_order = ".$id_order." AND tbl_qc.id_product_attribute = ".$id_product_attribute." AND tbl_qc.valid = 1";
		$qs = dbQuery($qr);
	}
	else
	{
		$qs = dbQuery("SELECT * FROM tbl_order_detail_sold WHERE id_order = ".$id_order." AND id_product_attribute = ".$id_product_attribute);
	}
	if( dbNumRows($qs) == 1 )
	{
		$rs = dbFetchArray($qs);	
	}
	return $rs;
}







function order_role($id_order)
{
	$role = 1;
	$qs = dbQuery("SELECT role FROM tbl_order WHERE id_order = ".$id_order);
	if(dbNumRows($qs) == 1 )
	{
		list($role) = dbFetchArray($qs);
	}
	return $role;
}






//-----------------  Id Zone ในคลังฝากขาย จากออเดอร์ฝากขาย
function getConsignmentIdZone($id_order)
{
	$sc = FALSE;
	$qs = dbQuery("SELECT id_zone FROM tbl_order_consignment WHERE id_order = ".$id_order);
	if( dbNumRows($qs) == 1 )
	{
		list( $sc ) = dbFetchArray( $qs );	
	}
	return $sc;
}




//----------------------------  เปลี่นสถานะใน temp ใช้ในการเปิดบิล
function updateTemp($status, $id_order, $id_pa, $limit)
{
	return dbQuery("UPDATE tbl_temp SET status = ".$status." WHERE id_temp IN( SELECT id_temp FROM (SELECT id_temp FROM tbl_temp WHERE id_order = ".$id_order." AND id_product_attribute = ".$id_pa." LIMIT ".$limit.") tmp)"); 	
}






function createErrorLog($functionName, $id_order, $reference, $id_pa)
{
	return dbQuery("INSERT INTO tbl_error_log ( function_name, id_order, reference, id_product_attribute ) VALUES ('".$functionName."', ".$id_order.", '".$reference."', ".$id_pa.")");	
}







function doc_type($role)
{
	switch($role){
		case 1 :
			$content="order";
			$title = "Packing List";
			break;
		case 2 : 
			$content = "requisition";
			$title = "ใบเบิกสินค้า / Requisition Product";
			break;
		case 3 :
			$content = "lend";
			$title = "ใบยืมสินค้า / Lend Product";
			break;
		case 4 :
			$content = "sponsor";
			$title = "รายการสปอนเซอร์สโมสร / Sponsor Order";
			break;
		case 5 :
			$content = "consignment";
			$title = "ใบส่งของ / ใบแจ้งหนี้  สินค้าฝากขาย";
			break;
		case 6 :
			$content = "requisition";
			$title = "ใบส่งของ / ใบเบิกสินค้าเพื่อแปรรูป";
			break;
		case 7 :
			$content = "order_support";
			$title = "รายการเบิกอภินันทนาการ / Support Order";
			break;
		default :
			$content = "order";
			$title = "ใบส่งของ / ใบแจ้งหนี้";
			break;
	}	
	$type = array("content"=>$content, "title"=>$title);
	return $type;
}







function get_header($order)
{
	if($order->role == 3 )
	{
		$header		= array(
								"เลขที่เอกสาร"=>$order->reference, 
								"วันที่"=>thaiDate($order->date_add), 
								"ผู้ยืม"=>employee_name($order->id_employee), 
								"ผู้ทำรายการ" => employee_name(get_lend_user_id($order->id_order)),
								"เลขที่อ้างอิง"	=> getInvoice($order->id_order)
							);
	}
	else if( $order->role == 2 || $order->role == 6 )
	{
		$header		= array(
									"ลูกค้า"=>customer_name($order->id_customer), 
									"วันที่"=>thaiDate($order->date_add), 
									"ผู้เบิก"=>employee_name($order->id_employee), 
									"เลขที่เอกสาร"=>$order->reference,
									"เลขที่อ้างอิง"	=> getInvoice($order->id_order)
									
									);	
	}
	else if( $order->role == 7)
	{
		$header	= array(
									"ผู้รับ"=>customer_name($order->id_customer), 
									"วันที่"=>thaiDate($order->date_add), 
									"ผู้เบิก"=>employee_name($order->id_employee), 
									"เลขที่เอกสาร"=>$order->reference, 
									"ผู้ดำเนินการ" => employee_name(get_id_user_support($order->id_order)),
									"เลขที่อ้างอิง"	=> getInvoice($order->id_order)
									);		
	}
	else if( $order->role == 4 )
	{
		$header	= array(
								"ผู้รับ"=>customer_name($order->id_customer), 
								"วันที่"=>thaiDate($order->date_add), 
								"ผู้เบิก"=>employee_name($order->id_employee), 
								"เลขที่เอกสาร"=>$order->reference, 
								"ผู้ดำเนินการ" => employee_name(get_id_user_sponsor($order->id_order)),
								"เลขที่อ้างอิง"	=> getInvoice($order->id_order)
								);
	}
	else
	{
		$header	= array(
							"ลูกค้า"=>customer_name($order->id_customer), 
							"วันที่"=>thaiDate($order->date_add), 
							"พนักงานขาย"=>sale_name($order->id_sale), 
							"เลขที่เอกสาร"=>$order->reference,
							"เลขที่อ้างอิง"	=> getInvoice($order->id_order)
							);
	}	
	
	return $header;
}


?>