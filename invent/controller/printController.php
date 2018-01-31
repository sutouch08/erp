<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";
include '../function/order_helper.php';
include '../function/customer_helper.php';


if(isset($_GET['printOrderSheet']))
{
  include '../print/order/print_order_sheet.php';
}



if(isset($_GET['printConsignBox']))
{
  include '../print/packing/print_consign_box.php';
}

 ?>
