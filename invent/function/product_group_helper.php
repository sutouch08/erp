<?php

function selectProductGroup($id = "")
{
	$option = '<option value="">เลือกกลุ่มสินค้า</option>';
	$pg = new product_group();
	$qs = $pg->getProductGroup();
	if( dbNumRows($qs) > 0 )
	{
		while( $rs = dbFetchObject($qs) )
		{
			$option .= '<option value="'.$rs->id.'" '.isSelected($id, $rs->id).'>'.$rs->name.'</option>';
		}
	}
	return $option;
}


function selectSubGroup($id='')
{
	$option = '<option value="">เลือกกลุ่มย่อยสินค้า</option>';
	$pg = new product_sub_group();
	$qs = $pg->getSubGroup();
	if( dbNumRows($qs) > 0 )
	{
		while( $rs = dbFetchObject($qs) )
		{
			$option .= '<option value="'.$rs->id.'" '.isSelected($id, $rs->id).'>'.$rs->name.'</option>';
		}
	}
	return $option;
}

?>
