<?php
require '../../library/config.php';
require '../../library/functions.php';
require '../function/tools.php';
require '../function/zone_helper.php';



if(isset($_GET['addNew']))
{
  include 'consign_check/consign_check_add.php';

}


if(isset($_GET['checkItem']))
{
  $sc = TRUE;
  $id_consign_check = $_POST['id_consign_check'];
  $barcode = $_POST['barcode'];  //---- barcode item
  $qty = $_POST['qty'];
  $id_box = $_POST['id_box'];
  $bc = new barcode();
  $cs = new consign_check();

  $id_pd = $bc->getProductId($barcode);

  startTransection();

  //----- update check qty in tbl_consign_check_detail
  if($cs->updateCheckedQty($id_consign_check, $id_pd, $qty) !== TRUE)
  {
    $sc = FALSE;
    $message = 'บันทึกจำนวนตรวจนับไม่สำเร็จ';
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

  echo $sc === TRUE ? 'success' : $message;

}


if(isset($_GET['getBox']))
{
  $id_consign_check = $_GET['id_consign_check'];
  $barcode = trim($_GET['barcode']);
  $cs = new consign_check();
  $box = $cs->getConsignBox($id_consign_check, $barcode);

  if($box !== FALSE)
  {
    $arr = array(
      'id_box' => $box->id,
      'box_no' => $box->box_no,
      'qty' => $cs->getQtyInBox($box->id, $id_consign_check)
    );
    $sc = json_encode($arr);
  }
  echo $box === FALSE ? 'เพิ่มกล่องไม่สำเร็จ' : $sc;
}






if(isset($_GET['updateHeader']))
{
  $id  = $_POST['id_consign_check'];
  $arr = array(
    'date_add' => dbDate($_POST['date_add']),
    'remark'   => addslashes($_POST['remark'])
  );

  $cs = new consign_check();

  $sc = $cs->update($id, $arr);

  echo $sc === TRUE ? 'success' : $cs->error;
}


 ?>
