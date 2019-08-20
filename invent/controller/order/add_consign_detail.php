<?php

foreach( $ds as $items )
{
  foreach( $items as $id => $qty )
  {
    if( $qty > 0 )
    {
      $qty = ceil($qty);
      //--- ถ้ามีสต็อกมากว่าที่สั่ง
      if( $stock->getSellStock($id) >= $qty )
      {
        $pd 			= new product($id);

        //---- ถ้ายังไม่มีรายการในออเดอร์
        if( $order->isExistsDetail($order->id, $id) === FALSE )
        {
          $gp = $order->gp;
          //------ คำนวณส่วนลดใหม่
					$step = explode('+', $gp);
					$discAmount = 0;
					$discLabel = array(0, 0, 0);
					$price = $pd->price;
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

					$total_discount = $qty * $discAmount; //---- ส่วนลดรวม
					$total_amount = ( $qty * $price ) - $total_discount; //--- ยอดรวมสุดท้าย

        
          //$discount['discount'] = $order->gp.' %';
        //  $discount['amount']   = ($pd->price * ($order->gp * 0.01)) * $qty;

          $arr = array(
                    'id_order'	      => $order->id,
                    'id_style'		    => $pd->id_style,
                    'id_product'	    => $id,
                    'product_code'    => addslashes($pd->code),
                    'product_name'    => addslashes($pd->name),
                    'cost'            => $pd->cost,
                    'price'	          => $pd->price,
                    'qty'		          => $qty,
                    'discount'	      => $discLabel[0], //$discount['discount'],
                    'discount2'       => $discLabel[1],
                    'discount3'       => $discLabel[2],
                    'discount_amount' => $total_discount, ///$discount['amount'],
                    'total_amount'	  => ($pd->price * $qty) - $total_discount, //$discount['amount'],
                    'gp'              => $order->gp
                      );

          if( $order->addDetail($arr) === FALSE )
          {
            $result = FALSE;
            $error = 'Error : Insert fail';
            $err_qty++;
          }

        }
        else  //--- ถ้ามีรายการในออเดอร์อยู่แล้ว
        {
          $detail 	= $order->getDetail($order->id, $id);
          $qty			= $qty + $detail->qty;

          $gp = $order->gp;
          //------ คำนวณส่วนลดใหม่
					$step = explode('+', $gp);
					$discAmount = 0;
					$discLabel = array(0, 0, 0);
					$price = $pd->price;
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

					$total_discount = $qty * $discAmount; //---- ส่วนลดรวม
					$total_amount = ( $qty * $price ) - $total_discount; //--- ยอดรวมสุดท้าย

          $arr = array(
                  'id_product'      => $id,
                  'qty'             => $qty,
                  'discount'	      => $discLabel[0],
                  'discount2' => $discLabel[1],
                  'discount3' => $discLabel[2],
                  'discount_amount'	=> $total_discount,
                  'total_amount'	  => ($pd->price * $qty) - $total_discount,
                  'valid'           => 0,
                  'isSaved'	        => 0, //--- ย้อนกลับมาเป็นยังไม่ได้บันทึกอีกครั้ง
                  'gp'              => $order->gp
                  );

          if( $order->updateDetail($detail->id, $arr) === FALSE )
          {
            $result = FALSE;
            $error = 'Error : Update Fail';
            $err_qty++;
          }

        }	//--- end if isExistsDetail
      }
      else 	// if getStock
      {
        $error = 'Error : สินค้าไม่เพียงพอ';
      } 	//--- if getStock
    }	//--- if qty > 0
  }	//-- foreach items
}	//--- foreach ds
 ?>
