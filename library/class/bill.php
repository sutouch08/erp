<?php
class bill
{
  public function __construct(){}

  public function getBillDetail($id_order)
  {
    $qr = "SELECT o.id_product, o.product_code, o.product_name, o.qty AS order_qty, ";
    $qr .= "o.price, o.discount, ";
    $qr .= "(o.discount_amount / o.qty) AS discount_amount, ";
    $qr .= "(o.total_amount/o.qty) AS final_price, ";
    $qr .= "(SELECT SUM(qty) FROM tbl_prepare WHERE id_order = ".$id_order." AND id_product = o.id_product) AS prepared, ";
    $qr .= "(SELECT SUM(qty) FROM tbl_qc WHERE id_order = ".$id_order." AND id_product = o.id_product) AS qc ";
    $qr .= "FROM tbl_order_detail AS o ";
    $qr .= "WHERE o.id_order = ".$id_order." GROUP BY o.id_product";

    return dbQuery($qr);
  }


} //--- End class

 ?>
