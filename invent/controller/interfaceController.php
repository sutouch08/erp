<?php
require '../../library/config.php';
require '../../library/functions.php';
require "../function/tools.php";
require "../../library/class/PHPExcel.php";

//---------  Sync Product Group -------//
if( isset( $_GET['syncMaster'] ) && isset( $_GET['product_group'] ) )
{
	include "interface/import/importProductGroup.php";		
}


//----------- Sync Unit ------------//
if( isset( $_GET['syncMaster'] ) && isset( $_GET['unit'] ) )
{
	include "interface/import/importUnit.php";	
}


//------------ Barcode --------------//
if( isset( $_GET['syncMaster'] ) && isset( $_GET['barcode'] ) )
{
	include "interface/import/importBarcode.php";
}


//------------- Warehouse ---------------//
if( isset( $_GET['syncMaster'] ) && isset( $_GET['warehouse'] ) )
{
	include "interface/import/importWarehouse.php";	
}


//--------------------- Customer Group ------------------//
if( isset( $_GET['syncMaster'] ) && isset( $_GET['customerGroup'] ) )
{
	include "interface/import/importCustomerGroup.php";	
}


//---------------------- Customer Area -------------------------//
if( isset( $_GET['syncMaster'] ) && isset( $_GET['customerArea'] ) )
{
	include "interface/import/importCustomerArea.php";	
}


//----------------- Customer --------------------//
if( isset( $_GET['syncMaster'] ) && isset( $_GET['customer'] ) )
{
	include "interface/import/importCustomer.php";
}


//------------- Sale Team -----------------//
if( isset( $_GET['syncMaster'] ) && isset( $_GET['saleGroup'] ) )
{
	include "interface/import/importSaleGroup.php";	
}


//---------------  Sale  --------------------//
if( isset( $_GET['syncMaster'] ) && isset( $_GET['sale'] ) )
{
	include "interface/import/importSale.php";	
}


//--------------  Supplier Group  -----------------//
if( isset( $_GET['syncMaster'] ) && isset( $_GET['supplierGroup'] ) )
{
	include "interface/import/importSupplierGroup.php";	
}

?>