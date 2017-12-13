<?php
$sc = TRUE;
$id = $_POST['id_receive_product'];
$cs = new receive_product($id);
$mv = new movement();
$stock = new stock();
$po = new po();
$cost = new product_cost();
$emp = getCookie('user_id');
//--- check if stock enough
if( isStockEnough($id) === TRUE )
{
  $reference = $cs->reference;
  $qs = $cs->getDetail($id);
  startTransection();

  while( $rs = dbFetchObject($qs) )
  {
    //--- update stock
    if( $stock->updateStockZone($rs->id_zone, $rs->id_product, $rs->qty * -1) !== TRUE )
    {
      $sc = FALSE;
      $message = 'ปรับยอดสินค้าออกจากโซนไม่สำเร็จ';
    }

      //---- ลดจำนวนวรายการต้นทุนสินค้าใน tbl_product_cost
    if($cost->deleteCostList($rs->id_product, $rs->cost, $rs->qty) !== TRUE)
    {
      $sc = FALSE;
      $message = 'ปรับปรุงต้นทุนสินค้าไม่สำเร็จ';
    }

    //--- remove movement
    if($mv->removeMovement($reference, $rs->id_product) !== TRUE)
    {
      $sc = FALSE;
      $message = 'ลบ movement ไม่สำเร็จ';
    }

    //--- update received in po
    if($po->unReceived($cs->po, $rs->id_product, $rs->qty) !== TRUE)
    {
      $sc = FALSE;
      $message = 'ปรับปรุงยอดรับเข้าแล้วในใบสั่งซื้อไม่สำเร็จ';
    }

    //--- cancle receive detail
    if($cs->cancleDetail($rs->id) !== TRUE )
    {
      $sc = FALSE;
      $message = 'ยกเลิกรายการรับเข้าไม่สำเร็จ';
    }

  }//-- End while

  if( $sc === TRUE )
  {
    if( $cs->cancleReceived($id, $emp) !== TRUE )
    {
      $sc = FALSE;
      $message = 'ยกเลิกเอกสารไม่สำเร็จ';
    }

    if( $po->setStatus($cs->po) !== TRUE )
    {
      $sc = FALSE;
      $message = 'เปลี่ยนสถานะใบสั่งซื้อไม่สำเร็จ';
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
}
else
{
  $sc = FALSE;
  $message = "สินค้าคงเหลือไม่พอให้ยกเลิก";
}//--- if isStockEnough

echo $sc === TRUE ? 'success' : $message;
 ?>
