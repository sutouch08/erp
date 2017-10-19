<?php
require '../../library/config.php';
require '../../library/functions.php';
require '../function/tools.php';
require '../function/zone_helper.php';


if( isset( $_GET['getConsignZone']))
{
  $sc = array();
  if( $_GET['id_customer'] != '')
  {
    $zone = new zone();
    $qs = $zone->searchConsignZone( trim( $_REQUEST['term'] ), $_GET['id_customer'] );
    if( dbNumRows($qs) > 0 )
    {
      while($rs = dbFetchObject($qs))
      {
        $sc[] = $rs->zone_name.' | '.$rs->id_zone.' | '.$rs->id_customer;
      }
    }
    else
    {
      $sc[] = 'ไม่พบรายการ';
    }
  }
  else
  {
    $sc[] = 'เลือกลูกค้าก่อน';
  }

  echo json_encode($sc);
}

 ?>
