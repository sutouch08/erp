<?php
/**
 * ตัดยอดฝากขาย
 */
class consign
{
  //--- ไอดีเอกสาร
  public $id;

  //--- เลขทีเอกสาร
  public $reference;

  //--- ไอดีลูกค้า
  public $id_customer;

  //--- ไอดีพนักงาน
  public $id_employee;

  //--- โซนฝากขาย
  public $id_zone;

  //--- Shop warrix (กรณีตัดยอดขายจาก shop)
  public $id_shop;

  //--- กรณีตัดยอดขายจากงานขาย retail
  public $id_event;

  //--- หมายเหตุเอกสาร
  public $remark;

  //--- วันที่เอกสาร
  public $date_add;

  //--- ยกเลิกแล้วหรือไม่
  public $isCancle = 0;

  //--- ส่งข้อมูลไป formula แล้วหรือไม่
  public $isExport = 0;

  //--- บันทึกแล้วหรือไม่
  public $isSaved = 0;

  //--- ไอดีเอกสารกระทบยอด (กรณีนำเข้ายอดจากการกระทบยอด)
  public $id_consign_check;

  //--- พนักงานที่แก้ไขเอกสาร
  public $emp_upd;

  //--- วันที่ปรับปรุงเอกสารล่าสุด
  public $date_upd;

  //--- เปิดใบกำกับภาษีหรือไม่ (ส่งออก SO หรือไม่)
  public $is_so = 1;



  public function __construct($id='')
  {
    if( $id )
    {
      $this->getData($id);
    }
  }







  public function getData($id)
  {
    $qs = dbQuery("SELECT * FROM tbl_consign WHERE id = '".$id."'");
    if( dbNumRows($qs) == 1 )
    {
      $rs = dbFetchArray($qs);
      if( ! empty($rs) )
      {
        foreach($rs as $key => $value)
        {
          $this->$key = $value;
        }
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

      $sc = dbQuery("INSERT INTO tbl_consign (".$fields.") VALUES (".$values.")");
    }

    return $sc === TRUE ? dbInsertId() : FALSE;
  }




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

      $sc = dbQuery("INSERT INTO tbl_consign_detail (".$fields.") VALUES (".$values.")");
    }

    return $sc;
  }




  public function getDetails($id)
  {
    return dbQuery("SELECT * FROM tbl_consign_detail WHERE id_consign = '".$id."'");
  }


  public function deleteDetail($id)
  {
    return dbQuery("DELETE FROM tbl_consign_detail WHERE id = '".$id."'");
  }



  public function update($id, array $ds = array())
  {
    $sc = FALSE;
    if(!empty($ds))
    {
      $set = "";
      $i = 1;
      foreach($ds as $field => $value)
      {
        $set .= $i == 1 ? $field." = '".$value."'" : ", ".$field." = '".$value."'";
        $i++;
      }

      $sc = dbQuery("UPDATE tbl_consign SET ".$set." WHERE id = '".$id."'");
    }

    return $sc;
  }





  //-----------------  New Reference --------------//
  public function getNewReference($date = '')
  {
    $date     = $date == '' ? date('Y-m-d') : $date;
    $Y		    = date('y', strtotime($date));
    $M		    = date('m', strtotime($date));
    $runDigit = getConfig('RUN_DIGIT');
    $prefix   = getConfig('PREFIX_CONSIGN_SOLD');
    $preRef   = $prefix . '-' . $Y . $M;

    $qs = dbQuery("SELECT MAX(reference) AS reference FROM tbl_consign WHERE reference LIKE '".$preRef."%' ORDER BY reference DESC");

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


  public function getProductGP($id_pd, $id_zone)
  {
    $qr  = "SELECT MAX(gp) FROM tbl_order_consign AS c JOIN tbl_order_detail AS d ON c.id_order = d.id_order ";
    $qr .= "WHERE id_product = '".$id_pd."' AND id_zone = '".$id_zone."' ";
    $qr .= "AND valid = 1 AND isSaved = 1 AND is_expired = 0";

    $qs = dbQuery($qr);
    list( $rs ) = dbFetchArray($qs);

    return is_null($rs) ? 0 : $rs;

  }



  public function setSaved($id, $option)
  {
    return dbQuery("UPDATE tbl_consign SET isSaved = '".$option."' WHERE id = '".$id."'");
  }


  public function setCancle($id, $option)
  {
    return dbQuery("UPDATE tbl_consign SET isCancle = '".$option."' WHERE id = '".$id."'");
  }


  public function setExport($id, $option)
  {
    return dbQuery("UPDATE tbl_consign SET isExport = '".$option."' WHERE id = '".$id."'");
  }



}


 ?>
