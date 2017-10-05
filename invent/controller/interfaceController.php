<?php
require '../../library/config.php';
require '../../library/functions.php';
require "../function/tools.php";
require "../../library/class/PHPExcel.php";


///============================================= MASTER ==========================================///
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


//------------------  Supplier  -----------------------//
if( isset( $_GET['syncMaster'] ) && isset( $_GET['supplier'] ) )
{
	include "interface/import/importSupplier.php";
}


//----------------- Product Style ------------//
if( isset( $_GET['syncMaster'] ) && isset( $_GET['style'] ) )
{
	include "interface/import/importStyle.php";
}



//----------------- Product Color ------------//
if( isset( $_GET['syncMaster'] ) && isset( $_GET['color'] ) )
{
	include "interface/import/importColor.php";
}



//----------------- Product Size ------------//
if( isset( $_GET['syncMaster'] ) && isset( $_GET['size'] ) )
{
	include "interface/import/importSize.php";
}


//--------------- Product Brand ----------------//
if( isset( $_GET['syncMaster'] ) && isset( $_GET['brand'] ) )
{
	include "interface/import/importBrand.php";
}


//---------------- Products -------------------------------//
if( isset( $_GET['syncMaster'] ) && isset( $_GET['product'] ) )
{
	include "interface/import/importProduct.php";
}


//---------------- Customer Credit -------------------------------//
if( isset( $_GET['syncMaster'] ) && isset( $_GET['customerCredit'] ) )
{
	include "interface/import/importCustomerCredit.php";
}



///==================================================== END MASTER ==========================================///


///=================================================== DOCUMENTS ==========================================///
if( isset( $_GET['syncDocument'] ) && isset( $_GET['po'] ) )
{
	include "interface/import/importPO.php";
}


//---	Export ใบรับสินค้าเข้า
if( isset( $_GET['export'] ) && isset( $_GET['BI'] ) )
{
	include '../function/vat_helper.php';
	include "interface/export/exportBI.php";
	$id_receive_product = $_POST['id_receive_product'];
	$BI = exportBI($id_receive_product);
	if( $BI === TRUE )
	{
		echo "success";
	}
	else
	{
		echo $BI;
	}
}



//---	Export Sale order
if( isset( $_GET['export']) && isset( $_GET['SO']))
{
	include '../function/vat_helper.php';
	include 'interface/export/exportSO.php';
	$id_order = $_POST['id_order'];
	$SO = exportSO($id_order);
	echo $SO === TRUE ? 'success' : $SO;
}
///=================================================== END DOCUMENTS =======================================///
?>
