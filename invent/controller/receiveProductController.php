<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";
include "../function/receive_product_helper.php";


if( isset( $_GET['getPoData'] ) )
{
	$reference = $_GET['reference']; // po
	$po 			= new po();
	$qs 			= $po->getDetail($reference);
	if( dbNumRows($qs) > 0 )
	{
		$ds = array();
		$no = 1;
		$totalQty	= 0;
		$totalBacklog = 0;
		$pd = new product();
		$bc = new barcode();
		$limit = getConfig('RECEIVE_OVER_PO');
		while( $rs = dbFetchObject($qs) )
		{
			$backlog = $rs->qty - $rs->received < 0 ? 0 : $rs->qty - $rs->received;
			$arr = array(
								"no"	=> $no,
								"barcode"	=> $bc->getBarcode($rs->id_product),
								"id_pd"		=> $rs->id_product,
								"pdCode"		=> $pd->getCode($rs->id_product),
								"pdName"		=> $pd->getName($rs->id_product),
								"qty"			=> number_format($rs->qty),
								"limit"			=> ($rs->qty + ($rs->qty* ( $limit * 0.01 ) ) ) - $rs->received,
								"backlog"		=> number_format($backlog)
							);
			array_push($ds, $arr);
			$totalQty += $rs->qty;
			$totalBacklog += $backlog;
			$no++;
		}
		$arr = array(
							"qty"			=> number_format($totalQty),
							"backlog"		=> number_format($totalBacklog)
						);
		array_push($ds, $arr);
		$sc = json_encode($ds);
	}
	else
	{
		$sc = 'ใบสั่งซื้อไม่ถูกต้อง ใบสั่งซื้ออาจถูกปิด หรือ ถูกยกเลิกไปแล้ว';
	}
	echo $sc;
}



if( isset( $_GET['addNew'] ) )
{
	$po 			= new po();
	$pd 			= new product();
	$zn			= new zone();
	$cs 			= new receive_product();
	$st			= new stock();
	$mv			= new movement();
	$result		= TRUE;
	$sc 			= 'success';
	$poCode		= trim( $_GET['po'] );
	if( $po->hasPO($poCode) === TRUE )
	{
		$date_add	= dbDate( $_GET['date'], TRUE );
		$id_supplier	= $po->getSupplierId($poCode);
		$invoice		= trim( $_GET['invoice'] );
		$remark		= trim( $_GET['remark'] );
		$id_zone		= $_GET['id_zone'];
		$id_wh		= $zn->getWarehouseId($id_zone);
		$bookcode 	= getConfig('BOOKCODE_BI');
		$ds			= $_POST['receive'];
		$data			= array();
		foreach( $ds as $id => $val )
		{
			if( is_numeric($val) )
			{
				$data[$id]	= $val;	
			}
		}
		if( count( $data ) > 0 )
		{
			startTransection();
			$reference	= $cs->getNewReference($date_add);
			if( $_POST['approvKey'] == "" )
			{
				$arr = array( 
										"bookcode"	=> $bookcode,
										"reference"	=> $reference,
										"id_supplier"	=> $id_supplier,
										"po" 			=> $poCode, 
										"invoice" 		=> $invoice, 
										"date_add" 	=> $date_add, 
										"id_employee" => getCookie('user_id'),
										"remark"		=> $remark
									);
			}
			else
			{
				$arr = array( 
										"bookcode"	=> $bookcode,
										"reference"	=> $reference,
										"id_supplier"	=> $id_supplier,
										"po" 			=> $poCode, 
										"invoice" 		=> $invoice, 
										"date_add" 	=> $date_add, 
										"id_employee" => getCookie('user_id'),
										"remark"		=> $remark,
										"approver"	=> $_POST['id_emp'],
										"approvKey"	=> $_POST['approvKey']
									);
			}
							
				$rs = $cs->add($arr);
				
				if( $rs == TRUE )
				{
					$id_receive_product = $cs->get_id($reference);	
					if( $id_receive_product != FALSE )
					{
						foreach( $data as $id_pd => $qty )
						{
							$arr = array(
											"id_receive_product"	=> $id_receive_product,
											"id_style"					=> $pd->getStyleId($id_pd),
											"id_product"				=> $id_pd,
											"qty"						=> $qty,
											"id_warehouse"			=> $id_wh,
											"id_zone"					=> $id_zone
											);
							//------ เพิ่มรายการรับเข้า				
							$rd = $cs->insertDetail($arr);
							
							//------ ปรับยอดสต็อก
							$ra = $st->updateStockZone($id_zone, $id_pd, $qty);
							
							//---- บันทึก movement
							$rm = $mv->move_in( $reference, $id_wh, $id_zone, $id_pd, $qty, dbDate($_GET['date'], TRUE) );
							
							//--- บันทึกยอดรับใน PO
							$ro = $po->received($poCode, $id_pd, $qty);
							
							if( $rd !== TRUE OR $ra !== TRUE OR $rm !== TRUE OR $ro !== TRUE )
							{
								//--- ถ้ามีขั้นตอนใดไม่สำเร็จ
								$result = FALSE;	
							}
							
						}//--- foreach data				
							
					}
					else
					{
						$result = FALSE;	
					}
					
					if( $result === TRUE )
					{
						commitTransection();
					}
					else
					{
						dbRollback();	
					}
					
				}
				else
				{
					$sc = 'fail | เพิ่มเอกสารไม่สำเร็จ';
				}	
				
				endTransection();
		}
		else //-- if count
		{
			$sc = "ไม่พบรายการรับเข้า";
		}//--- if count
	}
	else //---- if hasPO
	{
		$sc = "ใบสั่งซื้อไม่ถูกต้อง ถูกปิด หรือ ถูกยกเลิก";	
	}//--- if hasPO
	echo $sc = $result === TRUE ? 'success | '.$id_receive_product : 'fail | '.$sc;
}



