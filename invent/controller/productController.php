<?php
require "../../library/config.php";
require"../../library/functions.php";
require "../function/tools.php";
require '../function/product_helper.php';
require '../function/product_group_helper.php';
require '../function/category_helper.php';
require '../function/kind_helper.php';
require '../function/type_helper.php';
require '../function/productTab_helper.php';


if( isset( $_GET['saveProduct'] ) )
{
	$sc = 'success';
	$id_style = $_POST['id_style']; 	
	$tabs		= $_POST['tabs']; //---- will be receive in array
	$pd = new product();
	$tab = new product_tab();
	$arr 	= array(
						"id_kind"		=> $_POST['pdKind'],
						"id_type"		=> $_POST['pdType'],
						"id_category"	=> $_POST['pdCategory'],
						"discount_amount"	=> $_POST['discountType'] == 'amount' ? $_POST['discount'] : 0.00,
						"discount_percent"	=> $_POST['discountType'] == 'percent' ? $_POST['discount'] : 0.00,
						"weight"		=> $_POST['weight'],
						"width"		=> $_POST['width'],
						"length"		=> $_POST['length'],
						"height"		=> $_POST['height'],
						"count_stock"	=> $_POST['isVisual'],
						"show_in_sale"	=> $_POST['inSale'],
						"show_in_customer"	=> $_POST['inCustomer'],
						"show_in_online"		=> $_POST['inOnline'],
						"can_sell"		=> $_POST['canSell'],
						"active"		=> $_POST['active'],
						"emp"			=> getCookie('user_id') //---- Who last edit products
						);
						
	$rs = $pd->updateProducts($id_style, $arr);						
	if( $rs === TRUE )
	{
		$pd->updateDescription($id_style, $_POST['description']);
		$tab->updateTabsProduct($id_style, $tabs);
	}
	else
	{
		$sc = 'บันทึกข้อมูลไม่สำเร็จ';
	}
	
	echo $sc;
	
}



if( isset( $_GET['saveItem'] ) )
{
	$sc = 'success';
	$id		= $_POST['id_pd'];
	$arr = array(
						"weight"	=> $_POST['weight'],
						"width"	=> $_POST['width'],
						"length"	=> $_POST['length'],
						"height"	=> $_POST['height']
						);
	$pd = new product();
	if( $pd->update($id, $arr) === FALSE )
	{
		$sc = 'บันทึกข้อมูลไม่สำเร็จ';
	}
	echo $sc;						
}

//------ Get Item Data for Edit
if( isset( $_GET['getItem'] ) )
{
	$id = $_GET['id']; //--- id product
	$pd = new product($id);
	if( $pd->id != '')
	{
		$arr = array(
							"id_pd"		=> $pd->id,
							"pdCode"		=> $pd->code,
							"weight"		=> $pd->weight,
							"width"		=> $pd->width,
							"length"		=> $pd->length,
							"height"		=> $pd->height
							);
		$sc = json_encode($arr);
	}
	else
	{
		$sc = 'ไม่พบข้อมูลที่ต้องการแก้ไข';
	}
	echo $sc;	
}



if( isset( $_GET['setShowInSale'] ) )
{
	$id = $_POST['id'];
	$pd = new product();
	$field = 'show_in_sale';
	$val = $pd->getStatus($id, $field) == 1 ? 0 : 1; //-- switch current value
	if( $pd->setStatus($id, $field, $val) === TRUE )	
	{
		$sc = isActived($val);
	}
	else
	{
		$sc = 'fail';	
	}
	echo $sc;
}



if( isset( $_GET['setShowInCustomer'] ) )
{
	$id = $_POST['id'];
	$pd = new product();
	$field = 'show_in_customer';
	$val = $pd->getStatus($id, $field) == 1 ? 0 : 1; //-- switch current value
	if( $pd->setStatus($id, $field, $val) === TRUE )	
	{
		$sc = isActived($val);
	}
	else
	{
		$sc = 'fail';	
	}
	echo $sc;
}


if( isset( $_GET['setShowInOnline'] ) )
{
	$id = $_POST['id'];
	$pd = new product();
	$field = 'show_in_online';
	$val = $pd->getStatus($id, $field) == 1 ? 0 : 1; //-- switch current value
	if( $pd->setStatus($id, $field, $val) === TRUE )	
	{
		$sc = isActived($val);
	}
	else
	{
		$sc = 'fail';	
	}
	echo $sc;
}




if( isset( $_GET['setCanSell'] ) )
{
	$id = $_POST['id'];
	$pd = new product();
	$field = 'can_sell';
	$val = $pd->getStatus($id, $field) == 1 ? 0 : 1; //-- switch current value
	if( $pd->setStatus($id, $field, $val) === TRUE )	
	{
		$sc = isActived($val);
	}
	else
	{
		$sc = 'fail';	
	}
	echo $sc;
}




if( isset( $_GET['setActive'] ) )
{
	$id = $_POST['id'];
	$pd = new product();
	$field = 'active';
	$val = $pd->getStatus($id, $field) == 1 ? 0 : 1; //-- switch current value
	if( $pd->setStatus($id, $field, $val) === TRUE )	
	{
		$sc = isActived($val);
	}
	else
	{
		$sc = 'fail';	
	}
	echo $sc;
}


if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('sProductCode');
	deleteCookie('sProductName');
	deleteCookie('sProductGroup');
	deleteCookie('sProductCategory');
	echo 'done';	
}

?>