<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";
include "../../library/db.php";

if(isset($_GET['export_to_ix']))
{
  $sc = TRUE;
  $error = "";
  $id = $_POST['id'];
  $order = new order($id);
  if(!empty($order->id))
  {
    $cs = new customer();
    $customer = $cs->getDataById($order->id_customer);
    //----- เอารหัสลูกค้าไปดึงข้อมูลลูกค้าใน IX โดยใช้ field old_code
    $sql = db2Query("SELECT * FROM customers WHERE old_code = '{$customer->code}'");
    $rows = db2NumRows($sql);
    if($rows == 0)
    {
      $sc = FALSE;
      $error = "ไม่พบลูกค้า {$customer->code} ในระบบ IX กรุณาตรวจสอบ";
    }
    else if($rows > 1)
    {
      $sc = FALSE;
      $error = "รหัสเก่าลูกค้า {$customer->code} ใน IX มี {$rows} คน";
    }
    else if($rows == 1)
    {
      $ixCustomer = db2FetchObject($sql);

      print_r($ixCustomer);
    }
  }

}

 ?>
