<?php

function get_ix_customer($code)
{
  $qs = db2Query("SELECT * FROM customers WHERE old_code = '{$code}' OR code = '{$code}'");
  $rows = db2NumRows($qs);
  if($rows === 0)
  {
    return NULL;
  }
  else if($rows > 1)
  {
    $rows;
  }
  else
  {
    return db2FetchObject($qs);
  }

}


//--- add ix qc
function add_ix_qc(array $ds = array())
{
  if(!empty($ds))
  {
    $qr = get_insert_query('qc', $ds);
    return db2Query($qr);
  }

  return FALSE;
}


//--- get box id
function get_ix_box_id($barcode, $order_code)
{
  $qs = db2Query("SELECT id FROM qc_box WHERE code = '{$barcode}' AND order_code = '{$order_code}'");
  if(db2NumRows($qs) == 1)
  {
    $rs = db2FetchObject($qs);
    return $rs->id;
  }
  else
  {
    $qs = db2Query("INSERT INTO qc_box (code, order_code) VALUES ('{$barcode}', '{$order_code}')");
    return db2InsertId();
  }

  return FALSE;

}



//---- add buffer to ix
function add_ix_buffer(array $ds = array())
{
  if(!empty($ds))
  {
    $qr = get_insert_query('buffer', $ds);
    return db2Query($qr);
  }

  return FALSE;
}


//---- add prepare data to ix
function add_ix_prepare(array $ds = array())
{
  if(!empty($ds))
  {
    $qr = get_insert_query('prepare', $ds);
    return db2Query($qr);
  }

  return FALSE;
}

//--- add order to ix
function add_ix_order(array $ds = array())
{
  if(!empty($ds))
  {
    $qr = get_insert_query('orders', $ds);
    return db2Query($qr);
  }

  return FALSE;
}


//--- insert order detail
function add_ix_order_detail(array $ds = array())
{
  if(!empty($ds))
  {
    $qr = get_insert_query('order_details', $ds);
    return db2Query($qr);
  }

  return FALSE;
}


function add_ix_move(array $ds = array())
{
  if(!empty($ds))
  {
    $qr = get_insert_query('move', $ds);
    return db2Query($qr);
  }

  return FALSE;
}


function add_ix_move_detail(array $ds = array())
{
  if(!empty($ds))
  {
    return db2Query(get_insert_query('move_detail', $ds));
  }

  return FALSE;
}



function add_ix_transfer(array $ds = array())
{
  if(!empty($ds))
  {
    $qr = get_insert_query('transfer', $ds);
    return db2Query($qr);
  }

  return FALSE;
}


function add_ix_transfer_detail(array $ds = array())
{
  if(!empty($ds))
  {
    return db2Query(get_insert_query('transfer_detail', $ds));
  }

  return FALSE;
}


//--- add movement in
function add_ix_move_in(array $ds = array())
{
  if(!empty($ds))
  {
    return db2Query(get_insert_query('stock_movement', $ds));
  }

  return FALSE;
}


//--- add movement out
function add_ix_move_out(array $ds = array())
{
  if(!empty($ds))
  {
    return db2Query(get_insert_query('stock_movement', $ds));
  }

  return FALSE;
}


function add_ix_wm(array $ds = array())
{
  if(!empty($ds))
  {
    $qr = get_insert_query('consignment_order', $ds);
    return db2Query($qr);
  }

  return FALSE;
}


function add_ix_wm_detail(array $ds = array())
{
  if(!empty($ds))
  {
    return db2Query(get_insert_query('consignment_order_detail', $ds));
  }

  return FALSE;
}


function get_ix_item($code)
{
  $qr = "SELECT * FROM products WHERE old_code = '{$code}' OR code = '{$code}' AND active = 1 ";
  $qs = db2Query($qr);
  if(db2NumRows($qs) === 1)
  {
    return db2FetchObject($qs);
  }

  return NULL;
}


function get_ix_zone($code)
{
  $qr = "SELECT * FROM zone WHERE old_code = '{$code}' OR code = '{$code}'";
  $qs = db2Query($qr);
  if(db2NumRows($qs) === 1)
  {
    return db2FetchObject($qs);
  }

  return NULL;
}


function get_IX_Warehouse($code)
{
  $qr = "SELECT * FROM warehouse WHERE old_code = '{$code}' OR code = '{$code}'";
  $qs = db2Query($qr);
  if(db2NumRows($qs) === 1)
  {
    return db2FetchObject($qs);
  }
  return NULL;
}


function get_IX_role($role_id, $is_so = 1)
{
  $role = 'S';

  switch($role_id)
  {
    case '1' :
      $role = 'S';
    break;
    case '2' :
      $role = $is_so == 1 ? 'C' : 'N';
    break;
    case '3' :
      $role = 'U';
    break;
    case '4' :
      $role = 'P';
    break;
    case '5' :
      $role = 'Q';
    break;
    case '6' :
      $role = 'L';
    break;
    case '7' :
      $role = 'R';
    break;
    case '8' :
      $role = 'M';
    break;
    default :
      $role = 'S';
    break;
  }

  return $role;
}


function get_IX_channels_code($id_channels)
{
  $channels = new channels($id_channels);
  $qr = "SELECT * FROM channels WHERE code = '{$channels->code}'";
  $qs = db2Query($qr);
  if(db2NumRows($qs) == 1)
  {
    $rs = db2FetchObject($qs);
    return $rs->code;
  }

  return NULL;
}


function get_IX_payment($id)
{
  $payment = new payment_method($id);
  $qr = "SELECT * FROM payment_method WHERE code = '{$payment->code}'";
  $qs = db2Query($qr);
  if(db2NumRows($qs) == 1)
  {
    return db2FetchObject($qs);
  }

  return NULL;
}


//--- check is order code is exists in ix
function is_exists_in_ix($code)
{
  $qr = "SELECT code FROM orders WHERE code = '{$code}'";
  $qs = db2Query($qr);
  if(db2NumRows($qs) > 0)
  {
    return TRUE;
  }

  return FALSE;
}

function is_mv_exists_in_ix($code)
{
  $qr = "SELECT code FROM move WHERE code = '{$code}'";
  $qs = db2Query($qr);
  if(db2NumRows($qs) > 0)
  {
    return TRUE;
  }

  return FALSE;
}


function is_consign_exists_in_ix($code)
{
  $qr = "SELECT code FROM consignment_order WHERE code = '{$code}'";
  $qs = db2Query($qr);
  if(db2NumRows($qs) > 0)
  {
    return TRUE;
  }

  return FALSE;
}


function is_tr_exists_in_ix($code)
{
  $qr = "SELECT code FROM transfer WHERE code = '{$code}'";
  $qs = db2Query($qr);
  if(db2NumRows($qs) > 0)
  {
    return TRUE;
  }

  return FALSE;
}

 ?>
