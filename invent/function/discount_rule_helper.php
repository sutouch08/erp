<?php

function showItemDiscountLabel($item_price, $item_disc, $unit)
{
	$disc = 0.00;
	//---	ถ้าเป็นการกำหนดราคาขาย
	if($item_price > 0)
	{
		$disc = 'Price '.$item_price;
	}
	else
	{
		$symbal = $unit == 'percent' ? '%' : getConfig('CURRENCY');
		$disc = '- '.$item_disc.' '.$symbal;
	}

	return $disc;
}


function customerRule($rule)
{
  if($rule->all_customer == 0)
  {
    $sc = '<a href="javascript:void(0)" onclick="viewCustomerRule('.$rule->id.')">View Details</a>';
  }
  else
  {
    $sc = 'All';
  }

  return $sc;
}
 ?>
