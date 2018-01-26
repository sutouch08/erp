<?php

$order = new order();
$qs = $order->getOverDateOrder();
if(dbNumRows($qs) > 0)
{
  startTransection();
  while($rs = dbFetchObject($qs))
  {
    $sc = TRUE;
    if($order->setOrderExpired($rs->id) !== TRUE)
    {
      $sc = FALSE;
    }

    if($order->setOrderDetailExpired($rs->id) !== TRUE)
    {
      $sc = FALSE;
    }

    if($sc === TRUE)
    {
      commitTransection();
    }
    else
    {
      dbRollback();
    }

  }//--- End while

  endTransection();

}

echo 'done';

 ?>
