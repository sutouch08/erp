<?php
class discount_policy
{
  public $id;
  public $reference;
  public $name;
  public $date_start;
  public $date_end;
  public $isApproved;
  public $id_emp;
  public $approver;
  public $approve_key;
  public $date_add;
  public $date_upd;
  public $emp_upd;
  public $active;
  public $isDeleted;
  
  public function __construct($id = '')
  {
    if( $id != '')
    {
      $this->getData($id);
    }
  }



  public function getData($id)
  {
    $qs = dbQuery("SELECT * FROM tbl_discount_policy WHERE id = ".$id);
    if(dbNumRows($qs) == 1)
    {
      $rs = dbFetchArray($qs);
      foreach ($rs as $key => $value)
      {
        $this->$key = $value;
      }
    }
  }

}

 ?>
