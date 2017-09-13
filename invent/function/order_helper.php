<?php
function stateColor($state, $status)
{
	$sc = '';
	$st  = new state();
	if( $status == 1 )
	{
		$sc = 'style="color:#FFF; background-color: '.$st->getColor($state).'"';
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
?>