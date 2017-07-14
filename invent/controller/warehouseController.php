<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";
require '../function/warehouse_helper.php';

if( isset( $_POST['deleteWarehouse'] ) && isset( $_POST['id_warehouse'] ) )
{
	$sc = 'success';
	$id_warehouse	= $_POST['id_warehouse'];
	$warehouse = new warehouse();
	if( $warehouse->deleteWarehouse($id_warehouse) === FALSE )
	{
		$sc = 'ข้อผิดพลาด ! | ลบคลังสินค้าไม่สำเร็จ หรือ คลังสินค้าไม่ว่าง';
	}
	echo $sc;		
}



if( isset( $_POST['addNew'] ) && isset( $_POST['whCode'] ) )
{
	$sc = 'success';
	$ds	= array(
						'code'	 	=> $_POST['whCode'],
						'warehouse_name'		=> $_POST['whName'],
						'role'		=> $_POST['whRole'],
						'sell'		=> $_POST['sell'],
						'prepare'	=> $_POST['prepare'],
						'allow_under_zero'		=> $_POST['underZero'],
						'active'	=> $_POST['active']
						);
	$warehouse		= new warehouse();		
	$rs 	= $warehouse->add($ds);
	if( $rs === FALSE )
	{
		$sc = 'เพิ่มคลังสินค้าไม่สำเร็จ';
	}
	echo $sc;
}


if( isset( $_POST['editWarehouse'] ) && isset( $_POST['id_warehouse'] ) )
{
	$sc = 'success';
	$id_warehouse	= $_POST['id_warehouse'];
	$ds	= array(
						'role'		=> $_POST['whRole'],
						'sell'		=> $_POST['sell'],
						'prepare'	=> $_POST['prepare'],
						'allow_under_zero'		=> $_POST['underZero'],
						'active'	=> $_POST['active']
						);
	$warehouse		= new warehouse();		
	$rs 	= $warehouse->update($id_warehouse, $ds);
	if( $rs === FALSE )
	{
		$sc = 'ปรับปรุงข้อมูลไม่สำเร็จ';
	}
	echo $sc;
}


if( isset( $_POST['checkCode'] ) && isset( $_POST['whCode'] ) )
{
	$whCode = $_POST['whCode'];
	$id			= $_POST['id_warehouse']; /// May be blank
	$sc		= 'success';		   
	$rs 		= isExistsWarehouseCode($whCode, $id);
	if( $rs === TRUE )
	{
		$sc = 'duplicate';
	}
	echo $sc;
}

if( isset( $_POST['checkName'] ) && isset( $_POST['whName'] ) )
{
	$sc 			= 'success';
	$whName 	= $_POST['whName'];
	$id				= $_POST['id_warehouse'];
	$rs 			= isExistsWarehouseName($whName, $id);
	if( $rs === TRUE )
	{
		$sc = 'duplicate';
	}
	echo $sc;
}


if( isset( $_POST['resetSearch'] ) )
{
	deleteCookie('whCode');
	deleteCookie('whName');
	deleteCookie('whRole');
	deleteCookie('underZero');
	echo 'success';	
}

?>