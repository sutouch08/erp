<?php
$isAll = $_GET['allItems'];
$fCode = $_GET['from_code'];
$tCode = $_GET['to_code'];

$fromCode = $fCode > $tCode ? $tCode : $fCode;
$toCode = $fCode > $tCode ? $fCode : $tCode;

$from = fromDate($_GET['fromDate']);
$to = toDate($_GET['toDate']);
$sc = array();

$qr  = "SELECT rp.reference, pd.code, z.barcode_zone, z.zone_name, rp.date_add, rd.qty AS qty, (rd.qty * rd.cost) AS amount ";
$qr .= "FROM tbl_receive_product_detail AS rd ";
$qr .= "LEFT JOIN tbl_receive_product AS rp ON rd.id_receive_product = rp.id ";
$qr .= "LEFT JOIN tbl_product AS pd ON rd.id_product = pd.id ";
$qr .= "LEFT JOIN tbl_color AS co ON pd.id_color = co.id ";
$qr .= "LEFT JOIN tbl_size AS si ON pd.id_size = si.id ";
$qr .= "LEFT JOIN tbl_zone AS z ON rd.id_zone = z.id_zone ";
$qr .= "WHERE rp.date_add >= '".$from."' AND rp.date_add <= '".$to."' ";
$qr .= "AND rp.isCancle = 0 AND rd.is_cancle = 0 ";

if($isAll == 0)
{
  $qr .= "AND pd.code >= '".$fromCode."' AND pd.code <= '".$toCode."' ";
}

$qr .= "ORDER BY rp.reference, pd.id_style, co.code, si.position ASC";

//echo $qr;

$qs = dbQuery($qr);

if(dbNumRows($qs) > 0)
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
      'item' => $rs->code,
      'zone' => $rs->zone_name,
      'qty' => number($rs->qty),
      'amount' => number($rs->amount, 2)
    );

    array_push($sc, $arr);
    $no++;
    $totalQty += $rs->qty;
    $totalAmount += $rs->amount;
  }

  $arr = array(
    'totalQty' => number($totalQty),
    'totalAmount' => number($totalAmount, 2)
  );

  array_push($sc, $arr);
}
else
{
  $arr = array('nodata' => 'nodata');
  array_push($sc, $arr);
}

echo json_encode($sc);

 ?>
