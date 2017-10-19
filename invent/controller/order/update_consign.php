<?php
	$consign = new consign();

	//--- id_zone in tbl_order_consign
	$id_zone = $_POST['id_zone'];
	//---	เตรียมข้อมูลสำหรับ update
	$arr = array(
						"date_add"	=> dbDate($_POST['date_add']),
						"id_customer" => $_POST['id_customer'],
						"status"		=> 0, //--- เปลี่ยนกลับ ให้กดบันทึกใหม่
						"emp_upd"		=> getCookie('user_id'),
						"remark"		=> $_POST['remark'],
						"gp"				=> $_POST['gp'],
						"is_so"			=> $_POST['is_so']
						);

		//----- ถ้ายังไม่มีรายการ ไม่ต้องคำนวณใหม่
		if( $order->hasDetails($order->id) === TRUE )
		{
			//---- ยอดรวมสินค้าที่บันทึกไปแล้ว เพื่อเอามาคืนยอดใช้ไป
			$amount = $order->getTotalAmountSaved($order->id);

			//----- ยกเลิกการบันทึกรายการ เพื่อจะได้คำนวณใหม่อีกที
			$order->unSaveDetails($order->id);

		}

		//--- update order header
		$rs = $order->update($order->id, $arr);

		//--- update zone
		$consign->update($order->id, $id_zone);

		//--- update gp
		$order->updateDetails($order->id, array('gp' => $_POST['gp']));

	echo $rs === TRUE ? 'success' : 'fail';
?>
