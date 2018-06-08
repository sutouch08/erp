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

$qs = dbQuery($qr);
if(dbNumRows($qs) < 2001)
{
  $ds = array();
  $no = 1;
  $totalQty = 0;
  $totalReceived = 0;
  $totalBalance = 0;
  $totalAmount = 0;

  while($rs = dbFetchObject($qs))
  {
    $balance = $rs->qty - $rs->received;
    $arr = array(
      'no' => $no,
      'cusName' => $rs->name,
      'reference' => $rs->reference,
      'pdCode' => $rs->code,
      'qty' => number($rs->qty),
      'received' => number($rs->received),
      'balance' => number($balance),
      'cost' => number($rs->cost, 2),
      'amount' => number($balance * $rs->cost, 2)
    );

    $no++;
    $totalQty += $rs->qty;
    $totalReceived += $rs->received;
    $totalBalance += $balance;
    $totalAmount += ($balance * $rs->cost);

    array_push($ds, $arr);
    unset($arr);
  }

  $arr = array(
    'totalQty' => number($totalQty),
    'totalReceived' => number($totalReceived),
    'totalBalance' => number($totalBalance),
    'totalAmount' => number($totalAmount, 2)
  );

  array_push($ds, $arr);
  unset($arr);
}
else
{
  $sc = FALSE;
  $message = 'ผลลัพธ์มากกว่า 2000 รายการ กรุณาใช้การส่งออกแทนการแสดงผลหน้าจอ';
}

echo $sc === TRUE ? json_encode($ds) : $message;


 ?>
