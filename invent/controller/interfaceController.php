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

if( isset( $_GET['syncMaster'] ) && isset( $_GET['barcode'] ) )
{
	include "interface/import/importBarcode.php";
}

if( isset( $_GET['syncMaster'] ) && isset( $_GET['warehouse'] ) )
{
	include "interface/import/importWarehouse.php";	
}

if( isset( $_GET['syncMaster'] ) && isset( $_GET['customerGroup'] ) )
{
	include "interface/import/importCustomerGroup.php";	
}

if( isset( $_GET['syncMaster'] ) && isset( $_GET['customerArea'] ) )
{
	include "interface/import/importCustomerArea.php";	
}



?>