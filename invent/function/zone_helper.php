<?php
function isExistsZoneCode($code, $id = '')
{
	$zone = new zone();
	return $zone->isExistsZoneCode($code, $id);
}





function isExistsZoneName($name, $id = '')
{
	$zone = new zone();
	return $zone->isExistsZoneName($name, $id);
}




function getZoneDetail($id)
{
	$zone = new zone();
	return $zone->getZoneDetail($id);
}


?>
