<?php

$sc = TRUE;
$allCustomer = $_GET['allCustomer'];
$role = 4; //--- สปอนเซอร์
$fromCode = $_GET['fromCode']; //--- รหัสลูกค้า
$toCode = $_GET['toCode']; //-- รหัสลูกค้า

$fromDate = fromDate($_GET['fromDate']);
$toDate = toDate($_GET['toDate']);
$year = $_GET['year'];

$ds = array();


$qr  = "SELECT o.reference, o.customer_code, o.customer_name, SUM(o.qty) AS qty, SUM(o.total_amount_inc) AS amount, o.date_add ";
$qr .= "FROM tbl_order_sold AS o ";
$qr .= "LEFT JOIN tbl_sponsor_budget AS b ON o.id_budget = b.id ";
$qr .= "WHERE o.id_role IN(".$role.") ";
if($year != 0)
{
  $qr .= "AND b.year = '{$year}' ";
}

if($allCustomer == 0)
{
  $qr .= "AND customer_code >= '".$fromCode."' ";
  $qr .= "AND customer_code <= '".$toCode."' ";
}


$qr .= "AND date_add >= '".$fromDate."' ";
$qr .= "AND date_add <= '".$toDate."' ";

$qr .= "GROUP BY reference ";

$qr .= "ORDER BY customer_code ASC, reference ASC";


$qs = dbQuery($qr);

$rows = dbNumRows($qs);
if($rows > 0)
{
  if($rows > 2000)
  {
    $sc = FALSE;
    $message = 'ข้อมูลมีปริมาณมากเกินกว่าจะแสดงผลได้ กรุณาส่งออกข้อมูลแทนการแสดงผลหน้าจอ';
  }
  else
  {
    $no = 1;
    $totalQty = 0;
    $totalAmount = 0;
    while($rs = dbFetchObject($qs))
    {
      $arr = array(
        'no' => $no,
        'date_add' => thaiDate($rs->date_add),
        'reference' => $rs->reference,
        'customer' => $rs->customer_code.' : '.$rs->customer_name,
        'qty' => number($rs->qty),
        'amount' => number($rs->amount,2)
      );

      array_push($ds, $arr);
      $no++;
      $totalQty += $rs->qty;
      $totalAmount += $rs->amount;
    }

    $arr = array(
      'totalQty' => number($totalQty),
      'totalAmount' => number($totalAmount,2)
    );

    array_push($ds, $arr);
  }

}
else
{
  $ds = array(
    'nodata' => 'nodata'
  );

}

echo $sc === TRUE ? json_encode($ds) : $message;

 ?>
