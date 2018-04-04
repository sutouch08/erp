<?php

  $sc = TRUE;

  $id_move = $_POST['id_move'];

  //--- move objcet
  $cs    = new move($id_move);

  //--- stock object
  $stock = new stock();

  //--- movement object
  $mv    = new movement();

  //--- zone object
  $zone = new zone();



  //--- โซนที่ย้ายออก
  $id_zone		 = $_POST['id_zone'];

  //--- คลังที่ย้ายออก
  $id_warehouse = $zone->getWarehouseId($id_zone);

  //--- จำนวนที่ย้ายออก Array
  $moveQty 	   = $_POST['moveQty'];

  //--- สินค้าที่ย้ายออก Array
  $pd			     = $_POST['id_product'];

  $auz = $zone->isAllowUnderZero($id_zone);

  $product = new product();


  //-----

  startTransection();

  foreach( $moveQty as $name => $val)
  {
    if($sc === FALSE)
    {
      break;
    }

    if( $val != '' && $val != 0 )
    {
      $id_product	= $pd[$name];
      $qty		    = $val;

      if($stock->isEnough($id_zone, $id_product, $qty) !== TRUE && $auz == FALSE)
      {
        $sc = FALSE;
        $message = $product->getCode($id_product).'  มียอดในโซนไม่เพียงพอ';
        break;
      }

      //--- ตัดยอดออกจากโซนต้นทาง
      if( $stock->updateStockZone($id_zone, $id_product, ($qty * -1)) !== TRUE )
      {
        //--- if update stock fail
        $sc = FALSE;
        $message = $product->getCode($id_product).' ตัดยอดสต็อกจากโซนต้นทางไม่สำเร็จ';
        break;
      }


      $arr = array(
                  "id_move"      => $id_move,
                  "id_product"	 => $id_product,
                  "id_warehouse" => $id_warehouse,
                  "from_zone"	   => $id_zone,
                  "to_zone"		   => 0,
                  "qty"		       => $qty
                );

      //--- เพิ่มข้อมูล
      //--- หากมีข้อมูลอยู่แล้ว update
      //--- หากไม่มี insert
      //--- เมื่อ insert สำเร็จจะ return id_move_detail กลับมา
      $ra = $cs->updateDetail($arr);

      if( $ra === FALSE )
      {
        $sc = FALSE;
        $message = $product->getCode($id_product).' เพิ่ม/ปรับปรุง รายการไม่สำเร็จ';
        break;
      }
      else
      {
        //--- If insert or update move detail successful  do insert or update move temp
        $temp = array(
                  "id_move_detail" => $ra,
                  "id_move"        => $id_move,
                  "id_product"	   => $id_product,
                  "id_warehouse"   => $id_warehouse,
                  "id_zone"		     => $id_zone,
                  "qty"	           => $qty,
                  "id_employee"	   => getCookie('user_id')
                  );

        //--- เพิ่มยอดเข้า temp
        if( $cs->updateTemp($temp) !== TRUE )
        {
          //---- if insert or update move temp fail
          $sc = FALSE;
          $message = $product->getCode($id_product).' เพิ่ม/ปรับปรุง temp ไม่สำเร็จ';
          break;
        } //--- end if updateTemp

      } //--- end if $ra


      //--- บันทึก movement ออก
      if( $mv->move_out($cs->reference, $id_warehouse, $id_zone, $id_product, $qty, $cs->date_add) !== TRUE)
      {
        //--- ถ้าบันทึก movement ไม่สำเร็จ
        $sc = FALSE;
        $message = $product->getCode($id_product).' บันทึก movement ไม่สำเร็จ';
        break;
      } //--- end move_out

    } //--- end if $val != 0

  } //--- end foreach

  if( $sc === TRUE )
  {
    commitTransection();
  }
  else
  {
    dbRollback();
  }

  endTransection();

  echo $sc === TRUE ? 'success' : $message;


 ?>
