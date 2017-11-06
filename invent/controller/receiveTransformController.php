<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";
include "../function/receive_transform_helper.php";


if( isset( $_GET['getPoData'] ) )
{
	include 'receive_transform/data.php';	
}






if( isset( $_GET['addNew'] ) )
{
	include 'receive_transform/add_new.php';
}



if( isset( $_GET['cancleReceived'] ) )
{
	include 'receive_transform/cancle.php';
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




if( isset( $_GET['search_transform'] ) && isset( $_REQUEST['term'] ) )
{
	$sc = array();
	$cs = new transform();
	$is_closed = 0; //---	ยังไม่ปิด
	$qs = $cs->searchReference($_REQUEST['term'], $is_closed);
	if(dbNumRows($qs) > 0)
	while( $rs = dbFetchObject($qs) )
	{
		$sc[] = $rs->reference.' | '.$rs->id;
	}
	else
	{
		$sc[] = 'ไม่พบรายการ';
	}

	echo json_encode($sc);
}



if( isset( $_GET['search_zone'] ) && isset( $_REQUEST['term'] ) )
{
	$sc 	= array();
	$zone = new zone();
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
