<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";
include '../function/order_helper.php';
include '../function/customer_helper.php';

//---- เพิ่มเลขที่เอกสารใหม่
if( isset( $_GET['addNew'] ) )
{
	include 'order/new_order.php';
}


//----- 	บันทึกเอกสารเปลี่ยนสถานะ เป็นบันทึกแล้ว
//-----	ถ้ามีเครดิตเทอม จะตัดยอดเครดิตด้วย
if( isset( $_GET['saveOrder'] ) )
{
	include 'order/save_order.php';
}



//----- 	แก้ไขหัวเอกสาร 
//------ 	ถ้ามีการเปลี่ยนวันที่ /ชื่อลูกค้า /ช่องทางการชำระเงิน /ช่องทางการขาย จะทำการคำนวณส่วนลดใหม่
//------	ถ้าการชำระเงินมีเครดิตเทอม จะคืนยอดใช้ไปก่อน แล้วค่อยบันทึกเพื่อตัดยอดเครดิตอีกที
if( isset( $_GET['updateOrder'] ) )
{
	include 'order/update_order.php';
}



//----- แก้ไขส่วนลดโดยพนักงาน และมีผู้อนุมัติการแก้ไข
if( isset( $_GET['updateEditDiscount'] ) )
{
	include 'order/edit_discount.php';
}


//---- แก้ไขราคาสินค้า โดยพนักงาน และมีผู้อนุมัติการแก้ไข
if( isset( $_GET['updateEditPrice'] ) )
{
	include 'order/edit_price.php';	
}


//----		เพิ่มรายการสินค้าเข้าออเดอร์พร้อมคำนวณส่วนลดจากนโยบายส่วนลด
if( isset( $_GET['addToOrder'] ) )
{
	include 'order/add_detail.php';
}



//----- Delete detail row
if( isset( $_GET['removeDetail'] ) )
{
	include 'order/delete_detail.php';
}


//----		Attribute Grid By Search box
if( isset( $_GET['getProductGrid'] ) && isset( $_GET['pdCode'] ) )
{
	$sc = 'not exists';
	$pdCode = trim($_GET['pdCode']);
	$qr = "SELECT s.code FROM tbl_product AS p JOIN tbl_product_style AS s ON p.id_style = s.id ";
	$qr .= "WHERE p.active = 1 AND p.can_sell = 1 AND is_deleted = 0 GROUP BY p.id_style";
	$qs = dbQuery($qr);
	if( dbNumRows($qs) > 0 )
	{
		$pd = new product();
		$grid = new product_grid();
		$style = new style();
		$id_style = $style->getId($pdCode);
		$sc = $grid->getOrderGrid($id_style);
		$tableWidth	= $pd->countAttribute($id_style) == 1 ? 800 : $grid->getOrderTableWidth($id_style);
		$sc .= ' | ' . $tableWidth;
		$sc .= ' | ' . $id_style;
	}
	echo $sc;
}


//----- Attribute Grid By Clicking image
if( isset( $_GET['getOrderGrid'] ) && isset( $_GET['id_style'] ) )
{
	$sc = 'not exists';
	$id_style = $_GET['id_style'];
	$pd = new product();
	$grid = new product_grid();
	$style = new style();
	$view = FALSE; //----- View stock ? TRUE = view stock only FALSE = order
	$sc = $grid->getOrderGrid($id_style, $view);
	$tableWidth	= $pd->countAttribute($id_style) == 1 ? 600 : $grid->getOrderTableWidth($id_style);
	$sc .= ' | '.$tableWidth;
	$sc .= ' | ' . $style->getCode($id_style);
	$sc .= ' | ' . $id_style;
	echo $sc;
}




//----- Attribute Grid By Clicking image
if( isset( $_GET['getStockGrid'] ) && isset( $_GET['id_style'] ) )
{
	$sc = 'not exists';
	$id_style = $_GET['id_style'];
	$pd = new product();
	$grid = new product_grid();
	$style = new style();
	$view = TRUE;  //--- view stock
	$sc = $grid->getOrderGrid($id_style, $view);
	$tableWidth	= $pd->countAttribute($id_style) == 1 ? 600 : $grid->getOrderTableWidth($id_style);
	$sc .= ' | '.$tableWidth;
	$sc .= ' | ' . $style->getCode($id_style);
	$sc .= ' | ' . $id_style;
	echo $sc;
}


//----- Echo product style list in tab
if( isset( $_GET['getProductsInOrderTab'] ) )
{
	include 'order/product_tab.php';
}




