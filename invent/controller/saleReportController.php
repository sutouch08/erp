<?php
require '../../library/config.php';
require '../../library/functions.php';
require '../function/tools.php';
include '../function/report_helper.php';


//--- รายงานวิเคราะห์ขายแบบละเอียด
if(isset($_GET['sale_deep_analyz']) && isset($_GET['export']))
{
  include 'report/saleReport/export_sale_deep_analyz.php';
}


//--- รายงานยอดขายแยกตามช่องทางการขายแสดงเลขที่เอกสาร
if(isset($_GET['saleByChannelsAndReference']) && isset($_GET['report']))
{
  include 'report/saleReport/report_sale_by_channels_show_reference.php';
}


if(isset($_GET['saleByChannelsAndReference']) && isset($_GET['export']))
{
  include 'report/saleReport/export_sale_by_channels_show_reference.php';
}




 ?>
