<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";


if(isset($_GET['addNewStock']))
{
  $sc = TRUE;
  $id_pd = $_POST['id_product'];
  $id_zone = $_POST['id_zone'];
  $qty = $_POST['qty'];

  $qs = dbQuery("SELECT id_stock FROM tbl_stock WHERE id_product = '".$id_pd."' AND id_zone = '".$id_zone."'");
  if(dbNumRows($qs) > 0)
  {
    $sc = FALSE;
    $message = 'มีสินค้านี้ในโซนแล้ว ไม่สามารถเพิ่มใหม่ได้';
  }
  else
  {
    $qr  = "INSERT INTO tbl_stock (id_zone, id_product, qty) ";
    $qr .= "VALUES ('".$id_zone."', '".$id_pd."', ".$qty.")";
    $qs = dbQuery($qr);

    if(! $qs)
    {
      $sc = FALSE;
      $message = 'เพิ่มสต็อกไม่สำเร็จ';
    }
  }

  echo $sc === TRUE ? 'success' : $message;
}



if(isset($_GET['updateStock']))
{
  $sc = TRUE;

  $id = $_POST['id_stock'];
  $qty = $_POST['qty'];

  $qr = "UPDATE tbl_stock SET qty = ".$qty." WHERE id_stock = ".$id;
  if( dbQuery($qr) !== TRUE)
  {
    $sc = FALSE;
    $message = 'update fail';
  }

  echo $sc === TRUE ? 'success' : $message;
}



if(isset($_GET['deleteStock']))
{
  $sc = TRUE;
  $id = $_POST['id_stock'];

  $qr = "DELETE FROM tbl_stock WHERE id_stock = ".$id;

  if( dbQuery($qr) !== TRUE)
  {
    $sc = FALSE;
    $message = 'ลบรายการไม่สำเร็จ';
  }

  echo $sc === TRUE ? 'success' : $message;
}



if(isset($_GET['removeZero']))
{
  dbQuery("DELETE FROM tbl_stock WHERE qty = 0");
  echo 'done';
}


if(isset($_GET['recalStockZone']))
{
  $id_zone = $_GET['id_zone'];
  $id_product = $_GET['id_product'];

  if($id_zone != "" && $id_product != "")
  {
    recal_product($id_product, $id_zone);
  }

  if($id_zone != '' && $id_product == '')
  {
    recal_zone($id_zone);
  }
}




function recal_product($id_pd, $id_zone)
{
  $sc = TRUE;
  $mv = new movement();
  $stock = new stock();
  $buffer = new buffer();
  $cancle = new cancle();

  startTransection();

  //--- ลบสต็อกที่อยู่ในโซนออกก่อน แล้วค่อยคำนวนใหม่
  $qr = "DELETE FROM tbl_stock WHERE id_zone = {$id_zone} AND id_product = '{$id_pd}'";
  if(! dbQuery($qr) )
  {
    $sc = FALSE;
    $message = "ลบรายการเก่าในโซนไม่สำเร็จ";
  }

  //--- ดึง movement มาคำนวนใหม่
  $qr = "SELECT SUM(move_in) AS move_in, SUM(move_out) AS move_out ";
  $qr .= "FROM tbl_stock_movement ";
  $qr .= "WHERE id_zone = {$id_zone} ";
  $qr .= "AND id_product = '{$id_pd}'";
  $qs = dbQuery($qr);

  if(dbNumRows($qs) == 1)
  {
    $rs = dbFetchObject($qs);
    $qty = $rs->move_in - $rs->move_out;
    $bfQty = $buffer->get_buffer_zone_product($id_pd, $id_zone);
    $cnQty = $cancle->get_cancle_zone_product($id_pd, $id_zone);

    $qty = $qty - $bfQty - $cnQty;

    if($qty != 0 && $qty !== NULL)
    {
      if($stock->addStock($id_zone, $id_pd, $qty) === FALSE)
      {
        $sc = FALSE;
        $message = "เพิ่มยอดสินค้าใหม่เข้าโซนไม่สำเร็จ";
      }
    }
  }

  if($sc === TRUE)
  {
    commitTransection();
  }
  else
  {
    dbRollback();
  }

  endTransection();

  echo $sc === TRUE ? 'success' : $message;
}


function recal_zone($id_zone)
{
  $id_zone  = $_GET['id_zone'];
  $mv = new movement();
  $stock = new stock();
  $zone = new zone();

  //--- ลบรายการที่ไม่มี movement ออกจากโซน

  $qr  = "DELETE FROM tbl_stock ";
  $qr .= "WHERE id_zone = ".$id_zone." ";
  $qr .= "AND id_product NOT IN(SELECT id_product FROM tbl_stock_movement WHERE id_zone = ".$id_zone." GROUP BY id_product)";
  $qs  = dbQuery($qr);

  //--- ดึงข้อมูลสินค้าทั้งหมดในโซน
  $qr  = "SELECT id_product, SUM(move_in) AS move_in, SUM(move_out) AS move_out ";
  $qr .= "FROM tbl_stock_movement ";
  $qr .= "WHERE id_zone = ".$id_zone." ";
  $qr .= "GROUP BY id_product";

  $qs = dbQuery($qr);
  if(dbNumRows($qs) > 0)
  {
    while($rs = dbFetchObject($qs))
    {
      $qty = $rs->move_in - $rs->move_out;
      if($stock->isExists($id_zone, $rs->id_product) !== FALSE)
      {
        $stock->updateStock($id_zone, $rs->id_product, $qty);
      }
      else
      {
        $stock->addStock($id_zone, $rs->id_product, $qty);
      }
    }
  }

  $stock->removeZeroStock();

  echo 'success';
}


if(isset($_GET['clearFilter']))
{
  deleteCookie('pdCode');
  deleteCookie('zoneCode');
  echo 'done';
}
 ?>
