<?php
include "../../library/config.php";
include "../../library/functions.php";
include "../function/tools.php";
include "../function/qc_helper.php";

if( isset( $_GET['closeOrder'])){
	$order = new order($_POST['id_order']);
	if( $order->state == 6){
		$sc = $order->stateChange($order->id, 7);
	}else {
		$sc = 'ไม่สามารถปิดออเดอร์ได้ เนื่องจากสถานะออเดอร์ได้ถูกเปลี่ยนไปแล้ว';
	}

	echo $sc === TRUE ? 'success' : ($sc === FALSE ? 'ปิดออเดอร์ไม่สำเร็จ กรุณาลองใหม่อีกครั้ง' : $sc);
}



//----	บันทึกรายการที่ตรวจ
if( isset( $_GET['saveQc']))
{
	$id_order = $_POST['id_order'];
	$id_box		= $_POST['id_box'];
	$product	= $_POST['product'];
	$sc 			= TRUE;

	if( ! empty($product) )
	{
		startTransection();
		$qc = new qc();
		foreach($product as $id_product => $qty)
		{
			if( $qty > 0)
			{
				if($qc->updateChecked($id_order, $id_box, $id_product, $qty) === FALSE )
				{
					$sc = FALSE;
				}
			}
		}
		if($sc === TRUE )
		{
			commitTransection();
		}
		else
		{
			dbRollback();
		}
		endTransection();
	}
	echo $sc === TRUE ? 'success' : 'fail';
}




if( isset( $_GET['getBox']))
{
	$box = new box();
	$id_box = $box->getBox($_GET['barcode'], $_GET['id_order']);
	echo $id_box === FALSE ? 'ไม่พบกล่อง' : $id_box;
}





if( isset( $_GET['getBoxList']))
{
	$id_order = $_GET['id_order'];
	$id_box		= $_GET['id_box'];
	$qc 			= new qc();
	$qs 			= $qc->getBoxList($id_order);

	if( dbNumRows($qs) > 0)
	{
		$ds = array();
		$no = 1;
		while( $rs = dbFetchObject($qs))
		{
			$arr = array(
						"no" 			=> $no,
						"id_box" 	=> $rs->id_box,
					 	"qty"			=> number($rs->qty),
						"class" 	=> $rs->id_box == $id_box ? 'btn-success' : 'btn-default'
				);
			array_push($ds, $arr);
			$no++;
		}

		$sc = json_encode($ds);
	}
	else
	{
		$sc = "no box";
	}
	echo $sc;
}


?>
