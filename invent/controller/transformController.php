<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";
require "../function/transform_helper.php";


if( isset( $_GET['addTransformProduct']))
{
  $cs = new transform();
  $arr = array(
          'id_order' => $_POST['id_order'],
          'id_order_detail' => $_POST['id_order_detail'],
          'from_product' => $_POST['from_product'],
          'id_product' => $_POST['id_product'],
          'qty' => $_POST['qty']
        );

  $rs = $cs->addDetail($_POST['id_order_detail'], $_POST['id_product'], $arr);
  if( $rs === TRUE)
  {
    $ra  = getTransformProducts($_POST['id_order_detail']);
    $ra .= '<input type="hidden" id="transform-qty-'.$_POST['id_order_detail'].'" value="'.$cs->getSumTransformProductQty($_POST['id_order_detail']).'" />';

    $sc = json_encode(array('data' => $ra));
  }
  else
  {
    $sc = 'ทำรายการไม่สำเร็จ';
  }

  echo $sc;
}



//--- เอารายการเชื่อมโยงออกเฉพาะที่เลือก
if( isset($_GET['removeTransformProduct']))
{
  $cs = new transform();
  if( $cs->removeTransformProduct($_POST['id_order_detail'], $_POST['id_product']) === TRUE )
  {
    $ra  = getTransformProducts($_POST['id_order_detail']);
    $ra .= '<input type="hidden" id="transform-qty-'.$_POST['id_order_detail'].'" value="'.$cs->getSumTransformProductQty($_POST['id_order_detail']).'" />';

    $sc = json_encode(array('data' => $ra));
  }
  else
  {
    $sc = 'ทำรายการไม่สำเร็จ';
  }

  echo $sc;
}





//--- เอารายการเชื่อมโยงออกทั้งแถว
if( isset($_GET['removeTransformDetail']))
{
  $cs = new transform();

  if( $cs->removeTransformDetail($_POST['id_order_detail']) === TRUE )
  {
    echo 'success';
  }
  else
  {
    echo 'ลบการเชื่อมโยงสินค้าไม่สำเร็จ';
  }

}




//--- ตรวจสอบว่ามีการเชือมโยงสินค้าไว้บ้างแล้วหรือยัง
//--- กรณีที่ต้องการเอาการเชื่อมโยงออกทั้งรายการ
if(isset($_GET['isExistsConnected'])){
  $cs = new transform();
  if( $cs->hasTransformProduct($_GET['id_order_detail']) === TRUE)
  {
    echo 'exists';
  }
  else
  {
    echo 'not_exists';
  }
}

//--- ค้นหาโซนที่อยู่ในคลังระหว่างทำเท่านั้น
if( isset( $_GET['getZone']))
{
  $sc = array();
  $zone = new zone();
  $qs = $zone->searchWIPZone(trim($_REQUEST['term']));
  if( dbNumRows($qs) > 0 )
  {
    while($rs = dbFetchObject($qs))
    {
      $sc[] = $rs->zone_name.' | '.$rs->id_zone;
    }
  }
  else
  {
    $sc[] = 'ไม่พบรายการ';
  }

  echo json_encode($sc);
}







//--- ค้นหาพนักงาน
if( isset( $_GET['getEmployee']))
{
  $sc = array();
  $emp = new employee();
  $qs = $emp->search('id_employee, first_name, last_name', trim($_REQUEST['term']));
  if( dbNumRows($qs) > 0)
  {
    while( $rs = dbFetchObject($qs))
    {
      $sc[] = $rs->first_name.' '.$rs->last_name.' | '. $rs->id_employee;
    }
  }
  else
  {
    $sc[] = 'ไม่พบรายการ';
  }

  echo json_encode($sc);
}



if( isset($_GET['getDetailTable']))
{
  include 'transform/detail_table.php';
}



if( isset($_GET['getProduct']))
{
  $sc = array();
  $pd = new product();
  $qs = $pd->search(trim($_REQUEST['term']), 'id, code');
  if( dbNumRows($qs) > 0)
  {
    while($rs = dbFetchObject($qs))
    {
      $sc[] = $rs->code.' | '.$rs->id;
    }
  }
  else
  {
    $sc[] = 'ไม่พบรายการ';
  }

  echo json_encode($sc);
}
 ?>
