<?php
	$sc 			= "สร้างออเดอร์ไม่สำเร็จ";
	$order 		= new order();

	//---	ถ้าเป็นออเดอร์ขายหรือสปอนเซอร์ จะมี id_customer
	$customer = isset( $_POST['id_customer']) ? new customer( $_POST['id_customer'] ) : FALSE;

	//---	ถ้าเป็นออเดอร์ขายหรือสปอนเซอร์ จะมี id_customer
	$id_customer = $customer === FALSE ? '' : $customer->id;

	//---	ถ้าเป็นออเดอร์ขาย จะมี id_sale
	$id_sale = $customer === FALSE ? '' : $customer->id_sale;

	//---	เป็นออเดอร์ออนไลน์หรือไม่
	$isOnline = isset($_POST['isOnline']) ? ($_POST['isOnline'] == 1 ? 1 : 0) : 0;

	//---	เป็นเอกสารประเภทไหน
	$role 		= isset( $_POST['role']) ? $_POST['role'] : 1;

	//---	หากเป็นออนไลน์ ลูกค้าออนไลน์ชื่ออะไร
	$customerName = isset( $_POST['customerName']) ? $_POST['customerName'] : '';

	//---	เป็นเอกสารที่ออก SO หรือไม่ (default = 1)
	$is_so 		= isset($_POST['is_so'])? $is_so : 1;

	//---	ช่องทางการขาย
	$id_channels = isset( $_POST['channels']) ? $_POST['channels'] : 0;

	//---	ช่องทางการชำระเงิน
	$id_payment = isset( $_POST['paymentMethod']) ? $_POST['paymentMethod'] : 0;

	//---	วันที่เอกสาร
	$date_add = dbDate($_POST['dateAdd']);

	//---	พนักงาน
	//---	กรณีออเดอร์ขาย คือคนที่ทำการสั่งด้วยตัวเองหน้าเว็บ
	//---	กรณีสปอนเซอร์จะเป็นชื่อคนสั่งให้เบิก
	$id_employee = isset($_POST['id_employee']) ? $_POST['id_employee'] : getCookie('user_id');

	//---	ผู้ทำรายการ
	//--- ผู้ทำรายการคือคนที่ทำงานทั้งสั่งเองและสั่งตามคำสั่งของคนอื่น
	//---	ผูํ้ทำรายการจะถูกบันทึกแยกไว้ที่ tbl_order_user
	$id_user = getCookie('user_id');

	//---	id_budget
	$id_budget = isset( $_POST['id_budget']) ? $_POST['id_budget'] : 0;

	//--- รันเลขที่เอกสารตามประเภทเอาสาร
	$reference = $order->getNewReference($role, $date_add);

	//--- เตรียมข้อมูลสำหรับเพิ่มเอกสารใหม่
	$arr = array(
					"bookcode"		=> getConfig('BOOKCODE_SO'),
					"reference"		=> $reference,
					"role"				=> $role,
					"id_customer"	=> $id_customer,
					"id_sale"			=> $id_sale,
					"id_employee"	=> $id_employee,
					"id_payment"	=> $id_payment,
					"id_channels"	=> $id_channels,
					"isOnline"		=> $isOnline,
					"date_add"		=> $date_add,
					"remark"			=> $_POST['remark'],
					"online_code"	=> $customerName,
					"is_so"				=> $is_so,
					"id_budget"		=> $id_budget
					);

	//---	เพิ่มเอกสารใหม่
	if( $order->add($arr) === TRUE )
	{
		$id = $order->get_id($reference);
		$sc = $id;
		//---	เพิ่มผู้ทำรายการแยกตางหาก
		$order->insertUser($id, $id_user);
	}

	echo $sc;
?>
