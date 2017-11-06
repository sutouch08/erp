<?php
class transfer
{
  //---
  public $id;

  //--- เลขที่เอกสาร
  public $reference;

  //--- คลังต้นทาง
  public $from_warehouse;

  //--- คลังปลายทาง
  public $to_warehouse;

  //--- พนักงานที่เพิ่มเอกสาร
  public $id_employee;

  //--- วันที่เอกสาร
  public $date_add;

  //--- วันที่ปรับปรุง
  public $date_upd;

  //--- พนักงานที่แก้ไขล่าสุด
  public $emp_upd;

  //--- หมายเหตุหัวเอกสาร
  public $remark;

  //--- ยกเลิกเอกสารหรือไม่
  public $isCancle = 0;

  //--- ส่งออกไป formula แล้วหรือยัง
  public $isExport = 0;


  public function __construct($id = '')
  {
    if($id !='')
    {
      $this->getData($id);
    }
  }


  //--- inititailize
  public function getData($id)
  {
    $qs = dbQuery("SELECT * FROM tbl_transfer WHERE id = '".$id."'");
    if( dbNumRows($qs) == 1)
    {
      $rs = dbFetchArray($qs);
      foreach ($rs as $key => $value)
      {
        $this->$key = $value;
      }
    }
  }


  //--- เพิ่มเอกสารใหม่
  public function add(array $ds = array() )
	{
		$sc = FALSE;
		if( ! empty( $ds ) )
		{
			$fields = "";
			$values = "";
			$i = 1;
			foreach( $ds as $field => $value )
			{
				$fields .= $i == 1 ? $field : ", ".$field;
				$values .= $i == 1 ? "'".$value."'" : ", '".$value."'";
				$i++;
			}
			$sc = dbQuery("INSERT INTO tbl_transfer (".$fields.") VALUES (".$values.")");
		}

		return $sc === TRUE ? dbInsertId() : FALSE;
	}






	//----- แก้ไขเอกสาร
	public function update($id, array $ds)
	{
		$sc = FALSE;
		if( count( $ds ) > 0 )
		{
			$set = "";
			$i = 1;
			foreach( $ds as $field => $value )
			{
				$set .= $i == 1 ? $field ." = '".$value."'" : ", ". $field." = '".$value."'";
				$i++;
			}
			$sc = dbQuery("UPDATE tbl_transfer SET ". $set ." WHERE id = ".$id);
		}
		return $sc;
	}






  //--- update tbl_tranfer_detail
  //--- insert if not exists
  //--- update if exists
  public function updateDetail(array $ds = array())
  {
    $sc = FALSE;
    if( ! empty($ds))
    {
      //--- return id if exists
      //--- return false if not exists
      $id = $this->isExistsDetail($ds['id_transfer'], $ds['id_product'], $ds['from_zone']);
      if(  $id === FALSE )
      {
        //--- do insert
        $sc = $this->insertTransferDetail($ds);
      }
      else
      {
        //--- update by using id
        $sc = $this->updateTransferDetail($id, $ds['qty']);
      }
    }

    return $sc;
  }


  //--- เพิ่มรายการใหม่
  public function insertTransferDetail(array $ds = array())
  {
    $sc = FALSE;
		if( ! empty( $ds ) )
		{
			$fields = "";
			$values = "";
			$i = 1;
			foreach( $ds as $field => $value )
			{
				$fields .= $i == 1 ? $field : ", ".$field;
				$values .= $i == 1 ? "'".$value."'" : ", '".$value."'";
				$i++;
			}
			$sc = dbQuery("INSERT INTO tbl_transfer_detail (".$fields.") VALUES (".$values.")");
		}

		return $sc === TRUE ? dbInsertId() : FALSE;
  }



  //--- Update detail
  public function updateTransferDetail($id, $qty)
	{
		return dbQuery("UPDATE tbl_transfer_detail SET qty = qty + ".$qty." WHERE id = ".$id);
	}


  //--- เปลียนโซนปลายทางให้ถูกต้อง
  //--- เปลี่ยนสถานะรายการเป็น ย้ายเข้าปลายทางแล้ว (valid = 1) tbl_transfer_detail
  public function validDetail($id, $id_zone)
  {
    return dbQuery("UPDATE tbl_transfer_detail SET to_zone = ".$id_zone.", valid = 1 WHERE id = '".$id."'");
  }




