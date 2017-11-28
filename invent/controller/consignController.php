<?php
require '../../library/config.php';
require '../../library/functions.php';
require '../function/tools.php';
require '../function/zone_helper.php';


if( isset($_GET['getItemByBarcode']))
{
  include 'consign/consign_get_product_by_barcode.php';
}



if( isset($_GET['getProductData']))
{
  include 'consign/consign_get_product_data.php';
}



if( isset( $_GET['getProductByCode']))
{
  include 'consign/consign_get_product_by_code.php';
}



if(isset($_GET['getStockInZone']))
{
  $id_pd   = $_GET['id_product'];
  $id_zone = $_GET['id_zone'];
  $stock   = new stock();
  $qty     = $stock->getStockZone($id_zone, $id_pd);
  echo $qty;

}


if(isset($_GET['addNew']))
{
  include 'consign/consign_add.php';
}




if(isset($_GET['saveConsign']))
{
  include '../function/discount_helper.php';
  include '../function/vat_helper.php';
  include 'consign/consign_save.php';
}



if(isset($_GET['deleteConsign']))
{
  include 'consign/consign_delete.php';
}


//--- ตรวจสอบสถานะก่อนทำการ update
if(isset($_GET['canUpdate']))
{
  $sc = TRUE;
  $cs = new consign($_GET['id_consign']);
  if( $cs->isCancle == 1)
  {
    $sc = FALSE;
    $message = 'ไม่สามารถแก้ไขได้เนื่องจากเอกสารถูกยกเลิกแล้ว';
  }

  if( $cs->isExport == 1 OR $cs->isSaved == 1)
  {
    $sc = FALSE;
    $message = 'ไม่สามารถแก้ไขได้เนื่องจากเอกสารถูกบันทึกหรือถูกส่งออกแล้ว';
  }

  echo $sc === TRUE ? 'ok' : $message;
}




//--- Update document
if(isset($_GET['updateConsign']))
{
  include 'consign/consign_update.php';
}




if(isset($_GET['getProduct']))
{
  $sc = array();
  $pd = new product();
  $fields = 'id, code';
  $qs = $pd->search($_REQUEST['term'], $fields);
  if( dbNumRows($qs) > 0 )
  {
    while($rs = dbFetchObject($qs))
    {
      $sc[] = $rs->code;
    }
  }
  else
  {
    $sc[] = 'ไม่พบสินค้า';
  }

  echo json_encode($sc);
}






if(isset( $_GET['getCustomer']))
{
  $sc = array();
  $cs = new customer();
  $fields = 'code, name';
  $qs = $cs->search($_REQUEST['term'], $fields);
  if(dbNumRows($qs) > 0)
  {
    while($rs = dbFetchObject($qs))
    {
      $sc[] = $rs->code.' | '.$rs->name;
    }
  }
  else
  {
    $sc[] = 'ไม่พบลูกค้า';
  }

  echo json_encode($sc);
}







if(isset($_GET['getCustomerZone']))
{
  $sc = array();
  if( $_GET['id_customer'] != '' )
  {
    $zone = new zone();
    $qs = $zone->searchConsignZone( trim( $_REQUEST['term'] ), $_GET['id_customer'] );
    if( dbNumRows($qs) > 0 )
    {
      while($rs = dbFetchObject($qs))
      {
        $sc[] = $rs->zone_name.' | '.$rs->id_zone;
      }
    }
    else
    {
      $sc[] = 'ไม่พบโซนของลูกค้า';
    }
  }
  else
  {
    $sc[] = 'กรุณาระบุลูกค้า';
  }

  echo json_encode($sc);
}







if(isset( $_GET['getConsignZone']))
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



if(isset( $_GET['clearFilter']))
{
  deleteCookie('sConsignCode');
  deleteCookie('sConsignCus');
  deleteCookie('sConsignZone');
  deleteCookie('sShop');
  deleteCookie('sEvent');
  deleteCookie('fromDate');
  deleteCookie('toDate');
  echo 'done';
}
 ?>
