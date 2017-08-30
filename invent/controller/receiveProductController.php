<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";

if( isset( $_GET['addNew'] ) )
{
	$date_add	= dbDate( $_POST['date_add'] );
	$po			= trim( $_POST['po'] );
	$invoice		= trim( $_POST['invoice'] );
	$remark		= trim( $_POST['remark'] );
	$bookcode 	= getConfig('BOOKCODE_BI');
	$ps 			= new po();
	if( $ps->hasPO($po) === TRUE )
	{
		$cs 			= new receive_product();
		
		$reference	= $cs->getNewReference($date_add);
	
		$arr = array( 
							"bookcode"	=> $bookcode,
							"reference"	=> $reference,
							"po" 			=> $po, 
							"invoice" 		=> $invoice, 
							"date_add" 	=> $date_add, 
							"id_employee" => getCookie('user_id'),
							"remark"		=> $remark
						);
						
		$rs = $cs->add($arr);
		
		if( $rs === TRUE )
		{
			$sc = 'success | '.$cs->get_id($reference);	
		}
		else
		{
			$sc = 'fail | เพิ่มเอกสารไม่สำเร็จ';
		}	
	}
	else
	{
		$sc = 'fail | ใบสั่งซื้อไม่ถูกต้อง';	
	}
	echo $sc;
}



if( isset( $_GET['search_po'] ) && isset( $_REQUEST['term'] ) )
{
	$sc = array();
	$qs = dbQuery("SELECT DISTINCT reference FROM tbl_po WHERE reference LIKE '%".$_REQUEST['term']."%' AND status != 3 AND isCancle = 0");
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
	deleteCookie('sFrom');
	deleteCookie('sTo');
	echo 'done';	
}

?>