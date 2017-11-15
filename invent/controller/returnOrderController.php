<?php
require '../../library/config.php';
require '../../library/functions.php';
require '../function/tools.php';


if( isset($_GET['saveReturn']))
{

  $id_zone = $_POST['id_zone'];
  $reference = $_POST['reference'];
  $cs = new return_order($reference);
  $ds = $_POST['qty'];
  if( ! empty($ds))
  {
    foreach($ds as $id => $qty)
    {
      $cs->updateReceived($cs->bookcode, $reference, $id, $qty);
    }
  }
  echo 'success';
}


//--- auto complete zone name
if( isset( $_GET['getZone']) )
{
  $sc = array();
  $id_warehouse = $_GET['id_warehouse'];
  $zone = new zone();
  $qs = $zone->searchWarehouseZone(trim($_REQUEST['term']), $id_warehouse);
  if(dbNumRows($qs) > 0)
  {
    while($rs = dbFetchObject($qs))
    {
      $sc[] = $rs->zone_name.' | '.$rs->id_zone;
    }
  }
  else
  {
    $sc[] = 'ไม่พบโซน';
  }

  echo json_encode($sc);
}



//--- บันทึกโซนรับเข้า (ทั้งเอกสาร)
if( isset( $_GET['setZone'] ) )
{
  $sc = TRUE;
  $reference = $_POST['reference'];
  $id_zone = $_POST['id_zone'];
  $cs = new return_order();

  if( $cs->setZone($reference, $id_zone) === FALSE )
  {
    $sc = FALSE;
    $message = 'เปลี่ยนโซนไม่สำเร็จ';
  }

  echo $sc === TRUE ? 'success' : $message;

}



if( isset($_GET['clearFilter']))
{
  deleteCookie('SMsCode');
  deleteCookie('SMsInv');
  deleteCookie('SMsCus');
  deleteCookie('SMsStatus');
  deleteCookie('fromDate');
  deleteCookie('toDate');
  echo 'done';
}

 ?>
