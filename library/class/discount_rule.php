<?php
class discount_rule
{
  public $id = 0;
  public $code;
  public $name;
  public $id_discount_policy = 0;


  public function __construct(){

  }

  public function getData($id)
  {
    $qs = dbQuery("SELECT * FROM tbl_discount_rule WHERE id = ".$id);
    if(dbNumRows($qs) == 1)
    {
      $rs = dbFetchArray($qs);

      foreach ($rs as $key => $value)
      {
          $this->$key = $value;
      }
    }
  }



  public function getPolicyId($id)
  {
    $sc = 0;
    $qs = dbQuery("SELECT id_discount_policy FROM tbl_discount_rule WHERE id = ".$id);
    if( dbNumRows($qs) == 1 )
    {
      list( $sc ) = dbFetchArray($qs);
    }

    return $sc;
  }




  public function getPolicyCode($id)
  {
    $sc = '';
    $qs = dbQuery("SELECT p.reference FROM tbl_discount_rule AS r JOIN tbl_discount_policy AS p ON r.id_discount_policy = p.id WHERE r.id = ".$id);
    if( dbNumRows($qs) == 1 )
    {
      list( $sc ) = dbFetchArray($qs);
    }

    return $sc;
  }



  public function getPolicyName($id)
  {
    $sc = '';
    $qs = dbQuery("SELECT p.name FROM tbl_discount_rule AS r JOIN tbl_discount_policy AS p ON r.id_discount_policy = p.id WHERE r.id = ".$id);
    if( dbNumRows($qs) == 1 )
    {
      list( $sc ) = dbFetchArray($qs);
    }

    return $sc;
  }



  public function getCode($id)
  {
    $sc = '';
    $qs = dbQuery("SELECT code FROM tbl_discount_rule");
  }

}

 ?>
