<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";

//---	รายงานสินค้าคงเหลือแยกตามโซน
if(isset($_GET['getStyleCode']))
{
	include 'autocomplete/get_style_code.php';
}


if(isset($_GET['getItemCode']))
{
	include 'autocomplete/get_item_code.php';
}


//---	รายงานสินค้าคงเหลือแยกตามโซน
if(isset($_GET['getWarehouse']))
{
	include 'autocomplete/get_warehouse.php';
}


if(isset($_GET['getWarehouseCode']))
{
	include 'autocomplete/get_warehouse_code.php';
}


//---	รายงานสินค้าคงเหลือแยกตามโซน
if(isset($_GET['getZone']))
{
	include 'autocomplete/get_zone.php';
}
?>
