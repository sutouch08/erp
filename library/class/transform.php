<?php
class transform
{
  public $id;
  public $id_order;
  public $id_zone;
  public $role; //--- 1 = Sell,  2 = Sponsor OR Support, 3 = Keep Stock

  public function __construct($id='')
  {
    if( $id !='')
    {
      $this->getData($id);
    }
  }



  public function getData($id)
  {
    $qs = dbQuery("SELECT * FROM tbl_order_transform WHERE id_order ='".$id."'");
    if( dbNumRows($qs) == 1 )
    {
      $rs = dbFetchArray($qs);
      foreach($rs as $key => $value)
      {
        $this->$key = $value;
      }
    }
  }



  //--- Add order_transform
  public function add( array $ds = array())
  {
    $sc = FALSE;
    if( !empty($ds))
    {
      $fields = "";
      $values = "";
      $i = 1;
      foreach($ds as $field => $value )
      {
        $fields .= $i == 1 ? $field : ', ' . $field;
        $values .= $i == 1 ? "'".$value."'" : ", '".$value."'";
        $i++;
      }

      $sc = dbQuery("INSERT INTO tbl_order_transform (".$fields.") VALUES (".$values.")");
    }

    return $sc;
  }





  //---   update order transform
  public function update($id_order, array $ds = array())
  {
    $sc = FALSE;
    if( ! empty($ds))
    {
      $set = "";
      $i   = 1;
      foreach ($ds as $field => $value)
      {
        $set .= $i == 1 ? $field ." = '".$value."'" : ", ". $field ." = '".$value."'";
        $i++;
      }

      $sc = dbQuery("UPDATE tbl_order_transform SET ".$set." WHERE id_order = '".$id_order."'");
    }

    return $sc;
  }




  //--- update หรือ เพื่ม การเชื่อมโยงสินค้าทีละรายการ
  public function addDetail($id_order_detail, $id_product, array $ds = array())
  {
    $sc = FALSE;
    if( ! empty($ds))
    {
      if( $this->isExists($id_order_detail, $id_product) === TRUE )
      {
        $sc = $this->updateDetail($id_order_detail, $id_product, $ds['qty']);
      }
      else
      {
        $sc = $this->insertDetail($ds);
      }
    }

    return $sc;
  }




  //--- update การเชื่อมโยงสินค้าทีละรายการ
  public function updateDetail($id_order_detail, $id_product, $qty)
  {
    return dbQuery("UPDATE tbl_order_transform_detail SET qty = qty + " . $qty." WHERE id_order_detail = ".$id_order_detail." AND id_product = '".$id_product."'");
  }




  //--- เพื่มการเชื่อมโยงสินค้าทีละรายการ
  public function insertDetail(array $ds = array())
  {
    $sc = FALSE;
    if( !empty($ds))
    {
      $fields = "";
      $values = "";
      $i = 1;
      foreach($ds as $field => $value )
      {
        $fields .= $i == 1 ? $field : ', ' . $field;
        $values .= $i == 1 ? "'".$value."'" : ", '".$value."'";
        $i++;
      }

      $sc = dbQuery("INSERT INTO tbl_order_transform_detail (".$fields.") VALUES (".$values.")");
    }

    return $sc;
  }



  //--- ตรวจสอบว่ามีการเชื่อมโยงสินค้าแล้วหรือยัง
  public function isExists($id_order_detail, $id_product)
  {
    $sc = FALSE;
    $qs = dbQuery("SELECT id FROM tbl_order_transform_detail WHERE id_order_detail = ".$id_order_detail." AND id_product = '".$id_product."'");
    if( dbNumRows($qs) > 0)
    {
      $sc = TRUE;
    }

    return $sc;
  }




  //--- ลบการเชื่อมโยงเฉพาะ 1 รายการสินค้าแปรสภาพ
  public function removeTransformProduct($id_order_detail, $id_product)
  {
    return dbQuery("DELETE FROM tbl_order_transform_detail WHERE id_order_detail = ".$id_order_detail." AND id_product = '".$id_product."'");
  }



  //--- ลบการเชื่อมโยง 1 รายการสั่งแปรสภาพ (อาจมีหลายสินค้าแปรสภาพ)
  public function removeTransformDetail($id_order_detail)
  {
    return dbQuery("DELETE FROM tbl_order_transform_detail WHERE id_order_detail = ".$id_order_detail);
  }




  //--- อยากรู้ว่ามีการเชื่อมโยงสินค้าอะไรแล้วบ้างในรายการนี้
  public function getTransformProducts($id_order_detail)
  {
    return dbQuery("SELECT * FROM tbl_order_transform_detail WHERE id_order_detail = '.$id_order_detail.'");
  }





  //--- เชื่อมโยงสินค้าไปแล้วเท่าไร
  public function getSumTransformProductQty($id_order_detail)
  {
    $qs = dbQuery("SELECT SUM(qty) FROM tbl_order_transform_detail WHERE id_order_detail = '".$id_order_detail."'");
    list( $qty ) = dbFetchArray($qs);

    return is_null($qty) ? 0 : $qty;
  }


} //--- end class

 ?>
