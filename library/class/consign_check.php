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
    $sc = FALSE;
    if(! empty($ds))
    {
      $set = "";
      $i = 1;
      foreach($ds as $field => $value)
      {
        $set .= $i == 1 ? $field." = '".$value."'" : ", ".$field." = '".$value."'";
        $i++;
      }

      $sc = dbQuery("UPDATE tbl_consign_check SET ".$set." WHERE id = '".$id."'");
      $this->error = $sc === TRUE ? '' : dbError();
    }

    return $sc;
  }


  //----- ดึงรายละเอียดทั้งเอกสาร
  public function getDetails($id)
  {
    return dbQuery("SELECT * FROM tbl_consign_check_detail WHERE id_consign_check = '".$id."'");
  }




  //----- เพิ่มรายการกระทบยอด
  public function addDetail(array $ds = array())
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

      $sc = dbQuery("INSERT INTO tbl_consign_check_detail (".$fields.") VALUES (".$values.")");
      $this->error = $sc === TRUE ? '' : $this->dbError();
    }

    return $sc;
  }





  public function updateCheckedQty($id_consign_check, $id_pd, $qty)
  {
    $qr  = "UPDATE tbl_consign_check_detail ";
    $qr .= "SET qty = qty + ".$qty." ";
    $qr .= "WHERE id_consign_check = '".$id_consign_check."' ";
    $qr .= "AND id_product = '".$id_pd."' ";

    return dbQuery($qr);
  }




  public function addConsignBoxDetail(array $ds = array())
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

      $sc = dbQuery("INSERT INTO tbl_consign_box_detail (".$fields.") VALUES (".$values.")");
      if($sc !== TRUE)
      {
        $this->error = dbError();
      }
    }

    return $sc;
  }







  public function updateConsignBoxDetail($id_consign_box, $id_consign_check, $id_pd, $qty)
  {
    $sc = FALSE;

    $id = $this->getConsignBoxDetailId($id_consign_box, $id_consign_check, $id_pd);

    if($id === FALSE)
    {
      $arr = array(
        'id_consign_box' => $id_consign_box,
        'id_consign_check' => $id_consign_check,
        'id_product' => $id_pd,
        'qty' => $qty
      );

      $sc = $this->addConsignBoxDetail($arr);
    }
    else
    {
      $sc = dbQuery("UPDATE tbl_consign_box_detail SET qty = qty + ".$qty." WHERE id = '".$id."'");
    }

    return $sc;
  }




  public function getConsignBoxDetailId($id_consign_box, $id_consign_check, $id_pd)
  {
    $sc = FALSE;
    $qr  = "SELECT id FROM tbl_consign_box_detail ";
    $qr .= "WHERE id_consign_box = '".$id_consign_box."' ";
    $qr .= "AND id_consign_check = '".$id_consign_check."' ";
    $qr .= "AND id_product = '".$id_pd."' ";

    $qs = dbQuery($qr);

    if(dbNumRows($qs) == 1)
    {
      list($sc) = dbFetchArray($qs);
    }

    return $sc;
  }






  public function getConsignBox($id_consign_check, $barcode)
  {
    $id = $this->getBoxId($id_consign_check, $barcode);

    if($id === FALSE)
    {
      $id = $this->addNewBox($id_consign_check, $barcode);
    }

    $qs = dbQuery("SELECT * FROM tbl_consign_box WHERE id = '".$id."'");
    if(dbNumRows($qs) == 1)
    {
      $sc = dbFetchObject($qs);
    }
    else
    {
      $sc = FALSE;
    }

    return $sc;
  }






  private function getNextBoxNo($id_consign_check)
  {
    $qs = dbQuery("SELECT MAX(box_no) FROM tbl_consign_box WHERE id_consign_check = '".$id_consign_check."'");

    list($box_no) = dbFetchArray($qs);

    return is_null($box_no) ? 1 : $box_no +1;
  }






  public function addNewBox($id_consign_check, $barcode)
  {
    $box_no = $this->getNextBoxNo($id_consign_check);
    $qr  = "INSERT INTO tbl_consign_box ";
    $qr .= "(barcode, id_consign_check, box_no) ";
    $qr .= "VALUES ('".$barcode."', '".$id_consign_check."', '".$box_no."')";
    $qs = dbQuery($qr);

    return $qs === TRUE ? dbInsertId() : FALSE;
  }






  //--- check if box in document exists will be return id_box
  private function getBoxId($id_consign_check, $barcode)
  {
    $sc = FALSE;
    $qs = dbQuery("SELECT id FROM tbl_consign_box WHERE id_consign_check = '".$id_consign_check."' AND barcode = '".$barcode."'");
    if(dbNumRows($qs) == 1)
    {
      list($sc) = dbFetchArray($qs);
    }

    return $sc;
  }





  public function getQtyInBox($id_box, $id_check)
  {
    $qs = dbQuery("SELECT SUM(qty) AS qty FROM tbl_consign_box_detail WHERE id_consign_box = '".$id_box."' AND id_consign_check = '".$id_check."'");
    list($qty) = dbFetchArray($qs);

    return is_null($qty) ? 0 : $qty;
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
