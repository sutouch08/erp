<?php
class consign_check
{
  public $id;
  public $reference;
  public $id_customer;
  public $id_zone;
  public $d_employee;
  public $status;
  public $valid;
  public $date_add;
  public $date_upd;
  public $emp_upd;
  public $id_consign;
  public $remark;
  public $error;

  public function __construct($id='')
  {
    if($id != '')
    {
      $this->getData($id);
    }
  }


  public function getData($id)
  {
    $qs = dbQuery("SELECT * FROM tbl_consign_check WHERE id = '".$id."'");
    if(dbNumRows($qs) == 1)
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
    if(!empty($ds))
    {
      $fields = "";
      $values = "";
      $i = 1;

      foreach($ds as $field => $value)
      {
        $fields .= $i == 1 ? $field : ", ".$field;
        $values .= $i == 1 ? "'".$value."'" : ", '".$value."'";
        $i++;
      }

      $qs = dbQuery("INSERT INTO tbl_consign_check (".$fields.") VALUES (".$values.")");

      $sc = $qs === TRUE ? dbInsertId() : FALSE;
    }

    return $sc;
  }



  public function update($id, array $ds = array())
  {
    
  }



  //-----------------  New Reference --------------//
  public function getNewReference($date = '')
  {
    $date     = $date == '' ? date('Y-m-d') : $date;
    $Y		    = date('y', strtotime($date));
    $M		    = date('m', strtotime($date));
    $runDigit = getConfig('RUN_DIGIT');
    $prefix   = getConfig('PREFIX_CONSIGN_CHECK');
    $preRef   = $prefix . '-' . $Y . $M;

    $qs = dbQuery("SELECT MAX(reference) AS reference FROM tbl_consign_check WHERE reference LIKE '".$preRef."%' ORDER BY reference DESC");

    list( $ref ) = dbFetchArray($qs);

    if( ! is_null( $ref ) )
    {
      $runNo = mb_substr($ref, ($runDigit*(-1)), NULL, 'UTF-8') + 1;
      $reference = $prefix . '-' . $Y . $M . sprintf('%0'.$runDigit.'d', $runNo);
    }
    else
    {
      $reference = $prefix . '-' . $Y . $M . sprintf('%0'.$runDigit.'d', '001');
    }

    return $reference;
  }


}

 ?>
