<?php
//------------  Transform helper -------//
function selectTransformRole($se = '')
{
	$sc  = '';
	$sc .= '<option value="1" '. isSelected($se, 1) .'>เพื่อขาย</option>';
	$sc .= '<option value="2" '. isSelected($se, 2) .'>เพื่ออภินันท์</option>';
	$sc .= '<option value="3" '. isSelected($se, 3) .'>เพื่อสต็อก</option>';
	return $sc;
}



function getTransformProducts($id_order_detail)
{
	$sc = '';
	$pd = new product();
	$cs = new transform();
	$qs = $cs->getTransformProducts($id_order_detail);
	if( dbNumRows($qs) > 0)
	{
		while($rs = dbFetchObject($qs))
		{
			$code = $pd->getCode($rs->id_product);
			$sc .= '<span class="display-block">';
			$sc .= $code.' : '.$rs->qty;

			//---	ถ้ายังไม่ได้รับสินค้า สามารถลบได้
			if( $rs->received == 0)
			{
				$sc .= '<span class="red pointer" onclick="removeTransformProduct('.$id_order_detail.', \''.$rs->id_product.'\', \''.$code.'\')">  <i class="fa fa-times">';
				$sc .= '</i></span>';
			}

			$sc .= '</span>';
		}
	}

	return $sc;
}

?>
