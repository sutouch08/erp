<?php

	$ds 			= $_POST['discount'];
	$approver	= $_POST['approver'];
	$token		= $_POST['token'];
	$id_emp		= getCookie('user_id');

	$logs 			= new logs();

	$count = 0;

	foreach( $ds as $id => $value )
	{
		//----- ข้ามรายการที่ไม่ได้กำหนดค่ามา
		if( $value != "" )
		{
			//--- ได้ Obj มา
			$detail = $order->getDetailData($id);

			//--- ถ้ารายการนี้มีอยู่
			if( $detail !== FALSE )
			{
				//--- แยกเอาสัญลักษณ์ % ออก
				$val = explode('%', $value);

				//---	ตัดช่องว่าง
				$gp  = trim($val[0]);

				$arr = array('gp' => $gp);

				$cs = $order->updateDetail($id, $arr);

				$log_data = array(
											"reference"		=> $order->reference,
											"product_code"	=> $detail->product_code,
											"old_gp"	=> $detail->gp,
											"new_gp"	=> $gp,
											"id_employee"	=> $id_emp,
											"approver"		=> $approver,
											"token"			=> $token
											);
				$logs->logs_gp($log_data);
			}	//--- end if detail
		} //--- End if value
	}	//--- end foreach

	echo 'success';

	?>
