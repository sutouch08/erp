<?php

//---	ถ้าเป็นลูกค้าออไลน์ ให้ใส่ชื่อลูกค้าออนไลน์ไปด้วย
function orderCustomerName($id_customer, $online_code)
{
	$customer = new customer();
	$name = $customer->getName($id_customer);
	return $online_code != '' ? '[ '.$online_code.'] '.$name : $name;
}




function stateColor($state, $status)
{
	$sc = '';
	$st  = new state();
	if( $status == 1 )
	{
		$sc = $st->stateColor($state);
	}
	return $sc;
}


function stateName($state, $status)
{
	$sc = "ยังไม่บันทึก";
	$st = new state();
	if( $status == 1 )
	{
		$sc = $st->getName($state);
	}
	return $sc;
}



//---	ชื่อของ role
function roleName($role)
{
	$sc = "";
	$order = new order();
	$qs = $order->roleName($role);
	if( dbNumRows($qs) == 1 )
	{
		list( $sc ) = dbFetchArray($qs);
	}
	return $sc;
}




//--- แสดงป้ายส่วนลด
function discountLabel($disc)
{
	$arr = explode('%', $disc);
	if( count($arr) > 1)
	{
		return number_format(trim($arr[0]),2).' %';
	}
	else
	{
		return number_format($arr[0],2);
	}
}


function getSpace($amount, $length)
{
	$sc = '';
	$i	= strlen($amount);
	$m	= $length - $i;
	while($m > 0 )
	{
		$sc .= '&nbsp;';
		$m--;
	}
	return $sc.$amount;
}


function selectHour($se = '')
{
	$sc	= '';
	$hour = array('00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23');
	foreach($hour as $rs)
	{
		$sc .= '<option value="'.$rs.'" '.isSelected($rs, $se).'>'.$rs.'</option>';
	}
	return $sc;
}



function selectMin($se = '' )
{
	$sc = '<option value="00">00</option>';
	$m = 59;
	$i 	= 1;
	while( $i <= $m )
	{
		$ix = $i < 10 ? '0'.$i : $i;
		$sc .= '<option value="'.$ix.'" '.isSelected($se, $ix).'>'.$ix.'</option>';
		$i++;
	}
	return $sc;
}

?>
