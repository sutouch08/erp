<?php
$allProduct = $_GET['allProduct'];
$allZone    = $_GET['allZone'];
$pdFrom     = $_GET['pdFrom'];
$pdTo       = $_GET['pdTo'];
$id_wh      = $_GET['id_warehouse'];
$id_zone    = $_GET['id_zone'];

$qr  = "SELECT z.zone_name, p.code, p.name, s.qty ";
$qr .= "FROM tbl_stock AS s ";
$qr .= "LEFT JOIN tbl_zone AS z ON s.id_zone = z.id_zone ";
$qr .= "LEFT JOIN tbl_product AS p ON s.id_product = p.id ";
$qr .= "LEFT JOIN tbl_product_style AS ps ON p.id_style = ps.id ";
$qr .= "WHERE s.id_zone != '' ";

if($allProduct != 1)
{
  $qr .= "AND ps.code >= '".$pdFrom."' ";
  $qr .= "AND ps.code <= '".$pdTo."' ";
}

if($allZone != 1 && $id_zone != '')
{
  $qr .= "AND s.id_zone = '".$id_zone."' ";
}

if($id_wh != '' && $allZone == 1)
{
  $qr .= "AND id_warehouse = '".$id_wh."' ";
}

$qr .= "ORDER BY z.zone_name ASC, ps.code ASC";

//echo $qr;
$qs = dbQuery($qr);

$sc = array();

if(dbNumRows($qs) > 0)
{
  $no = 1;
  $totalQty = 0;
  while($rs = dbFetchObject($qs))
  {
    $arr = array(
      'no' => number($no),
      'zone' => $rs->zone_name,
      'reference' => $rs->code,
      'productName' => $rs->name,
      'qty' => number($rs->qty)
    );

    array_push($sc, $arr);
    $no++;
    $totalQty += $rs->qty;
  }

  $arr = array('totalQty' => number($totalQty));
  array_push($sc, $arr);
}
else
{
  $arr = array('nodata' => 'nodata');
  array_push($sc, $arr);
}

echo json_encode($sc);


 ?>
