<?php
class return_received
{
  public $id;
  public $bookcode;
  public $code;
  public $reference;
  public $invoice;
  public $id_supplier;
  public $id_warehouse;
  public $id_zone;
  public $id_style;
  public $id_product;
  public $product_code;
  public $price;
  public $qty;
  public $unit_code;
  public $umqty;
  public $bill_discount;
  public $amount_ex;
  public $vat_amount;
  public $valid = 0;
  public $isCancle = 0;

  public function __construct($reference = '')
  {
    if( $reference != '')
    {
      $this->getData($reference);
    }
  }


  public function getData($reference)
  {
    $qs = dbQuery("SELECT * FROM tbl_return_received WHERE reference = '".$reference."' LIMIT 1");
    if( dbNumRows($qs) == 1)
    {
      $rs = dbFetchArray($qs);
      foreach($rs as $key => $value)
      {
        $this->$key = $value;
      }
    }
  }



  public function add(array $ds = array())
  {
    $sc = FALSE;

    if( !empty($ds))
    {
      $fields = "";
      $values = "";
      $i      = 1;

      foreach($ds as $field => $value)
      {
        $fields .= $i == 1 ? $field : ", ".$field;
        $values .= $i == 1 ? "'".$value."'" : ", '".$value."'";
        $i++;
      }

      $sc = dbQuery("INSERT INTO tbl_return_received (".$fields.") VALUES (".$values.")");

    }

    return $sc;
  }


  public function delete($id)
  {
    return dbQuery("DELETE FROM tbl_return_received WHERE id = '".$id."'");
  }



  public function getDetails($reference)
  {
    return dbQuery("SELECT * FROM tbl_return_received WHERE reference = '".$reference."'");
  }



  public function getId($bookcode, $reference, $product_code)
  {
    $sc  = FALSE;

    $qr  = "SELECT id FROM tbl_return_received ";
    $qr .= "WHERE bookcode = '".$bookcode."' ";
    $qr .= "AND reference = '".$reference."' ";
    $qr .= "AND product_code = '".$product_code."'";

    $qs = dbQuery($qr);

    if( dbNumRows($qs) == 1)
    {
      list( $sc ) = dbFetchArray($qs);
    }

    return $sc;
  }



  public function isExists($bookcode, $reference, $id_product)
  {
    $sc  = FALSE;
    $qr  = "SELECT id FROM tbl_return_received ";
    $qr .= "WHERE bookcode = '".$bookcode."' ";
    $qr .= "AND reference = '".$reference."' ";
    $qr .= "AND id_product = '".$id_product."'";

    $qs = dbQuery($qr);
    if( dbNumRows($qs) == 1)
    {
      $sc = TRUE;
    }

    return $sc;
  }



  //----  เปลี่ยนโซนรับเข้า
  public function setZone($reference, $id_zone)
  {
    return dbQuery("UPDATE tbl_return_received SET id_zone = '".$id_zone."' WHERE reference = '".$reference."' AND valid = 0 AND isCancle = 0");
  }


  public function updateReceived($bookcode, $reference, $id_product, $qty)
  {
    return dbQuery("UPDATE tbl_return_received SET received = '".$qty."' WHERE bookcode = '".$bookcode."' AND reference = '".$reference."' AND id_product = '".$id_product."'");
  }



  public function setValid($reference, $valid)
  {
    return dbQuery("UPDATE tbl_return_received SET valid = '".$valid."' WHERE reference = '".$reference."'");
  }

} //--- endclass

 ?>
