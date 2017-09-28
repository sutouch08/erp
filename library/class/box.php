<?php
class box
{
  public function __construct(){

  }



  public function getBox($barcode, $id_order)
  {
    $sc = $this->isExists($barcode, $id_order);

    if( $sc === FALSE )
    {
      $sc = $this->add($barcode, $id_order);
    }

    return $sc;
  }





  public function isExists($barcode, $id_order)
  {
    $sc = FALSE;
    $qs = dbQuery("SELECT id_box FROM tbl_box WHERE barcode = '".$barcode."' AND id_order = '".$id_order."'");
    if( dbNumRows($qs) == 1)
    {
      list( $sc ) = dbFetchArray($qs);
    }

    return  $sc;
  }





  public function add($barcode, $id_order)
  {
    $sc = FALSE;
    $qs = dbQuery("INSERT INTO tbl_box (barcode, id_order) VALUES ('".$barcode."', ".$id_order.")");
    if( $qs === TRUE )
    {
      $sc = dbInsertId();
    }
    return $sc;
  }






} //--- end class


 ?>
