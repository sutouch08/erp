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




function clearBuffer($id_order)
{
	$sc = TRUE;

	$buffer = new buffer();

	//---	ลบรายการที่มียอดเป็น 0 ทิ้ง
	$sc = $buffer->dropZero($id_order);

	//---	เคลียร์ยอดที่เหลือไปเข้า cancle
	$qs = $buffer->getBuffer($id_order);
	if( dbNumRows($qs) > 0 )
	{
		$cancle = new cancle_zone();
		while( $rs = dbFetchObject($qs))
		{
			//---	เพิ่มรายการเข้า cancle ถ้าไม่มี insert ถ้ามี update
			$cn = $cancle->updateCancle($id_order, $rs->id_style, $rs->id_product, $rs->id_zone, $rs->id_warehouse, $rs->qty);

			//---	delete buffer row
			$cb = $buffer->delete($rs->id);

			if( $cn === FALSE OR $cb === FALSE)
			{
				$sc = FALSE;
			}
		}
	}

	return $sc;
}


 ?>