//----- Echo product style list in tab
if( isset( $_GET['getProductsInViewTab'] ) )
{
	include 'order/stock_tab.php';
}



//----- Echo Order detail list 
if( isset( $_GET['getDetailTable'] ) )
{
	include 'order/detail_table.php';
}





if( isset( $_GET['getCustomer'] ) && isset( $_REQUEST['term'] ) )
{
	$sc = array();
	$cs = new customer();
	$qs = $cs->search($_REQUEST['term'], "id, code, name");
	while( $rs = dbFetchObject($qs) )
	{
		$sc[] = $rs->code .' | '. $rs->name .' | '. $rs->id;
	}
	echo json_encode($sc);
}



if( isset( $_GET['getCustomerOnline'] ) && isset( $_REQUEST['term'] ) )
{
	$sc = array();
	$cs = new order();
	$qs = $cs->searchOnlineCode($_REQUEST['term'] );
	while( $rs = dbFetchObject($qs) )
	{
		$sc[] = $rs->online_code;
	}
	echo json_encode($sc);
}


if( isset( $_GET['searchProducts'] ) && isset( $_REQUEST['term'] ) )
{
	$sc = array();
	$qr = "SELECT s.code FROM tbl_product AS p JOIN tbl_product_style AS s ON p.id_style = s.id ";
	$qr .= "WHERE s.code LIKE '%".$_REQUEST['term']."%' AND p.active = 1 AND p.is_deleted = 0 AND p.can_sell = 1 ";
	$qr .= "GROUP BY p.id_style";
	$qs = dbQuery($qr);
	while( $rs = dbFetchObject($qs) )
	{
		$sc[] = $rs->code;
	}
	echo json_encode($sc);
}



//------------------------- แจ้งโอนเงินพร้อมแนบไฟล์หลักฐาน  ------------//
if( isset( $_GET['confirmPayment'] ) )
{
	require "../function/bank_helper.php";
	$sc 			= 'fail';
	$file 			= isset( $_FILES['image'] ) ? $_FILES['image'] : FALSE;
	$id_order 		= $_POST['id_order'];
	$id_acc			= $_POST['id_account'];
	$accNo			= getAccountNo($id_acc);
	$date			= $_POST['payDate'];
	$h				= $_POST['payHour'];
	$m				= $_POST['payMin'];
	$dhm			= $date .' '.$h.':'.$m.':00';
	$payDate		= dbDate($dhm, TRUE);
	$date_add 		= date('Y-m-d H:i:s');
	$order			= new order($id_order);
	
	$id_emp			= getCookie('user_id');
	//-------  บันทึกรายการ -----//
	$payment = new payment(); 
	$arr = array(
				"id_order"	=> $order->id,
				"order_amount"	=> $_POST['orderAmount'],
				"pay_amount"	=> $_POST['payAmount'],
				"paydate"	=> $payDate,
				"id_account"	=> $id_acc,
				"acc_no"		=> $accNo,
				"id_employee"	=> $id_emp,
				"date_add"	=> $date_add
			);
	
	if( $payment->isExists($order->id) === FALSE )
	{
		$cs = $payment->add($arr);
	}
	else
	{
		$cs = $payment->update($order->id, $arr);	
	}
	
	if( $cs )
	{
		$order->stateChange($order->id, 2); //--- แจ้งชำระเงิน
		$sc = 'success';
	}
	
	//----- Upload image -----//	
	if( $file !== FALSE )
	{	
		$image_path 	= "../../img/payment/";
		$image 			= new upload($file);
		if($image->uploaded)
		{
			$image->file_new_name_body	= $order->reference;
			$image->file_overwrite 			= TRUE;
			$image->auto_create_dir 		= FALSE;
			$image->image_convert 			= "jpg";
			$image->process($image_path);
			if( ! $image->processed)
			{
				$sc = $image->error;
			}
		}
		$image->clean();
	}
	echo $sc;
}



if( isset( $_GET['updateShippingFee'] ) )
{
	$sc 		= 'fail';
	$amount 	= $_POST['fee'];
	$id			= $_POST['id_order'];
	$order	= new order();
	$arr 		= array("shipping_fee" => $amount);
	if( $order->update($id, $arr) )
	{
		$sc = 'success';
	}
	echo $sc;
}



