<?php

function selectRole($role = '')
{
	$sc = '';
	$qs = dbQuery("SELECT * FROM tbl_order_role");
	if( dbNumRows($qs) )
	{
		while($rs = dbFetchObject($qs))
		{
			$sc .= '<option value="'.$rs->id.'" '.isSelected($role, $rs->id).'>'.$rs->name.'</option>';
		}
	}
	return $sc;
}




function clearBuffer($id_order)
{
	$sc = TRUE;

	$buffer = new buffer();

	//---	ลบรายการที่มียอดเป็น 0 ทิ้ง
	$sc = $buffer->dropZero($id_order);

	//---	เคลียร์ยอดที่เหลือไปเข้า cancle
	$qs = $buffer->getBuffer($id_order);
	if( dbNumRows($qs) > 0 )
	{
		$cancle = new cancle_zone();
		while( $rs = dbFetchObject($qs))
		{
			//---	เพิ่มรายการเข้า cancle ถ้าไม่มี insert ถ้ามี update
			$cn = $cancle->updateCancle($id_order, $rs->id_style, $rs->id_product, $rs->id_zone, $rs->id_warehouse, $rs->qty);

			//---	delete buffer row
			$cb = $buffer->delete($rs->id);

			if( $cn === FALSE OR $cb === FALSE)
			{
				$sc = FALSE;
			}
		}
	}

	return $sc;
}





function doc_type($role)
{
	switch($role)
	{
		case 1 :
			$content	= "order";
			$title 		= "Packing List";
		break;

		case 2 :
			$content = "consign";
			$title = "ใบส่งของ / ใบแจ้งหนี้  สินค้าฝากขาย";
		break;

		case 3 :
			$content = "support";
			$title = "ใบส่งของ / รายการเบิกอภินันทนาการ";
		break;

		case 4 :
			$content = "sponsor";
			$title = "ใบส่งของ / รายการสปอนเซอร์สโมสร";
		break;

		case 5 :
			$content = "transform";
			$title = "ใบส่งของ / ใบเบิกสินค้าเพื่อแปรรูป";
		break;

		case 6 :
			$content = "lend";
			$title = "ใบส่งของ / ใบยืมสินค้า";
		break;

		case 7 :
			$content 	= "requisition";
			$title 		= "ใบส่งของ / ใบเบิกสินค้า";
		break;

		default :
			$content = "order";
			$title = "ใบส่งของ / ใบแจ้งหนี้";
		break;
	}

	return array("content"=>$content, "title"=>$title);
}






function get_header($order)
{
	$customer = new customer();
	$sale = new sale();

	//---	เบิกสปอนเซอร์
	if( $order->role == 4)
	{
		$header	= array(
				"ผู้รับ"      => $customer->getName($order->id_customer),
				"วันที่"      => thaiDate($order->date_add),
				"ผู้เบิก"      => employee_name($order->id_employee),
				"เลขที่เอกสาร" => $order->reference,
				"ผู้ดำเนินการ" =>  employee_name(get_id_user_sponsor($order->id_order))
			);
	}



	//---	ยิมสินค้า
	if($order->role == 6 )
	{
				$header		= array(
								"เลขที่เอกสาร"	=> $order->reference,
								"วันที่"	=> thaiDate($order->date_add),
								"ผู้ยืม"	=> employee_name($order->id_employee),
								"ผู้ทำรายการ" => employee_name(get_lend_user_id($order->id_order))
							);
	}


	//---	เบิก หรือ เบิกแปรสภาพ
	else if( $order->role == 5 || $order->role == 7 )
	{
		$header		= array(
									"ลูกค้า"	=> $customer->getName($order->id_customer),
									"วันที่"	=> thaiDate($order->date_add),
									"ผู้เบิก"	=> employee_name($order->id_employee),
									"เลขที่เอกสาร"	=> $order->reference,
									"เลขที่อ้างอิง"		=> getInvoice($order->id_order)
									);
	}

	//---	เบิกอภินันท์
	else if( $order->role == 3)
	{
		$header	= array(
									"ผู้รับ"	=> $customer->getName($order->id_customer),
									"วันที่"	=> thaiDate($order->date_add),
									"ผู้เบิก"	=> employee_name($order->id_employee),
									"เลขที่เอกสาร"	=> $order->reference,
									"ผู้ดำเนินการ" 	=> employee_name(get_id_user_support($order->id_order))
									);
	}

	else
	{
		$header	= array(
							"ลูกค้า"			=> $customer->getName($order->id_customer),
							"วันที่"			 => thaiDate($order->date_add),
							"พนักงานขาย" => $sale->getName($order->id_sale),
							"เลขที่เอกสาร" => $order->reference
							);
	}

	return $header;
}



function barcodeImage($barcode)
{
	return '<img src="'.WEB_ROOT.'library/class/barcode/barcode.php?text='.$barcode.'" style="height:8mm;" />';
}

 ?>
