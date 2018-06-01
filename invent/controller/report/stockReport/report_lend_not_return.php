<?php
$sc = TRUE;

$allLender = $_GET['allLender'];
$lender = $_GET['lender'];

$allProduct = $_GET['allProduct'];
$pdFrom = $_GET['pdFrom'];
$pdTo = $_GET['pdTo'];

$from = fromDate($_GET['fromDate']);
$to = toDate($_GET['toDate']);

$qr  = "SELECT cus.name, od.reference, pd.code, ld.qty, ld.received, pd.cost ";
$qr .= "FROM tbl_order_lend_detail AS ld ";
$qr .= "JOIN tbl_order AS od ON ld.id_order = od.id ";
$qr .= "JOIN tbl_product AS pd ON ld.id_product = pd.id ";
$qr .= "JOIN tbl_customer AS cus ON od.id_customer = cus.id ";
$qr .= "WHERE ld.received < ld.qty ";
$qr .= "AND od.date_add >= '".$from."' ";
$qr .= "AND od.date_add <= '".$to."' ";

if($allLender == 0)
{
  $qr .= "AND cus.id = '".$lender."' ";
}

if($allProduct == 0)
{
  $qr .= "AND pd.code >= '".$pdFrom."' ";
  $qr .= "AND pd.code <= '".$pdTo."' ";
}

$qr .= "ORDER BY cus.name ASC, pd.code ASC";

echo $qr;

 ?>
