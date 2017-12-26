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


//----  รายงานสินค้าคงเหลือแยกตามโซน
if(isset($_GET['stock_balance_by_zone']) && isset($_GET['export']))
{
  include 'report/stockReport/export_stock_balance_by_zone.php';
}

 ?>
