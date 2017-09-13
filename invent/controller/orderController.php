<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";
include '../function/order_helper.php';
include '../function/customer_helper.php';

if( isset( $_GET['addNew'] ) )
{
	$sc = "สร้างออเดอร์ไม่สำเร็จ";
	$order = new order();
	$customer = new customer( $_POST['id_customer'] );
	$reference = $order->getNewReference();
	$arr = array(
					"bookcode"		=> getConfig('BOOKCODE_SO'),
					"reference"		=> $reference,
					"id_customer"	=> $_POST['id_customer'],
					"id_sale"			=> $customer->id_sale,
					"id_employee"	=> getCookie('user_id'),
					"id_payment"	=> $_POST['paymentMethod'],
					"id_channels"	=> $_POST['channels'],
					"date_add"		=> dbDate($_POST['dateAdd']),
					"remark"			=> $_POST['remark']
					);
	if( $order->add($arr) === TRUE )
	{
		$id = $order->get_id($reference);
		$sc = $id;
	}
	
	echo $sc;	
}




if( isset( $_GET['addToOrder'] ) )
{
	$id_order = $_POST['id_order'];
	$ds = $_POST['qty'];
	$result = TRUE;
	
	if( count($ds) > 0 ){
		$order = new order();
		$pd = new product();
		$stock = new stock();
		
		startTransection();
		
		foreach( $ds as $items )
		{
			foreach( $items as $id => $qty )
			{
				if( $qty > 0 )
				{
					if( $stock->getSellStock($id) >= $qty )
					{
						$arr = array(
						
									);
					}
				}
			}
		}
		
		
	}//--- if count
}


//------- Attribute Grid By Search box
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
		$sc .= ' | '.$tableWidth;
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
	$sc = $grid->getOrderGrid($id_style);
	$tableWidth	= $pd->countAttribute($id_style) == 1 ? 600 : $grid->getOrderTableWidth($id_style);
	$sc .= ' | '.$tableWidth;
	$sc .= ' | ' . $style->getCode($id_style);
	echo $sc;
}


//----- Echo product style list in tab
if( isset( $_GET['getProductsInOrderTab'] ) )
{
	$ds = "";
	$id_tab = $_POST['id'];
	$cs = new product_tab();
	$pd = new product();
	$img = new image();
	$stock = new stock();
	$qs = $cs->getStyleInTab($id_tab);
	if( dbNumRows($qs) > 0 )
	{
		while( $rs = dbFetchObject($qs) )
		{
			$style = new style($rs->id_style);
			if( $style->active == 1 && $pd->isDisactiveAll($rs->id_style) === FALSE)
			{
				$ds 	.= 	'<div class="col-lg-2 col-md-2 col-sm-3 col-xs-4"	style="text-align:center;">';
				$ds 	.= 		'<div class="product" style="padding:5px;">';
				$ds 	.= 			'<div class="image">';
				$ds 	.= 				'<a href="javascript:void(0)" onClick="getOrderGrid('.$rs->id_style.')">';
				$ds 	.=					'<img class="img-responsive" src="'.$img->getImagePath($img->getCover($rs->id_style), 2).'" />';
				$ds 	.= 				'</a>';
				$ds	.= 			'</div>';
				$ds	.= 			'<div class="description" style="font-size:10px; min-height:50px;">';
				$ds	.= 				'<a href="javascript:void(0)" onClick="getOrderGrid('.$rs->id_style.')">';
				$ds	.= 			$style->code.'<br/>'. number_format($pd->getStylePrice($rs->id_style),2);
				$ds 	.=  		$pd->isCountStock($rs->id_style) === TRUE ? ' | <span style="color:red;">'.$pd->getStyleSellStock($rs->id_style).'</span>' : '';
				$ds	.= 				'</a>';
				$ds 	.= 			'</div>';
				$ds	.= 		'</div>';
				$ds 	.=	'</div>';
			}
		}
	}
	else
	{
		$ds = "no_product";
	}
	
	echo $ds;
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


	
?>
