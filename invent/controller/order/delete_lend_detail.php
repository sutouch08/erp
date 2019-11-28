<?php

	//---	ลบรายการสั่งซื้อ
	$rs = $order->deleteDetail($id);

	echo ($rs === TRUE) ? 'success' : 'Can not delete please try again';

?>
