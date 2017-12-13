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
	include 'receive_product/receive_add.php';
}



if( isset( $_GET['cancleReceived'] ) )
{
	include 'receive_product/receive_cancle.php';
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
	$sc 	= array();
	$zone = new zone();
	$role = '5';
	$qs 	= $zone->searchReceiveZone($_REQUEST['term']);

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
