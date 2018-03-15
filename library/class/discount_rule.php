<?php
class discount_rule
{
  public $id = 0;
  public $code;
  public $name;
  public $id_discount_policy = 0;
  public $error;


  public function __construct($id = '')
  {
    if(is_numeric($id))
    {
      $this->getData($id);
    }
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




  public function add(array $ds = array())
  {
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

      $qs = dbQuery("INSERT INTO tbl_discount_rule (".$fields.") VALUES (".$values.")");
      if($qs === TRUE)
      {
        return dbInsertId();
      }

      $this->error = dbError();
    }

    return FALSE;
  }



  public function update($id, array $ds = array())
  {
    if(!empty($ds))
    {
      $set = "";
      $i = 1;
      foreach($ds as $field => $value)
      {
        $set .= $i == 1 ? $field." = '".$value."'" : ", ".$field." = '".$value."'";
        $i++;
      }

      $qs = dbQuery("UPDATE tbl_discount_rule SET ".$set." WHERE id = ".$id);
      if($qs !== TRUE)
      {
        $this->error = dbError();
      }

      return $qs;
    }

    return FALSE;
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
    $qs = dbQuery("SELECT code FROM tbl_discount_rule WHERE id = '".$id."'");
    if(dbNumRows($qs) == 1)
    {
      list($sc) = dbFetchArray($qs);
    }

    return $sc;
  }






  public function getId($code)
  {
    $sc = '';
    $qs = dbQuery("SELECT id FROM tbl_discount_rule WHERE code = '".$code."'");
    if(dbNumRows($qs) == 1)
    {
      list($sc) = dbFetchArray($qs);
    }

    return $sc;
  }






  public function getName($id)
  {
    $sc = '';
    $qs = dbQuery("SELECT name FROM tbl_discount_rule WHERE id = '".$id."'");
    if(dbNumRows($qs) == 1)
    {
      list($sc) = dbFetchArray($qs);
    }

    return $sc;
  }





  //-----------------  New Reference --------------//
	public function getNewReference($date = '')
	{
		$date = $date == '' ? date('Y-m-d') : $date;
		$Y		= date('y', strtotime($date));
		$M		= date('m', strtotime($date));
		$prefix = getConfig('PREFIX_RULE');
		$runDigit = getConfig('RUN_DIGIT'); //--- รันเลขที่เอกสารกี่หลัก
		$preRef = $prefix . '-' . $Y . $M;
		$qs = dbQuery("SELECT MAX(code) AS code FROM tbl_discount_rule WHERE code LIKE '".$preRef."%' ORDER BY code DESC");
		list( $ref ) = dbFetchArray($qs);
		if( ! is_null( $ref ) )
		{
			$runNo = mb_substr($ref, ($runDigit*-1), NULL, 'UTF-8') + 1;
			$reference = $prefix . '-' . $Y . $M . sprintf('%0'.$runDigit.'d', $runNo);
		}
		else
		{
			$reference = $prefix . '-' . $Y . $M . sprintf('%0'.$runDigit.'d', '001');
		}
		return $reference;
	}




  public function getRuleList($id_policy)
  {
    return dbQuery("SELECT * FROM tbl_discount_rule WHERE id_discount_policy = '".$id_policy."'");
  }

}

 ?>
