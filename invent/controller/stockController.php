<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";


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



if(isset($_GET['clearFilter']))
{
  deleteCookie('pdCode');
  deleteCookie('zoneCode');
  echo 'done';
}
 ?>
