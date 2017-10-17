<?php
	//---	คำนวนส่วนลดใหม่หรือไม่ 1 = คำนวนใหม่ 0 = ไม่ต้องคำนวณ
	$recal = isset( $_GET['recal'] ) ? $_GET['recal'] : 0;

	$order = new order($_POST['id_order']);

	$bd 	 = new sponsor_budget();

	//---	เตรียมข้อมูลสำหรับ update
	$arr = array(
						"date_add"	=> dbDate($_POST['date_add']),
						"id_customer"	=> $_POST['id_customer'],
						"id_employee" => $_POST['id_employee'],
						"status"		=> 0, //--- เปลี่ยนกลับ ให้กดบันทึกใหม่
						"emp_upd"		=> getCookie('user_id'),
						"remark"		=> $_POST['remark'],
						"id_budget" => $_POST['id_budget']
						);

		//----- ถ้ายังไม่มีรายการ ไม่ต้องคำนวณใหม่
		if( $order->hasDetails($order->id) === TRUE )
		{
			//---- ยอดรวมสินค้าที่บันทึกไปแล้ว เพื่อเอามาคืนยอดใช้ไป
			$amount = $order->getTotalAmountSaved($order->id);

			//----- ยกเลิกการบันทึกรายการ เพื่อจะได้คำนวณใหม่อีกที
			$order->unSaveDetails($order->id);

			//---- คืนยอดเครดิตใช้ไป แล้วค่อยไปคำนวณใหม่ตอนบันทึก
			$bd->decreaseUsed($order->id_budget, $amount);

		}

		//--- update order header
		$rs = $order->update($order->id, $arr);


	echo $rs === TRUE ? 'success' : 'fail';
?>
