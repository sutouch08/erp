<?php
function customerGroupIn($txt)
{
	$sc = "";
	$qs = dbQuery("SELECT code FROM tbl_customer_group WHERE code LIKE '%".$txt."%' OR name LIKE '%".$txt."%'");
	if( dbNumRows($qs) > 0 )
	{
		$i = 1;
		while( $rs = dbFetchArray($qs) )
		{
			$sc 	.= $i == 1 ? "'".$rs['code']."'" : ", '".$rs['code']."'";
			$i++;
		}
	}
	else
	{
		$sc = "'".$txt."'";	
	}
	return $sc;
}


function customerAreaIn($txt)
{
	$sc = "";
	$qs = dbQuery("SELECT code FROM tbl_customer_area WHERE code LIKE '%".$txt."%' OR name LIKE '%".$txt."%'");
	if( dbNumRows($qs) > 0 )
	{
		$i = 1;
		while( $rs = dbFetchArray($qs) )
		{
			$sc .= $i == 1 ? "'".$rs['code']."'" : ", '".$rs['code']."'";
			$i++;	
		}
	}
	else
	{
		$sc = "'".$txt."'";	
	}
	return $sc;
}

?>