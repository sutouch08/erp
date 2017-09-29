<?php
function selectRole($role = '')
{
	$sc = '';
	$qs = dbQuery("SELECT * FROM tbl_order_role");
	if( dbNumRows($qs) )
	{
		while($rs = dbFetchObject($qs))
		{
			$sc .= '<option value="'.$rs->id.'" '.isSelected($role, $rs->id).'>'.$rs->name.'</option>';
		}
	}
	return $sc;
}


 ?>
