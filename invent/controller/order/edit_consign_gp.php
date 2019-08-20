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


				//--- ถ้ามีการแก้ไขส่วนลด (ส่วนลดไม่เท่าเดิม)
				if( $detail->gp != $value )
				{
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
								"discount"  => $discLabel[0],
								"discount2" => $discLabel[1],
								"discount3" => $discLabel[2],
								"discount_amount"	=> $total_discount,
								"total_amount"    => $total_amount ,
								"id_rule"	        => 0,
								"gp"              => $value
							);

					$cs = $order->updateDetail($id, $arr);

					$log_data = array(
												"reference"		=> $order->reference,
												"product_code"	=> $detail->product_code,
												"old_discount"	=> $detail->gp,
												"new_discount"	=> $value,
												"id_employee"	=> $id_emp,
												"approver"		=> $approver,
												"token"			=> $token
												);

					$logs->logs_discount($log_data);

				}	//---	end if match discount
			}	//--- end if detail
		} //--- End if value
	}	//--- end foreach

	echo 'success';

	?>
