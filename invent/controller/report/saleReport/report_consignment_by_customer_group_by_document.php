<?php
$cusFrom = $_GET['cusFrom'];
$cusTo   = $_GET['cusTo'];
$docFrom   = $_GET['docFrom'];
$docTo     = $_GET['docTo'];
$allDocument = $_GET['allDocument'];
$fromDate = fromDate($_GET['fromDate']);
$toDate   = toDate($_GET['toDate']);
$limit    = 1000;
$sc = TRUE;

$qr  = "SELECT customer_code, customer_name, reference, ";
$qr .= "SUM(qty) AS qty, SUM(total_amount_inc) AS amount ";
$qr .= "FROM tbl_order_sold ";
$qr .= "WHERE id_role = 2 ";
$qr .= "AND customer_code >= '".$cusFrom."' ";
$qr .= "AND customer_code <= '".$cusTo."' ";

if($allDocument == 0)
{
  $qr .= "AND reference >= '{$docFrom}' AND reference <= '{$docTo}'";
}

$qr .= "AND date_add >= '".$fromDate."' ";
$qr .= "AND date_add <= '".$toDate."' ";
$qr .= "GROUP BY reference";


$qs = dbQuery($qr);

$rows = dbNumRows($qs);

if($rows > $limit)
{
  $sc = FALSE;
  $message = 'ผลลัพธ์มากกว่า 1000 รายการ กรุณาใช้การส่งออกเป็นไฟล์ Excel แทนการแสดงผลหน้าจอ';
}
else
{
  if(dbNumRows($qs) > 0)
  {
    $ds = array();
    $no = 1;
    $totalQty = 0;
    $totalAmount = 0;
    while($rs = dbFetchObject($qs))
    {
      $arr = array(
        'no' => $no,
        'customer' => $rs->customer_code.' : '.$rs->customer_name,
        'reference' => $rs->reference,
        'qty' => number($rs->qty),
        'amount' => number($rs->amount, 2)
      );
      array_push($ds, $arr);
      $no++;
      $totalQty += $rs->qty;
      $totalAmount += $rs->amount;
    }

    $arr = array(
      'totalQty' => number($totalQty),
      'totalAmount' => number($totalAmount, 2)
    );

    array_push($ds, $arr);
  }
  else
  {
    $ds = array('nodata' => 'nodata');
  }
}


echo $sc === TRUE ? json_encode($ds) : $message;

 ?>
