<?php
  $sc = TRUE;
  $message = "";
  $id_order = $_POST['id_order'];
  $barcode 	= $_POST['barcode'];
  $id_zone	= $_POST['id_zone'];
  $qty 			= $_POST['qty'];
  $valid    = 0;


  $bc = new barcode();
  $stock = new stock();
  $buffer = new buffer();
  $order = new order($id_order);
  $prepare = new prepare();

  if( $order->state == 4)
  {

    //---	ดึงข้อมูลสินค้าจากบาร์โค้ด
    //---	ถ้ามีบาร์โค้ดจะได้ object barcode มา 1 row
    //---	id, barcode, id_product, reference, unit_code, unit_qty
    $pd = $bc->getDetail($barcode);

    //--- 1 ดึง id_product จาก barcode
    if( $pd !== FALSE)
    {

      //---	ถ้ามีออเดอร์สั่งมา
      $orderQty = $order->getOrderQty($id_order, $pd->id_product);

      //--- เอาจำนวนที่จัดมาคูณด้วยจำนวนหน่วย (บางทีอาจมีการยิงบาร์โค้ดแพ็ค)
      $qty = $pd->unit_qty * $qty;

      if( $orderQty > 0)
      {
        $preparedQty = $buffer->getSumQty($id_order, $pd->id_product);
        if( ($orderQty - $preparedQty) >= $qty)
        {
          //---	ถ้ามีสต็อกคงเหลือเพียงพอให้ตัด
          if($stock->isEnough($id_zone, $pd->id_product, $qty))
          {
            startTransection();

            //---	ตัดยอดสอนค้าออกจากโซน
            $ra = $stock->updateStockZone($id_zone, $pd->id_product, $qty * -1);

            //---	เพิ่มยอดเข้า buffer
            $rb = $buffer->updateBuffer($id_order, $pd->id_product, $id_zone, $qty);

            //--- เพิ่มรายการจัดสินค้าเข้าตารางจัด
            $rc = $prepare->updatePrepare($id_order, $pd->id_product, $id_zone, $qty);


            //---	ตรวจสอบออเดอร์ว่าครบแล้วหรือยัง
            if($orderQty == $buffer->getSumQty($id_order, $pd->id_product))
            {
              $order->validDetail($id_order, $pd->id_product);
              $valid = 1;
            }

            if( $ra === TRUE && $rb === TRUE && $rc === TRUE )
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
              $sc = FALSE;
              $message = "สินค้าไม่เพียงพอ กรุณากำหนดจำนวนสินค้าใหม่";
          }
        }
        else
        {
          $sc = FALSE;
          $message = "สินค้าเกิน กรุณาคืนสินค้าแล้วจัดสินค้าใหม่อีกครั้ง";
        }

      }
      else
      {
          $sc = FALSE;
          $message = "ไม่มีสินค้านี้ในออเดอร์";
      }
    }
    else
    {
      $sc = FALSE;
      $message = "ไม่มีสินค้าในระบบ หรือ บาร์โค้ดไม่ถูกต้อง";
    }

  }
  else
  {
      $sc = FALSE;
      $message = "สถานะออเดอร์ถูกเปลี่ยน ไม่สามารถจัดสินค้าต่อได้";
  }

  echo $sc === TRUE ? json_encode(array("id_product" => $pd->id_product, "qty" => $qty, "valid" => $valid)) : $message;

?>
