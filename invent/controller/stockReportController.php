<?php
require '../../library/config.php';
require '../../library/functions.php';
require '../function/tools.php';
include '../function/report_helper.php';



//---- รายงานการรับสินค้าแยกตามเอกสาร
if(isset($_GET['received_by_document']))
{
  if(isset($_GET['report']))
  {
    include 'report/stockReport/report_received_by_document.php';
  }

  if(isset($_GET['export']))
  {
    include 'report/stockReport/export_received_by_document.php';
  }
}



//---- รายงานการรับสินค้าแปรสภาพแยกตามเอกสาร
if(isset($_GET['received_transform_by_document']))
{
  if(isset($_GET['report']))
  {
    include 'report/stockReport/report_received_transform_by_document.php';
  }

  if(isset($_GET['export']))
  {
    include 'report/stockReport/export_received_transform_by_document.php';
  }
}




//--- รายงานสินค้าคงเหลือแยกตามโซน
if(isset($_GET['stock_balance_by_zone']) && isset($_GET['report']))
{
  if($_GET['prevDate'] == 1)
  {
    include 'report/stockReport/report_stock_balance_by_zone_prev_date.php';
  }
  else
  {
    include 'report/stockReport/report_stock_balance_by_zone.php';
  }

}


//----  ส่งออกรายงานสินค้าคงเหลือแยกตามโซน
if(isset($_GET['stock_balance_by_zone']) && isset($_GET['export']))
{
  if($_GET['prevDate'] == 1)
  {
    include 'report/stockReport/export_stock_balance_by_zone_prev_date.php';
  }
  else
  {
    include 'report/stockReport/export_stock_balance_by_zone.php';
  }
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


//--- รายงานสินค้าคงเหลือปัจจุบัน
if(isset($_GET['current_stock']) && isset($_GET['report']))
{
  include 'report/stockReport/report_current_stock.php';
}



//---- รายงานความเคลื่อนไหวสินค้า
if(isset($_GET['movementByProduct']) && isset($_GET['export']))
{
  include 'report/stockReport/export_movement_by_product.php';
}



if(isset($_GET['getStockGrid']))
{
  $sc = 'not exists';
	$id_style = $_GET['id_style'];
	$pd = new product();
	$grid = new stock_grid();
	$style = new style();
	$view = TRUE;  //--- view stock
	$sc = $grid->getStockGrid($id_style, $view);
	$tableWidth	= $pd->countAttribute($id_style) == 1 ? 600 : $grid->getOrderTableWidth($id_style);
	$sc .= ' | '.$tableWidth;
	$sc .= ' | ' . $style->getCode($id_style);
	$sc .= ' | ' . $id_style;
	echo $sc;
}


 ?>
