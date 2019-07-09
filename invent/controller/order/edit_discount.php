<?php

	$ds 			= isset($_POST['discount']) ? $_POST['discount'] : FALSE;
	$approver	= $_POST['approver'];
	$token		= $_POST['token'];
	$id_emp		= getCookie('user_id');
	$payment	= new payment_method($order->id_payment);
	$credit 		= new customer_credit();
	$logs 			= new logs();
	$count = 0;
	if($ds !== FALSE)
	{
		foreach( $ds as $id => $value )
		{
			//----- ข้ามรายการที่ไม่ได้กำหนดค่ามา
			if( $value != "")
			{
				//--- ได้ Obj มา
				$detail = $order->getDetailData($id);

				//--- ถ้ารายการนี้มีอยู่
				if( $detail !== FALSE )
				{
					//---- ถ้ารายการนี้เป็นเครดิตเทอมและคำนวณยอดใช้ไปแล้ว
					if( $payment->hasTerm == 1 && $detail->isSaved == 1)
					{
						//--- คืนยอดใช้ไปก่อน แล้วจะไปคำนวณใหม่อีกทีตอนบันทึก
						$credit->decreaseUsed($order->id_customer, $detail->total_amount);

						//---- ระบุสถานะเป็นยังไม่ตัดยอดเครดิต
						$order->unsaveDetail($detail->id);

					}	//---- end if hasTerm

					//------ คำนวณส่วนลดใหม่
					$step = explode('+', $value);
					$discAmount = 0;
					$discLabel = array(0, 0, 0);
					$price = $detail->price;
					$i = 0;
					foreach($step as $discText)
					{
						if($i < 3) //--- limit ไว้แค่ 3 เสต็ป
						{
							$disc = explode('%', $discText);
							$disc[0] = trim($disc[0]); //--- ตัดช่องว่างออก
							$discount = count($disc) == 1 ? $disc[0] : $price * ($disc[0] * 0.01); //--- ส่วนลดต่อชิ้น
							$discLabel[$i] = count($disc) == 1 ? $disc[0] : $disc[0].'%';
							$discAmount += $discount;
							$price -= $discount;
						}
						$i++;
					}

					$total_discount = $detail->qty * $discAmount; //---- ส่วนลดรวม
					$total_amount = ( $detail->qty * $detail->price ) - $total_discount; //--- ยอดรวมสุดท้าย

					$arr = array(
								"discount" => $discLabel[0],
								"discount2" => $discLabel[1],
								"discount3" => $discLabel[2],
								"discount_amount"	=> $total_discount,
								"total_amount" => $total_amount ,
								"id_rule"	=> 0
							);

					$cs = $order->updateDetail($id, $arr);
					$log_data = array(
												"reference"		=> $order->reference,
												"product_code"	=> $detail->product_code,
												"old_discount"	=> discountLabel($detail->discount, $detail->discount2, $detail->discount3),
												"new_discount"	=> discountLabel($discLabel[0], $discLabel[1], $discLabel[2]),
												"id_employee"	=> $id_emp,
												"approver"		=> $approver,
												"token"			=> $token
												);
					$logs->logs_discount($log_data);
				}	//--- end if detail
			} //--- End if value
		}	//--- end foreach

		//---- ถ้ามีเครดิตเทอม
		if( $payment->hasTerm == 1 )
		{
			//---- เปลี่ยนสถานะออเดอร์ เพื่อให้ต้องกดบันทึกอีกครั้งเพื่อตัดยอดเครดิตใหม่
			$order->changeStatus($order->id, 0);
		}

	}


	echo 'success';

	?>
