<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";
include "../function/receive_transform_helper.php";


if( isset( $_GET['getPoData'] ) )
{
	$cs 			= new transform();
	$qs 			= $cs->getReceiveTransfromProductDetails($_GET['id_order']);
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
			if( $rs->valid == 1 && $rs->is_closed == 0 )
			{
				$backlog = $rs->qty - $rs->received < 0 ? 0 : $rs->qty - $rs->received;
				$arr = array(
									"no"	    => $no,
									"barcode"	=> $bc->getBarcode($rs->id_product),
									"id_pd"		=> $rs->id_product,
									"pdCode"	=> $pd->getCode($rs->id_product),
									"pdName"	=> $pd->getName($rs->id_product),
									"qty"			=> number_format($rs->qty),
									"limit"		=> ($rs->qty + ($rs->qty* ( $limit * 0.01 ) ) ) - $rs->received,
									"backlog"	=> number_format($backlog)
								);
				array_push($ds, $arr);
				$totalQty += $rs->qty;
				$totalBacklog += $backlog;
				$no++;
			}

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
		$sc = 'ใบเบิกสินค้าไม่ถูกต้อง อาจถูกปิด หรือ ถูกยกเลิกไปแล้ว';
	}
	echo $sc;
}






if( isset( $_GET['addNew'] ) )
{
	include 'receive_transform/add_new.php';
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