if( isset( $_GET['cancleReceived'] ) )
{
	$sc = 'success';
	$result = TRUE;
	$id = $_POST['id_receive_product'];
	$cs = new receive_product($id);
	$mv = new movement();
	$stock = new stock();
	$po = new po();
	$emp = getCookie('user_id');
	//--- check if stock enough
	if( isStockEnough($id) === TRUE )
	{
		$reference = $cs->reference;
		$qs = $cs->getDetail($id);
		startTransection();
		
		while( $rs = dbFetchObject($qs) )
		{
			//--- update stock
			if( $stock->updateStockZone($rs->id_zone, $rs->id_product, $rs->qty * -1) === TRUE )
			{
				//--- remove movement
				$rb = $mv->removeMovement($reference, $rs->id_product);
				
				//--- update received in po
				$rc = $po->unReceived($cs->po, $rs->id_product, $rs->qty);
				
				//--- cancle receive detail
				$rd = $cs->cancleDetail($rs->id);
				
				if( $rb === FALSE OR $rc === FALSE OR $rd === FALSE )
				{
					$result = FALSE;
					/*
					if( $rb === FALSE ){ $sc = 'movement'; }
					if( $rc === FALSE ){ $sc = 'unReceived'; }
					if( $rd === FALSE ){ $sc = 'cancleDetail'; }
					*/
					$sc = 'ยกเลิกรายการไม่สำเร็จ';
				}
			}
			else
			{
				$result = FALSE;
				$sc = $stock->error;
			}
		}//-- End while
				
		if( $result === TRUE )
		{
			$re = $cs->cancleReceived($id, $emp);
			$rf = $po->setStatus($cs->po);
			if( $re === FALSE OR $rf === FALSE )
			{
				$sc = "ยกเลิกรายการไม่สำเร็จ2";
				
				$result = FALSE;
			}
		}
		
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
	else
	{
		$sc = "สินค้าคงเหลือไม่พอให้ยกเลิก";
	}//--- if isStockEnough
	
	echo $sc;
	
}

if( isset( $_GET['getApprove'] ) )
{
	$sKey = $_GET['sKey'];
	$id_tab = 49; //---- อนุมัติรับสินค้าเกินใบสั่งซื้อ
	$valid = new validate();
	$rs = $valid->getApproveCode($id_tab, $sKey);
	
	if( $rs !== FALSE )
	{
		echo $rs;
	}
	else
	{
		echo 'fail';
	}
}

if( isset($_GET['search_supplier'] ) && isset( $_REQUEST['term'] ) )
{
	$sc = array();
	$sp = new supplier();
	$qs = $sp->search($_REQUEST['term']);
	if( dbNumRows($qs) > 0 )
	{
		while( $rs = dbFetchObject($qs) )
		{
			$sc[] = $rs->code.' : ' . $rs->name .' | '. $rs->id;	
		}
	}
	echo json_encode($sc);
}


if( isset( $_GET['search_po'] ) && isset( $_REQUEST['term'] ) )
{
	$sc = array();
	$po = new po();
	$qs = $po->search($_REQUEST['term']);
	while( $rs = dbFetchObject($qs) )
	{
		$sc[] = $rs->reference;
	}
	echo json_encode($sc);
}



if( isset( $_GET['search_zone'] ) && isset( $_REQUEST['term'] ) )
{
	$sc = array();
	$qr = "SELECT id_zone, zone_name FROM ";
	$qr .= "tbl_zone JOIN tbl_warehouse ON tbl_zone.id_warehouse = tbl_warehouse.id ";
	$qr .= "WHERE role = 5 AND zone_name LIKE '%".$_REQUEST['term']."%'";
	$qs = dbQuery($qr);
	if( dbNumRows($qs) > 0 )
	{
		while( $rs = dbFetchObject($qs) )
		{
			$sc[] = $rs->zone_name.' | '.$rs->id_zone;
		}
	}
	else
	{
		$sc[] = "ไม่พบโซน";
	}
	echo json_encode($sc);
}


if( isset( $_GET['isExistsDetails'] ) )
{
	$id = $_GET['id_receive_product'];
	$cs = new receive_product();	
	if( $cs->hasDetails($id) === TRUE )
	{
		echo "has_details";
	}
	else
	{
		echo "no_details";
	}
}

if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('sReceiveCode');
	deleteCookie('sReceivePo');
	deleteCookie('sReceiveInv');
	deleteCookie('sReceiveSup');
	deleteCookie('sFrom');
	deleteCookie('sTo');
	deleteCookie('sReceiveStatus');
	echo 'done';	
}

?>