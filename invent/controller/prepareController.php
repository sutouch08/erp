<?php
require_once "../../library/config.php";
require_once "../../library/functions.php";
require_once "../function/tools.php";
require_once "../function/prepare_helper.php";
include_once "../function/warehouse_helper.php";



//--	ตรวจสอบสภานะของออเดอร์
if( isset($_GET['checkStatusAndState']))
{
	$sc = '';
	$id_order = $_GET['id_order'];
	$order = new order();
	$qs = $order->getStatusAndState($id_order);
	if( dbNumRows($qs) == 1)
	{
		$rs = dbFetchObject($qs);
		$sc = json_encode(array('status' => $rs->status, 'state' => $rs->state));
	}

	echo $sc;
}



if(isset($_GET['pullOrderBack']))
{
	$sc = TRUE;
	$id = $_POST['id_order'];
	$order = new order($id);

	//---- 	ถ้าสถานะเป็น กำลังจัดสินค้าอยูท
	if($order->state == 4)
	{
		//---	เปลี่ยนสถานะกลับมารอจัดใหม่
		if($order->stateChange($id, 3) === FALSE)
		{
			$sc = FALSE;
			$message = 'เปลี่ยนสถานะไม่สำเร็จ';
		}
	}

	echo $sc === TRUE ? 'success' : $message;

}


//--- จัดสินค้า ตัดยอดออกจากโซน เพิ่มเข้า buffer
if( isset( $_GET['doPrepare']))
{
	include 'prepare/prepare_product.php';
}



if( isset( $_GET['finishPrepare'] ) )
{
	include 'prepare/prepare_finish.php';
}




//--- เมื่อยิงบาร์โค้ดโซนเพื่อจัดสินค้า
if( isset( $_GET['getZoneId'] ) )
{
	$sc = 'ไม่พบโซน หรือ บาร์โค้ดไม่ถูกต้อง';
	$zone = new zone();
	$id = $zone->getId($_GET['barcode']);
	if( $id !== FALSE)
	{
		$sc = $id;
	}

	echo $sc;
}





//--- Clear Filter
if( isset( $_GET['clearFilter']))
{
	deleteCookie('sOrderCode');
	deleteCookie('sOrderCus');
	deleteCookie('sOrderEmp');
	deleteCookie('fromDate');
	deleteCookie('toDate');
	echo 'done';
}


//--- Set Cookie show stock in zone or not
if( isset($_GET['setZoneLabel']))
{
	createCookie('showZone', $_GET['showZone']);
	echo 'done';
}

?>
