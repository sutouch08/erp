<?php
$id_warehouse = $_GET['id_warehouse'];

$sc 		 = array();

$stock   = new stock();

$barcode = new barcode();

$cancle = new cancle_zone();

$zone = new zone();

$qs 		 = $cancle->getDetailsByWarehouse($id_warehouse);

if( dbNumRows($qs) > 0 )
{
  $no = 1;
  while( $rs = dbFetchObject($qs) )
  {
    $arr = array(
          "no"				 => $no,
          "id_cancle" 	 => $rs->id,
          "id_product" => $rs->id_product,
          "barcode" 	 => $barcode->getBarcode($rs->id_product),
          "products" 	 => $rs->code,
          "zone"       => $zone->getName($rs->id_zone),
          "qty" 			 => $rs->qty,
          );

    array_push($sc, $arr);

    $no++;
  }
}
else
{
  array_push($sc, array("nodata" => "nodata"));
}
echo json_encode($sc);

 ?>
