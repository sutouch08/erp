<?php

    /*
    การเปลียนสถานะมี 2 กรณี
    1. เปลี่ยนตามสถานะของออเดอร์ในแต่ละสถานีซึ่เป็นการเปลี่ยนโดยระบบ
    สถานะจะเปลี่ยนเพิ่มขึ้นเรื่อยๆ ทีละ 1 step

    2. เปลียนโดยคน เป็นการย้อนสถานะกลับไปแก้ไขข้อมูลต่าง หรือ ยกเลิกออเดอร์

    สถานะต่างๆมีดังนี้
    ID  สถานะ
    1   รอชำระเงิน
    2   ชำระเงินแล้ว
    3   รอจัดสินค้า
    4   กำลังจัดสินค้า     ** มีสินค้าเพิ่มใน buffer
    5   รอตรวจสินค้า     ** มีสินค้าใน buffer
    6   กำลังตรวจสินค้า   ** มีสินค้าใน buffer มีสินค้าใน qc
    7   รอเปิดบิล        ** มีสินค้าใน buffer มีสินค้าใน qc
    8   เปิดบิลแล้ว       ** สินค้าถูกเคลียร์ออกจาก buffer ส่วนที่เกินออเดอร์ถูกเพิ่มลงใน cancle มีการตัดสต็อก และบันทึกขาย บันทึก movement
    9   กำลังจัดส่ง
    10  จัดส่งแล้ว
    11  ยกเลิก

    *** กรณีที่มีการย้อนสถานะจากสถานะ 8-10
      1. ลบ movement
      2. ลบ รายการบันทึกขาย
      3. เพิ่มรายการกลับเข้า buffer
      4. เปลี่ยนสถานะ
    */

    $sc = TRUE;

    $prepare = new prepare();

    $QC = new qc();

    $movement = new movement();

    $buffer = new buffer();

    $cancle = new cancle_zone();

    $stock = new stock();

    $lend = new lend($order->id);

    $zone = new zone($lend->id_zone);


    //--- ตรวจสอบว่ามีการรับเข้าแล้วหรือยัง ถ้ามีการรับเข้าแล้วไม่ให้เปลี่ยนสถานะ
    if( $lend->isReceived($order->id) === TRUE)
    {
      $sc = FALSE;
      $message = 'มีการคืนสินค้าแล้วไม่อนุญาติให้เปลี่ยนสถานะ';
    }
    else
    {
      startTransection();

      if( $state < $order->state OR $state == 11)
      {
        if( $order->state >= 8 )
        {
          //--- ลบ movement
          if( $movement->dropMovement($order->reference) !== TRUE)
          {
            $sc = FALSE;
            $message = 'ลบ movement ไม่สำเร็จ';
          }

          //--- เพิ่มรายการกลับ buffer และลบรายการบันทึกขายทีละรายการ
          $qs = $order->getSoldOrderDetail($order->id);

          if( dbNumRows($qs) > 0)
          {
            while( $rs = dbFetchObject($qs))
            {
              if($sc == FALSE)
              {
                break;
              }

              //--- ลดยอดในโซนปลายทางออก
              $isEnough = $stock->isEnough($lend->id_zone, $rs->id_product, $rs->qty);

              if( $isEnough === FALSE && $zone->allowUnderZero === FALSE )
              {
                $sc = FALSE;
                $message = 'ยอดคงเหลือในโซนไม่เพียงพอ';
              }
              else
              {
                if($stock->updateStockZone($lend->id_zone, $rs->id_product, ($rs->qty * -1)) !== TRUE )
                {
                  $sc = FALSE;
                  $message = 'ปรับยอดในโซนปลายทางออกไม่สำเร็จ';
                }


                //--- เพิ่มข้อมูลกลับ buffer
                if( $buffer->updateBuffer($rs->id_order, $rs->id_product_style, $rs->id_product, $rs->id_zone, $rs->id_warehouse, $rs->qty) !== TRUE)
                {
                  $sc = FALSE;
                  $message = 'เพิ่มข้อมูลเข้า buffer ไม่สำเร็จ';
                }

                //--- ลบรายการบันทึกขาย
                if( $order->unSold($rs->id) !== TRUE )
                {
                  $sc = FALSE;
                  $message = 'ลบรายการบันทึกขายไม่สำเร็จ';
                }

                if( $lend->deleteDetail($rs->id_order, $rs->id_product) !== TRUE )
                {
                  $sc = FALSE;
                  $message = 'ลบรายการค้างรับคืนไม่สำเร็จ';
                }

              } //--- end if isEnough

            } //--- End while

          } //--- end if dbNumRows

          //--- ดึงรายการใน cancle กลับเข้า buffer
          $cancleProduct = $cancle->getCancleProductByOrder($order->id);

          if(dbNumRows($cancleProduct) > 0)
          {
            while($cp = dbFetchObject($cancleProduct))
            {
              $cs = $buffer->updateBuffer($cp->id_order, $cp->id_style, $cp->id_product, $cp->id_zone, $cp->id_warehouse, $cp->qty);
              if($cs === TRUE)
              {
                $cancle->delete($cp->id);
              }
            }
          }

        } //--- end if order->state >= 8  ถ้าสถานะปัจจุบัน มากกกว่า หรือ เท่ากับ 8 (เปิดบิลแล้ว)

        //--- ถ้ายกเลิกออเดอร์
        if( $state == 11)
        {
          //--- เคลียร์ buffer เข้า cancle
          if( clearBuffer($order->id) !== TRUE)
          {
            $sc = FALSE;
            $message = 'เคลียร์ Buffer ไม่สำเร็จ';
          }

          //--- ลบประวัติการจัดสินค้า
          if( $prepare->dropPreparedData($order->id) !== TRUE )
          {
            $sc = FALSE;
            $message = 'ลบรายการจัดสินค้าไม่สำเร็จ';
          }

          //--- ลบประวัติการตรวจสินค้า
          if( $QC->dropQcData($order->id) !== TRUE )
          {
            $sc = FALSE;
            $message = 'ลบรายการตรวจสินค้าไม่สำเร็จ';
          }

          //--- delete order detail
          if( $order->deleteDetails($order->id) !== TRUE )
          {
            $sc = FALSE;
            $message = 'ลบรายการในออเดอร์ไม่สำเร็จ';
          }

          //--- cancle order
          if( $order->cancleOrder($order->id) !== TRUE )
          {
            $sc = FALSE;
            $message = 'ยกเลิกออเดอร์ไม่สำเร็จ';
          }

          //--- ปิดเอกสาร
          if( $lend->closed($order->id) !== TRUE)
          {
            $sc = FALSE;
            $message = 'ปิดเอกสารรายการค้างรับไม่สำเร็จ';
          }

        } //--- end if ยกเลิกออเดอร์

      } //--- end if state < $order->state ถ้าสถานะที่จะเปลี่ยนน้อยกว่าปัจจุบัน


      //--- ถ้าไม่มีอะไรผิดพลาด
      if( $sc === TRUE )
      {
        if( $order->stateChange($order->id, $state) !== TRUE)
        {
          $sc = FALSE;
          $message = 'เปลี่ยนสถานะไม่สำเร็จ';
        }
      }

      if( $sc === TRUE )
      {
        commitTransection();
      }
      else
      {
        dbRollback();
      }

      endTransection();

    } //--- end if isReceived




    echo $sc === TRUE ? 'success' : $message;


?>
