<?php
$sc = TRUE;
$id_consign_check = $_POST['id_consign_check'];
$barcode = $_POST['barcode'];  //---- barcode item
$qty = $_POST['qty'];
$id_box = $_POST['id_box'];
$bc = new barcode();
$cs = new consign_check();

$message = 'success';

$id_pd = $bc->getProductId($barcode);
if($id_pd === FALSE)
{
  $sc = FALSE;
  $message = 'ไม่พบสินค้าในระบบ กรุณาตรวจสอบบาร์โค้ด';
}
else
{
  startTransection();

  $ds = $cs->getDetail($id_consign_check, $id_pd);

  //--- ถ้าไม่มีสินค้านี้ในโซน
  if($ds === FALSE)
  {
    $pd = new product($id_pd);
    $message = "ไม่พบสินค้า ".$pd->code." ในโซน";

    $arr = array(
      'id_consign_check' => $id_consign_check,
      'id_product' => $id_pd,
      'product_code' => $pd->code,
      'stock_qty' => 0,
      'qty' => $qty
    );

    if($cs->addDetail($arr) !== TRUE)
    {
      $sc = FALSE;
      $message .= ' และบันทึกยอดตรวจนับไม่สำเร็จ';
    }
    else
    {
      $message .="  แต่ระบบได้บันทึกยอดตรวจนับแล้ว";
    }
  }
  else
  {
    if($ds->stock_qty < ($ds->qty + $qty))
    {
        $message = 'จำนวนสินค้าเกินกว่ายอดที่มีในโซน';
    }

    //----- update check qty in tbl_consign_check_detail
    if($cs->updateCheckedQty($id_consign_check, $id_pd, $qty) !== TRUE)
    {
      $sc = FALSE;
      $message = 'บันทึกจำนวนตรวจนับไม่สำเร็จ';
    }
  }

  //----  update qty to consign_box
  if($cs->updateConsignBoxDetail($id_box, $id_consign_check, $id_pd, $qty) !== TRUE)
  {
    $sc = FALSE;
    $message = 'บันทึกยอดตรวจนับลงกล่องไม่สำเร็จ';
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

$res = $sc === TRUE ? 'success' : 'fail';
$arr = array(
            'status' => $res,
            'message' => $message
          );

echo json_encode($arr);

 ?>