if( isset( $_GET['updateServiceFee'] ) )
{
	$sc = 'fail';
	$amount	= $_POST['fee'];
	$id			= $_POST['id_order'];
	$order	= new order();	
	$arr		= array("service_fee" => $amount);
	if( $order->update($id, $arr) )
	{
		$sc = 'success';
	}
	echo $sc;
}


if( isset( $_GET['getSummary'] ) )
{
	include 'order/order_summary.php';
}


if( isset( $_GET['getPayAmount'] ) )
{
	$order = new order($_GET['id_order']);
	$amount = $order->getTotalAmount($order->id);
	$sc = ( $amount + $order->shipping_fee + $order->service_fee ) - $order->bDiscAmount;
	echo $sc;
}





//------------------ return address Table  ---------------//
if( isset( $_GET['getAddressTable'] ) )
{
	$sc 			= 'fail';
	$id_order	= $_POST['id_order'];
	$order 		= new order();
	$code 		= $order->getOnlineCode($id_order);
	if( $code )
	{
		$ds = array();
		$ad = new online_address();
		$qs = $ad->getAddressByCode($code);
		if( dbNumRows($qs) > 0 )
		{
			while( $data = dbFetchArray($qs) )
			{
				$arr	= array( 
							'id'			=> $data['id'],
							'name'		=> $data['first_name'].' '.$data['last_name'],
							'address'	=> $data['address1'].' '.$data['address2'].' '.$data['province'].' '.$data['postcode'],
							'phone'	=> $data['phone'],
							'email'		=> $data['email'],
							'alias'		=> $data['alias'],
							'default'	=> $data['is_default'] == 1 ? 1 : ''
							);
				array_push($ds, $arr);
			}
			$sc = json_encode($ds);
		}
	}
	echo $sc;	
}






if( isset( $_GET['getAddressDetail']) )
{
	$sc = 'fail';
	$id = $_POST['id_address'];
	$ad = new online_address();
	$qs = $ad->getAddress($id);
	if( dbNumRows($qs) == 1 )
	{
		$rs = dbFetchObject($qs);
		$ds = array(
					"id"		=> $rs->id,
					"customer_code"	=> $rs->customer_code,
					"first_name"	=> $rs->first_name,
					"last_name"	=> $rs->last_name,
					"address1"	=> $rs->address1,
					"address2"	=> $rs->address2,
					"province"	=> $rs->province,
					"postcode"	=> $rs->postcode,
					"phone"		=> $rs->phone,
					"email"			=> $rs->email,
					"alias"			=> $rs->alias,
					"is_default"	=> $rs->is_default
				);
					
		$sc = json_encode($ds);
	}
	echo $sc;
}





if( isset( $_GET['saveAddress'] ) )
{
	$id = $_POST['id_address'];
	$ad = new online_address();
	if( $id != "" )
	{
		$arr = array(
					"first_name"	=> $_POST['first_name'],
					"last_name"	=> $_POST['last_name'],
					"address1"	=> $_POST['address1'],
					"address2"	=> $_POST['address2'],
					"province"	=> $_POST['province'],
					"postcode"	=> $_POST['postcode'],
					"phone"		=> $_POST['phone'],
					"email"			=> $_POST['email'],
					"alias"			=> $_POST['alias']
				);
		$rs = $ad->update($id, $arr);
	}
	else
	{
		$arr = array(
					"customer_code"	=> $_POST['online_code'],
					"first_name"	=> $_POST['first_name'],
					"last_name"	=> $_POST['last_name'],
					"address1"	=> $_POST['address1'],
					"address2"	=> $_POST['address2'],
					"province"	=> $_POST['province'],
					"postcode"	=> $_POST['postcode'],
					"phone"		=> $_POST['phone'],
					"email"			=> $_POST['email'],
					"alias"			=> $_POST['alias']
				);
		$rs = $ad->add($arr);
	}
	echo $rs === TRUE ? 'success' : 'fail';
}



//--------------------- Delete Online Address  ---------------//
if( isset( $_GET['removeAddress'] ) )
{
	$id = $_POST['id_address'];
	$ad	= new online_address();
	echo $ad->delete($id) === TRUE ? 'success' : 'fail';
}




if( isset( $_GET['setDefaultAddress'] ) )
{
	$id = $_POST['id_address'];
	$add = new online_address();
	$sc = $add->setDefault($id);
	echo $sc === TRUE ? 'success' : 'fail';		
}



if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('sOrderCode');
	deleteCookie('sOrderCus');
	deleteCookie('sOrderEmp');
	deleteCookie('fromDate');
	deleteCookie('toDate');
	echo "done";	
}

	
?>
