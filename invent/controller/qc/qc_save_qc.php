<?php
  $id_order = $_POST['id_order'];
  $id_box		= $_POST['id_box'];
  $product	= $_POST['product'];
  $sc 			= TRUE;

  if( ! empty($product) )
  {
    startTransection();
    $qc = new qc();
    foreach($product as $id_product => $qty)
    {
      if( $qty > 0)
      {
        if($qc->updateChecked($id_order, $id_box, $id_product, $qty) === FALSE )
        {
          $sc = FALSE;
        }
      }
    }
    if($sc === TRUE )
    {
      commitTransection();
    }
    else
    {
      dbRollback();
    }
    endTransection();
  }
  echo $sc === TRUE ? 'success' : 'fail';
 ?>
