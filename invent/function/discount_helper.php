<?php
function showDiscountByProductGroup($id_customer, $id_product_group)
{
	$sc = 0.00;
	$qs = dbQuery("SELECT discount FROM tbl_customer_discount WHERE id_customer = '".$id_customer."' AND id_product_group = '".$id_product_group."'");
	if( dbNumRows($qs) > 0 )
	{
		list( $sc ) = dbFetchArray($qs);
	}
	return $sc;
}


function getDiscountLabel($p_disc = 0, $a_disc = 0)
{
	return $p_disc > 0 ? $p_disc .' %' : $a_disc;
}


function getDiscountAmount($p_disc, $a_disc, $price)
{
	$disc = $p_disc > 0 ? ($p_disc * 0.01) * $price : $a_disc;
	return $disc;
}


//--- return discount label
function discountPercentToLabel($discount)
{
	$discLabel = '';
	//------ คำนวณส่วนลดใหม่
	$step = explode('+', $discount);
	$i = 0;
	foreach($step as $discText)
	{
		if($i < 3) //--- limit ไว้แค่ 3 เสต็ป
		{
			$disc = explode('%', $discText);
			$disc[0] = trim($disc[0]); //--- ตัดช่องว่างออก
			$discLabel .= $i === 0 ? $disc[0].'%' : '+'.$disc[0].'%';
		}
		$i++;
	}

	return $discLabel;
}


//--- return discount amount per item
function parseDiscount($discountText, $price)
{
	$discAmount = 0;
	$discLabel = array(0, 0, 0);
	$step = explode('+', $discountText);
	$i = 0;
	foreach($step as $discText)
	{
		if($i < 3)
		{
			$disc = explode('%', trim($discText));
			$disc[0] = trim($disc[0]);
			$discount = count($disc) === 1 ? ($disc[0] > $price ? $price : $disc[0]) : ($disc[0] > 100 ? $price : $price * ($disc[0] * 0.01)); //--- ส่วนลดต่อชิ้น เป็นจำนวนเงิน
			$discLabel[$i] = count($disc) == 1 ? $disc[0] : $disc[0].'%';
			$discAmount += $discount;
			$price -= $discount;
		}
		$i++;
	}

	$ds = array(
		'discAmount' => $discAmount,
		'discLabel1' => $discLabel[0],
		'discLabel2' => $discLabel[1],
		'discLabel3' => $discLabel[2]
	);

	return $ds;
}

?>
