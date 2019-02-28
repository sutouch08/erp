<?php
//--- รายงานสินค้าคงเหลือ ณ ปัจจุบัน แสดงเป็นรายการ
$sc = array();
$allProduct = $_GET['allProduct'];
$allWarehouse = $_GET['allWhouse'];
$pdFrom     = $_GET['pdFrom'];
$pdTo       = $_GET['pdTo'];
$styleFrom  = $_GET['styleFrom'];
$styleTo    = $_GET['styleTo'];
$showItem   = $_GET['showItem'];
$whList     = $allWarehouse == 1 ? FALSE : $_GET['warehouse'];
$wh = new warehouse();
$order = new order();
$wh_in      = "";
$wh_list    = "";

if($allWarehouse != 1)
{
  $i = 1;
  foreach($whList as $id_wh)
  {
    $wh_in .= $i == 1 ? "'".$id_wh."'" : ", '".$id_wh."'";
    $wh_list .= $i == 1 ? $wh->getCode($id_wh) : ", ".$wh->getCode($id_wh);
    $i++;
  }
}


//---  Report title
$sc['reportDate'] = thaiDate(date('Y-m-d'), '/');
$sc['whList']   = $allWarehouse == 1 ? 'ทั้งหมด' : $wh_list;
$sc['productList']   = $allProduct == 1 ? 'ทั้งหมด' : ($showItem == 1 ? '('.$pdFrom.') - ('.$pdTo.')' : '('.$styleFrom.') - ('.$styleTo.')');

$qr  = "SELECT s.id_product, p.code, p.name, p.cost, SUM(s.qty) AS qty ";
$qr .= "FROM tbl_stock AS s ";
$qr .= "JOIN tbl_zone AS z ON s.id_zone = z.id_zone ";
$qr .= "JOIN tbl_warehouse AS wh ON z.id_warehouse = wh.id ";
$qr .= "JOIN tbl_product AS p ON s.id_product = p.id ";
$qr .= "LEFT JOIN tbl_product_style AS ps ON p.id_style = ps.id ";
$qr .= "LEFT JOIN tbl_color AS co ON p.id_color = co.id ";
$qr .= "LEFT JOIN tbl_size AS si ON p.id_size = si.id ";
$qr .= "WHERE s.id_zone != '' ";
$qr .= "AND wh.role IN(1,3,4,5,6) ";

if($allProduct != 1)
{
  if($showItem == 1)
  {
    $qr .= "AND p.code >= '".$pdFrom."' ";
    $qr .= "AND p.code <= '".$pdTo."' ";
  }

  if($showItem == 0)
  {
    $qr .= "AND ps.code >= '".$styleFrom."' ";
    $qr .= "AND ps.code <= '".$styleTo."' ";
  }
}

if($allWarehouse != 1)
{
  $qr .= "AND z.id_warehouse IN(".$wh_in.") ";
}

$qr .= "GROUP BY p.id ";

$qr .= "ORDER BY ps.code ASC, co.code ASC, si.position ASC";

//echo $qr;

$qs = dbQuery($qr);

$bs = array();

$rows = dbNumRows($qs);

$limit = 2000;

if($rows > $limit)
{
  echo 'ผลลัพธ์ของรายงานมีมากกว่า '.number($limit).' รายการ กรุณาส่งออกเป็นไฟล์ Excel แทนการแสดงผลหน้าจอ';
  exit;
}

if(dbNumRows($qs) > 0)
{
  $no = 1;
  $totalQty = 0;
  $totalAmount = 0;
  while($rs = dbFetchObject($qs))
  {
    $balance = $rs->qty - $order->getReservQty($rs->id_product);
    $arr = array(
      'no' => number($no),
      'pdCode' => $rs->code,
      'pdName' => $rs->name,
      'cost' => number($rs->cost, 2),
      'qty' => number($balance),
      'amount' => number($rs->cost * $balance, 2)
    );

    array_push($bs, $arr);
    $no++;

    $totalQty += $balance;
    $totalAmount += ($balance * $rs->cost);
  }

  $arr = array(
    'totalQty' => number($totalQty),
    'totalAmount' => number($totalAmount, 2)
  );

  array_push($bs, $arr);
}
else
{
  $arr = array('nodata' => 'nodata');
  array_push($bs, $arr);
}

$sc['bs'] = $bs;

echo json_encode($sc);

 ?>
