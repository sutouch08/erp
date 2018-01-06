<?php
require '../../library/config.php';
require '../../library/functions.php';
require '../function/tools.php';
include '../function/report_helper.php';


//--- รายงานสินค้าคงเหลือแยกตามโซน
if(isset($_GET['stock_balance_by_zone']) && isset($_GET['report']))
{
  include 'report/stockReport/report_stock_balance_by_zone.php';
}


//----  ส่งออกรายงานสินค้าคงเหลือแยกตามโซน
if(isset($_GET['stock_balance_by_zone']) && isset($_GET['export']))
{
  include 'report/stockReport/export_stock_balance_by_zone.php';
}


//--- ส่งออกยอดตั้งต้น ไปตรวจนับ
if(isset($_GET['exportToCheck']) && isset($_GET['id_zone']))
{
  include 'report/stockReport/export_to_check.php';
}




//--- รายงานสินค้าคงเหลือเปรียบเทียบคลัง
if(isset($_GET['stock_balance_compare_warehouse']) && isset($_GET['report']))
{
  include 'report/stockReport/report_stock_compare_warehouse.php';
}


//--- ส่งออกรายงานสินค้าคงเหลือเปรียบเทียบคลัง
if(isset($_GET['stock_balance_compare_warehouse']) && isset($_GET['export']))
{
  include 'report/stockReport/export_stock_compare_warehouse.php';
}


//--- รายงานสินค้าคงเหลือ
if(isset($_GET['stock_balance']) && isset($_GET['report']))
{
  if($_GET['prevDate'] == 1)
  {
    include 'report/stockReport/report_stock_balance_prev_date.php';
  }
  else
  {
    include 'report/stockReport/report_stock_balance.php';
  }

}



//--- ส่งออกรายงานสินค้าคงเหลือ
if(isset($_GET['stock_balance']) && isset($_GET['export']))
{
  if($_GET['prevDate'] == 1)
  {
    include 'report/stockReport/export_stock_balance_prev_date.php';
  }
  else
  {
    include 'report/stockReport/export_stock_balance.php';
  }

}


 ?>
