<?php
function isExistsZoneCode($code, $id = '')
{
	$sc = FALSE;
	if( $id != '' )
	{
		$qs = dbQuery("SELECT id_zone FROM tbl_zone WHERE barcode_zone = '".$code."' AND id_zone != ".$id);
	}
	else
	{
		$qs = dbQuery("SELECT id_zone FROM tbl_zone WHERE barcode_zone = '".$code."'");
	}
	if( dbNumRows($qs) > 0 )
	{
		$sc = TRUE;	
	}
	
	return $sc;
}


function isExistsZoneName($name, $id = '')
{
	$sc = FALSE;
	if( $id != '' )
	{
		$qs = dbQuery("SELECT id_zone FROM tbl_zone WHERE zone_name = '".$name."' AND id_zone != ".$id);	
	}
	else
	{
		$qs = dbQuery("SELECT id_zone FROM tbl_zone WHERE zone_name = '".$name."'");	
	}
	if( dbNumRows($qs) > 0 )
	{
		$sc = TRUE;
	}
	
	return $sc;
}

function getZoneDetail($id)
{
	$sc = FALSE;
	$qs = dbQuery("SELECT * FROM tbl_zone WHERE id_zone = ".$id);
	if( dbNumRows($qs) == 1 )
	{
		$sc = dbFetchObject($qs);	
	}
	
	return $sc;
}

?>