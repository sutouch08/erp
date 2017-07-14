<?php
/////////////////////////////////
///////   Warehouse Helper //////
////////////////////////////////
function selectWarehouse($se = 0 )
{
	$sc = '<option value="0">โปรดเลือก</option>';
	$qs = dbQuery("SELECT id_warehouse, code, warehouse_name FROM tbl_warehouse");
	if( dbNumRows($qs) > 0 )
	{
		while( $rs = dbFetchObject($qs) )
		{
			$sc .= '<option value="'.$rs->id_warehouse.'" '.isSelected($rs->id_warehouse, $se).'>'.$rs->code .' | ' .$rs->warehouse_name .'</option>';
		}
	}
	return $sc;
}

function selectWarehouseRole($se = 0)
{
	$sc = '<option value="0">โปรดเลือก</option>';
	$qs = dbQuery("SELECT * FROM tbl_warehouse_role");
	if(dbNumRows($qs) > 0 )
	{
		while( $rs = dbFetchObject($qs) )
		{
			$sc .= '<option value="'.$rs->id.'" '.isSelected($rs->id, $se).'>'.$rs->name.'</option>';
		}
	}
	return $sc;		
}

function getWarehouseRoleName($id)
{
	$sc = '';
	$qs = dbQuery("SELECT name FROM tbl_warehouse_role WHERE id = ".$id);
	if( dbNumRows($qs) == 1 )
	{
		list( $sc ) = dbFetchArray($qs);	
	}
	return $sc;
}

function isExistsWarehouseCode($code, $id = "")
{
	$sc = FALSE;
	
	if( $id != "" )
	{
		$qs = dbQuery("SELECT id_warehouse FROM tbl_warehouse WHERE code = '".$code."' AND id_warehouse != ".$id);
	}
	else
	{
		$qs = dbQuery("SELECT id_warehouse FROM tbl_warehouse WHERE code = '".$code."'");
	}
	if( dbNumRows($qs) > 0 )
	{
		$sc = TRUE;	
	}
	
	return $sc;
}

function isExistsWarehouseName($name, $id="")
{
	$sc = FALSE;
	if( $id != "" )
	{
		$qs = dbQuery("SELECT id_warehouse FROM tbl_warehouse WHERE warehouse_name = '".$name."' AND id_warehouse != ".$id);
	}
	else
	{
		$qs = dbQuery("SELECT id_warehouse FROM tbl_warehouse WHERE warehouse_name = '".$name."'");	
	}
	if( dbNumRows($qs) > 0 )
	{
		$sc = TRUE;
	}
	
	return $sc;
}

function getWarehouseDetail($id)
{
	return dbQuery("SELECT * FROM tbl_warehouse WHERE id_warehouse = ".$id);
}

function isEmptyWarehouse($id_warehouse)
{
	$sc = TRUE;
	$qs = dbQuery("SELECT id_zone FROM tbl_zone WHERE id_warehouse = ".$id_warehouse);
	if( dbNumRows($qs) > 0 )
	{
		$sc = FALSE;	
	}
	
	return $sc;
}

function getWarehouseCode($id)
{
	$sc = "";
	$qs = dbQuery("SELECT code FROM tbl_warehouse WHERE id_warehouse = ".$id);
	if( dbNumRows($qs) == 1 )
	{
		list( $sc ) = dbFetchArray($qs);
	}
	return $sc;
}


//-------------- คลังนี้สามารถติดลบได้หรือไม่
function isAllowUnderZero($id)
{
	$sc = FALSE;
	$qs = dbQuery("SELECT allow_under_zero FROM tbl_warehouse WHERE allow_under_zero = 1 AND id_warehouse = ".$id);
	if( dbNumRows($qs) == 1 )
	{
		$sc = TRUE;	
	}
	return $sc;		
}

function isAllowPrepare($id)
{
	$sc = FALSE;
	$qs = dbQuery("SELECT prepare FROM tbl_warehouse WHERE id_warehouse = ".$id." AND prepare = 1 ");
	if( dbNumRows($qs) == 1 )
	{
		$sc = TRUE;	
	}
	return $sc;
}

function isAllowSell($id)
{
	$sc = FALSE;
	$qs = dbQuery("SELECT sell FROM tbl_warehouse WHERE id_warehouse = ".$id." AND sell = 1");
	if( dbNumRows($qs) == 1 )
	{
		$sc = TRUE; 
	}
	return $sc;
}

function isWarehouseActive($id)
{
	$sc = FALSE;
	$qs = dbQuery("SELECT active FROM tbl_warehouse WHERE id_warehouse = ".$id." AND active = 1");
	if( dbNumRows($qs) == 1 )
	{
		$sc = TRUE;
	}
	return $sc;
}



?>