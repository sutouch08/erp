<?php

function isStockEnough($id_receive_product)
{
	$sc = TRUE;
	$cs = new receive_product();
	$stock = new stock();
	$zone = new zone();
	$qs = $cs->getDetail($id_receive_product);
	
	if( dbNumRows($qs) > 0 )
	{
		while( $rs = dbFetchObject($qs) )
		{
			$auz = $zone->isAllowUnderZero($rs->id_zone);
			$qty = $stock->getStockZone($rs->id_zone, $rs->id_product);
			if( $qty < $rs->qty && $auz === FALSE )
			{
				$sc = FALSE;	
			}
		}
	}
	else
	{
		$sc = FALSE;	
	}
	return $sc;
}

?>