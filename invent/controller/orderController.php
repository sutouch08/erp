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




if( isset( $_GET['saveOrder'] ) )
{
	$order = new order();
	$rs = $order->changeStatus($_POST['id_order'], 1);
	if( $rs === TRUE )
	{
		//--- 1 = รอการชำระเงิน
		$order->stateChange($_POST['id_order'], 1);
	}
	echo ($rs === TRUE ) ? 'success' : 'Save order fail, Please try again';	
}


if( isset( $_GET['updateOrder'] ) )
{
	$id_order = $_POST['id_order'];
	$recal = isset( $_GET['recal'] ) ? $_GET['recal'] : 0;
	$order = new order();
	if( $recal == 0 )
	{
		$arr = array("remark" => $_POST['remark']);
		$rs = $order->update($id_order, $arr);
	}
	else
	{
		$customer = new customer($_POST['id_customer']);
		$arr = array(
						"date_add"	=> dbDate($_POST['date_add']),
						"id_customer"	=> $customer->id,
						"id_sale"		=> $customer->id_sale,
						"id_payment"	=> $_POST['id_payment'],
						"id_channels"	=> $_POST['id_channels'],
						"emp_upd"		=> getCookie('user_id'),
						"remark"		=> $_POST['remark']
						);
		//--- update order header first
		$rs = $order->update($id_order, $arr);
		
		//----- ถ้ายังไม่มีรายการ ไม่ต้องคำนวณใหม่	
		if( $rs === TRUE && $order->hasDetails($id_order) === TRUE )
		{
			//$order->recalculateDiscount($id_order, $arr); 	
		}			
	}
	
	echo $rs === TRUE ? 'success' : 'fail';
}

if( isset( $_GET['addToOrder'] ) )
{
	$ds 			= $_POST['qty'];
	$result 		= TRUE;
	$error 		= "";
	$err_qty		= 0;
	
	if( count($ds) > 0 ){
		$order	= new order($_POST['id_order']);
		$stock 	= new stock();
		$disc		= new discount();
		
		startTransection();
		
		foreach( $ds as $items )
		{
			foreach( $items as $id => $qty )
			{
				if( $qty > 0 )
				{
					//--- ถ้ามีสต็อกมากว่าที่สั่ง
					if( $stock->getSellStock($id) >= $qty )
					{
						$pd 			= new product($id);
						
						//---- ถ้ายังไม่มีรายการในออเดอร์
						if( $order->isExistsDetail($order->id, $id) === FALSE )
						{
							//---- คำนวณ ส่วนลดจากนโยบายส่วนลด
							$discount 	= $disc->getItemDiscount($pd->id, $order->id_customer, $qty, $order->id_payment, $order->id_channels);
							
							$arr = array(
											"id_order"	=> $order->id,
											"id_style"		=> $pd->id_style,
											"id_product"	=> $id,
											"product_code"	=> $pd->code,
											"product_name"	=> $pd->name,
											"price"	=> $pd->price,
											"qty"		=> $qty,
											"discount"	=> $discount['discount'],
											"discount_amount" => $discount['amount'],
											"total_amount"	=> ($pd->price * $qty) - $discount['amount'],
											"id_rule"	=> $discount['id_rule']										
										);
										
							if( $order->addDetail($arr) === FALSE )
							{
								$result = FALSE;	
								$error = "Error : Insert fail";
								$err_qty++;
							}
							
						}
						else  //--- ถ้ามีรายการในออเดอร์อยู่แล้ว
						{
							$detail 		= $order->getDetail($order->id, $id);
							$qty			= $qty + $detail->qty;
							$discount 	= $disc->getItemDiscount($pd->id, $order->id_customer, $qty, $order->id_payment, $order->id_channels);
							$arr = array(
												"id_product"	=> $id,
												"qty" => $qty,
												"discount"	=> $discount['discount'],
												"discount_amount"	=> $discount['amount'],
												"total_amount"	=> ($pd->price * $qty) - $discount['amount'],
												"id_rule"	=> $discount['id_rule']
												);
							if( $order->updateDetail($detail->id, $arr) === FALSE )
							{
								$result = FALSE;
								$error = "Error : Update Fail";
								$err_qty++;
							}												
												
						}	//--- end if isExistsDetail
					}
					else 	// if getStock
					{
						$error = "Error : สินค้าไม่เพียงพอ";
					} 	//--- if getStock
				}	//--- if qty > 0
			}	//-- foreach items
		}	//--- foreach ds
		
		if( $result === TRUE )
		{
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
	$sc = $grid->getOrderGrid($id_style);
	$tableWidth	= $pd->countAttribute($id_style) == 1 ? 600 : $grid->getOrderTableWidth($id_style);
	$sc .= ' | '.$tableWidth;
	$sc .= ' | ' . $style->getCode($id_style);
	$sc .= ' | ' . $id_style;
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




if( isset( $_GET['getDetailTable'] ) )
{
	$sc = "no data found";
	$id_order	= $_GET['id_order'];
	$order 		= new order();
	$qs  			= $order->getDetails($id_order);
	if( dbNumRows($qs) > 0 )
	{
		$no = 1;
		$total_qty = 0;
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
							"discount"	=> $rs->discount,
							"amount"	=> number_format($rs->total_amount, 2)
							);
			array_push($ds, $arr);
			$total_qty += $rs->qty;
			$no++;
		}
		$arr = array("total" => number_format($total_qty)); 
		array_push($ds, $arr);
		$sc = json_encode($ds);
	}
	echo $sc;
}

//----- Delete detail row
if( isset( $_GET['removeDetail'] ) )
{
	$id 	= $_POST['id_order_detail'];
	$order = new order();
	$rs = $order->deleteDetail($id);
	echo $rs === TRUE ? 'success' : 'Can not delete please try again';		
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
