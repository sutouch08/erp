<?php
function statusLabel($isCancle = 0, $isExport = 0, $isSaved = 0, $is_so)
{

	$rs = '';
	if( $isExport == 0 && $is_so == 1)
	{
		$rs = 'NE';
	}

	if( $isSaved == 0)
	{
		$rs = 'NC';
	}

	if( $isCancle == 1)
	{
		$rs = 'CN';
	}

	$sc  = '<span class="red">';
	$sc .= $rs;
	$sc .= '</span>';

	return $sc;
}



?>
