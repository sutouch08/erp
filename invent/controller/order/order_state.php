<?php

    $sc = FALSE;
    $message = 'เปลี่ยนสถานะไม่สำเร็จ';
    $order = new order($_POST['id_order']);
    $state = $_POST['state'];

    //---- ถ้ายังไม่ได้จัด
    if( $order->state < 4)
    {
        $sc = $order->stateChange($order->id, $state);
    }

    echo $sc === TRUE ? 'success' : $message;

    
?>    