  public function updateTemp(array $ds = array())
  {
    $sc = FALSE;
    if( ! empty($ds))
    {
      //--- return id if exists
      //--- return false if not exists
      $id = $this->isExistsTemp($ds['id_transfer_detail']);

      if( $id === FALSE)
      {
        $sc = $this->insertTransferTemp($ds);
      }
      else
      {

        $sc = $this->updateTransferTemp($id, $ds['qty']);
      }
    }

    return $sc;
  }





  public function insertTransferTemp(array $ds = array())
  {
    $sc = FALSE;
		if( ! empty( $ds ) )
		{
			$fields = "";
			$values = "";
			$i = 1;
			foreach( $ds as $field => $value )
			{
				$fields .= $i == 1 ? $field : ", ".$field;
				$values .= $i == 1 ? "'".$value."'" : ", '".$value."'";
				$i++;
			}
			$sc = dbQuery("INSERT INTO tbl_transfer_temp (".$fields.") VALUES (".$values.")");
		}

		return $sc;
  }





  //--- เพิ่มยอดสินค้าใน temp
  public function updateTransferTemp($id, $qty)
  {
    return dbQuery("UPDATE tbl_transfer_temp SET qty = qty + ".$qty." WHERE id = '".$id."'");
  }



  //--- รายการที่อยู่ใน temp 1 แถว
  public function getTempDetail($id)
  {
    return dbQuery("SELECT * FROM tbl_transfer_temp WHERE id_transfer_detail = ".$id);
  }


  //--- รายการที่อยู่ใน temp ทั้งเอกสาร
  public function getTempDetails($id_transfer)
  {
    return dbQuery("SELECT * FROM tbl_transfer_temp WHERE id_transfer = '".$id_transfer."'");
  }



  //--- ลบรายการใน temp ออก หลังจากเพิ่มยอดเข้าโซนต้นทางแล้ว
  public function removeTempDetail($id_transfer_detail)
  {
    return dbQuery("DELETE FROM tbl_transfer_temp WHERE id_transfer_detail = '".$id_transfer_detail."'");
  }




  //-----------------  New Reference --------------//
	public function getNewReference($date = '')
	{
		$date     = $date == '' ? date('Y-m-d') : $date;
		$Y		    = date('y', strtotime($date));
		$M		    = date('m', strtotime($date));
		$runDigit = getConfig('RUN_DIGIT');
		$prefix   = getConfig('PREFIX_TRANSFER');
		$preRef   = $prefix . '-' . $Y . $M;

		$qs = dbQuery("SELECT MAX(reference) AS reference FROM tbl_transfer WHERE reference LIKE '".$preRef."%' ORDER BY reference DESC");

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



  //--- รายการโอนสินค้า
  public function getMoveList($id)
	{
		return dbQuery("SELECT * FROM tbl_transfer_detail WHERE id_transfer = ".$id);
	}



  //--- ตรวจสอบว่ามีรายการอยู่ในตารางแล้วหรือยัง (ยังไม่ย้ายเข้าปลายทาง)
  public function isExistsDetail($id_transfer, $id_product, $id_zone)
  {
    $sc  = FALSE;
    $qr  = "SELECT id FROM tbl_transfer_detail ";
    $qr .= "WHERE id_transfer = '".$id_transfer."' ";
    $qr .= "AND id_product = '".$id_product."' ";
    $qr .= "AND from_zone = '".$id_zone."' ";
    $qr .= "AND valid = 0";

    $qs = dbQuery($qr);

    if( dbNumRows($qs) == 1 )
    {
      list( $sc ) = dbFetchArray($qs);
    }

    return $sc;
  }



  //--- มีรายการนี้อยู่ใน temp แล้วหรือยัง
  public function isExistsTemp($id_transfer_detail)
  {
    $sc = FALSE;
    $qs = dbQuery("SELECT id FROM tbl_transfer_temp WHERE id_transfer_detail = '".$id_transfer_detail."'");
    if( dbNumRows($qs) == 1)
    {
      list( $sc ) = dbFetchArray($qs);
    }

    return $sc;
  }




} //--- end class
 ?>
