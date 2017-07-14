<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";
require '../function/warehouse_helper.php';
require '../function/zone_helper.php';

if( isset( $_GET['deleteZone'] ) )
{
	$sc = 'success';
	$id_zone = $_POST['id_zone'];
	$zone = new zone();
	$rs = $zone->deleteZone($id_zone);
	if( $rs === FALSE )
	{
		$sc = 'ไม่สามารถลบได้ เนื่องจากมีทรานเซ็คชั่นเกิดขึ้นแล้ว';	
	}
	echo $sc;
}


if( isset( $_GET['updateZone'] ) )
{
	$sc = 'fail';
	$id_zone	= $_POST['id_zone'];
	if( ! isExistsZoneCode($_POST['code'], $id_zone) && ! isExistsZoneName($_POST['name'], $id_zone) )
	{
		
		$ds = array(
						'id_warehouse'	=> $_POST['id_warehouse'],
						'barcode_zone'		=> $_POST['code'],
						'zone_name'		=> $_POST['name']
						);
		$zone = new zone();
		$rs = $zone->update($id_zone, $ds);
		if( $rs === TRUE )
		{
			$sc = 'success';	
		}
	}
	
	echo $sc;
}


if( isset( $_GET['addNewZone'] ) )
{
	$sc = 'fail';
	if( ! isExistsZoneCode($_POST['code']) && ! isExistsZoneName($_POST['name']) )
	{
		$ds = array(
						'id_warehouse'	=> $_POST['id_warehouse'],
						'barcode_zone'		=> $_POST['code'],
						'zone_name'		=> $_POST['name']
						);
		$zone		= new zone();
		$rs	= $zone->add($ds);
		if( $rs !== FALSE )	 //-- It's mean id_zone returned
		{			
			$rd = getZoneDetail($rs);
			if( $rd !== FALSE )
			{
				$arr = array(
									'barcode'	=> $rd->barcode_zone,
									'zone_name'	=> $rd->zone_name,
									'warehouse_name'	=> getWarehouseCode($rd->id_warehouse) . ' | ' . get_warehouse_name_by_id($rd->id_warehouse)
									);
				$sc = json_encode($arr);
			}
		}
	}
	echo $sc;
}

if( isset( $_GET['checkBarcode'] ) )
{
	$sc = 'ok';
	$barcode = $_GET['barcode'];
	$id_zone = isset( $_GET['id_zone'] ) ? $_GET['id_zone'] : '';
	if( isExistsZoneCode($barcode, $id_zone) === TRUE )
	{
		$sc = 'duplicate';
	}
	echo $sc;
}

if( isset( $_GET['checkName'] ) )
{
	$sc = 'ok';
	$name	= $_GET['name'];
	$id_zone		= isset( $_GET['id_zone'] ) ? $_GET['id_zone'] : '';
	if( isExistsZoneName($name, $id_zone) === TRUE )
	{
		$sc = 'duplicate';
	}
	echo $sc;
}


if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('zCode');
	deleteCookie('zName');
	deleteCookie('zWH');
	echo 'done';	
}



//********************* ตรวจสอบชื่อซ้ำกันหรือไม่ ก่อนเพิ่มหรือแก้ไข ********************************//
if(isset($_GET['zone_name'])&&isset($_GET['id_warehouse'])&&isset($_GET['id_zone'])){
	$zone_name = $_GET['zone_name'];
	$id_warehouse = $_GET['id_warehouse'];
	$id_zone = $_GET['id_zone'];
	if($id_zone != ""){
		$row =dbNumRows(dbQuery("SELECT zone_name FROM tbl_zone WHERE id_zone != $id_zone AND zone_name='$zone_name' AND id_warehouse=$id_warehouse"));
	}else{
		$row =dbNumRows(dbQuery("SELECT zone_name FROM tbl_zone WHERE zone_name='$zone_name' AND id_warehouse=$id_warehouse"));
	}
	if($row >0){
		$message ="1";
		echo $message;
	}else{
		$message ="0";
		echo $message;
	}
}


//********************************** ตรวจสอบบาร์โค้ดซ้ำก่อน เพิ่มหรือแก้ไข **********************************//
if(isset($_GET['barcode_zone'])&&isset($_GET['id_warehouse'])&&isset($_GET['id_zone'])){
	$barcode_zone = $_GET['barcode_zone'];
	$id_warehouse = $_GET['id_warehouse'];
	$id_zone = $_GET['id_zone'];
	if($id_zone !=""){
		$row =dbNumRows(dbQuery("SELECT barcode_zone FROM tbl_zone WHERE id_zone != $id_zone AND barcode_zone='$barcode_zone'"));
	}else{
		$row =dbNumRows(dbQuery("SELECT barcode_zone FROM tbl_zone WHERE barcode_zone='$barcode_zone' "));
	}
	if($row >0){
		$message ="1";
		echo $message;
	}else{
		$message ="0";
		echo $message;
	}
}



if(isset($_REQUEST['term'])){
	if(isset($_GET['get_consign_zone'])){
		$qstring = "SELECT id_zone, zone_name FROM tbl_zone WHERE zone_name LIKE '%".$_REQUEST['term']."%'";
		$rs = dbQuery($qstring);
		if($rs->num_rows >0)
		{
			$data = array();
			while($row = dbFetchArray($rs))
			{
				$data[] = $row['zone_name'].":".$row['id_zone'];
			}
			echo json_encode($data);//format the array into json data
		}else{
			echo "error";
		}
	}else{
			$qstring = "SELECT zone_name FROM tbl_zone WHERE zone_name LIKE '%".$_REQUEST['term']."%'";
			$result = dbQuery($qstring);//query the database for entries containing the term
		if ($result->num_rows>0)
			{
				$data= array();
			while($row = $result->fetch_array())//loop through the retrieved values
				{
						$data[] = $row['zone_name'];
				}
				echo json_encode($data);//format the array into json data
			}else {
				echo "error";
			}
	}
}
?>