<?php
class buffer
{
  public $id;
  public $id_order;
  public $id_product;
  public $qty;
  public $id_zone;

  public function __construct($id = "")
  {
    if( $id !="")
    {
      //--- initialize
      $this->getData($id);
    }
  }





  public function getData($id)
  {
    $qs = dbQuery("SELECT * FROM tbl_buffer WHERE id = ".$id);
    if( dbNumRows($qs) == 1 )
    {
      $rs = dbFetchArray($qs);
      foreach($rs as $key => $value )
      {
        $this->$key = $value;
      }
    }
  }







  public function add($id_order, $id_pd, $id_zone, $qty)
  {
      return dbQuery("INSERT INTO tbl_buffer (id_order, id_product, id_zone, qty) VALUES (".$id_order.", '".$id_pd."', '".$id_zone."', ".$qty.")");
  }






  public function update($id_order, $id_pd, $id_zone, $qty)
  {
      return dbQuery("UPDATE tbl_buffer SET qty = qty + ".$qty." WHERE id_order = ".$id_order." AND id_product = '".$id_pd."' AND id_zone = '".$id_zone."'");
  }






  public function isExists($id_order, $id_product, $id_zone)
  {
      $sc = FALSE;
      $qs = dbQuery("SELECT id FROM tbl_prepare WHERE id_order = '".$id_order."' AND id_product ='".$id_product."' AND id_zone = '".$id_zone."'");
      if( dbNumRows($qs) == 1 )
      {
          $sc = TRUE;
      }

      return $sc;
  }






  public function updateBuffer($id_order, $id_pd, $id_zone, $qty)
  {

    if( $this->isExists($id_order, $id_pd, $id_zone))
    {
      return $this->update($id_order, $id_pd, $id_zone, $qty);
    }
    else
    {
      return $this->add($id_order, $id_pd, $id_zone, $qty);
    }

  }





  //--- ยอดรวมสินค้าที่จัดไปแล้ว
  public function getSumQty($id_order, $id_pd)
  {
    $qs = dbQuery("SELECT SUM(qty) FROM tbl_buffer WHERE id_order = ".$id_order." AND id_product = '".$id_pd."'");
    list( $qty ) = dbFetchArray($qs);
    return is_null($qty) ? 0 : $qty;
  }





}//---- End class


 ?>
