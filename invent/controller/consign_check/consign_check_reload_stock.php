<?php
$id = $_POST['id_consign_check'];
$cs = new consign_check($id);
$stock = new stock();

//--- ถ้าเพิ่มสำเร็จจะได้ ไอดีมา ตรวจสอบว่าไอดีเป็นตัวเลขหรือไม่(ป้องกันความผิดพลาด)
if(is_numeric($id) === TRUE && $id > 0)
{
  $qs = $stock->getStockInZone($cs->id_zone);
  if(dbNumRows($qs) > 0)
  {
    $sc = TRUE;
    startTransection();
    //---- ทำยอดตั้งต้นให้เป็น 0 ก่อน
    //--- ตัวไหนมียอดจะถูก update ทีหลัง
    //--- ตัวไหนไม่มียอดจะเป็น 0
    //--- ตัวไหนที่ไม่มีรายการ จะถูกเพิ่ม
    $cs->setStockZero($id);
    while($rs = dbFetchObject($qs))
    {
      if($sc == FALSE)
      {
        break;
      }

      //--- ถ้ามีรายการ
      if($cs->isExistsDetail($id, $rs->id_product) === TRUE)
      {
        if($cs->updateStockQty($id, $rs->id_product, $rs->qty) === FALSE)
        {
          $sc = FALSE;
          $message = 'ปรับปรุงยอดตั้งต้นไม่สำเร็จ';
        }
      }
      else
      {
        $arr = array(
          'id_consign_check' => $id,
          'id_product' => $rs->id_product,
          'product_code' => $rs->code,
          'stock_qty' => $rs->qty
        );

        if($cs->addDetail($arr) !== TRUE)
        {
          $sc = FALSE;
          $message = 'บันทึกยอดตั้งต้นไม่สำเร็จ';
        }
      }
    }

    if($sc === TRUE)
    {
      commitTransection();
    }
    else
    {
      dbRollback();
    }

    endTransection();
  }

}

echo $sc === TRUE ? 'success' : $message;

 ?>
