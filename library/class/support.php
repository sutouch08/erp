<?php
class support
{

  public $id;
  public $name;
  public $id_employee;
  public $year;

  public function __construct($id = '')
  {
      if($id != '')
      {
        $this->getData($id);
      }
  }


  public function getData($id)
  {
    $qs = dbQuery("SELECT * FROM tbl_support WHERE id = ".$id);
    if( dbNumRows($qs) == 1 )
    {
      $rs = dbFetchArray($qs);
      foreach($rs as $key => $value)
      {
        $this->$key = $value;
      }
    }
  }


  public function getDataByEmployee($id_employee)
  {
    $qs = dbQuery("SELECT * FROM tbl_support WHERE id_employee = '".$id_employee."'");
    if( dbNumRows($qs) == 1 )
    {
      $rs = dbFetchArray($qs);
      foreach($rs as $key => $value)
      {
        $this->$key = $value;
      }
    }
  }



  public function add(array $ds )
	{
		$sc = FALSE;
		if( ! empty($ds) )
		{
			$fields	= "";
			$values	= "";
			$i			= 1;
			foreach( $ds as $field => $value )
			{
				$fields	.= $i == 1 ? $field : ", ".$field;
				$values	.= $i == 1 ? "'". $value ."'" : ", '". $value ."'";
				$i++;
			}
			$sc = dbQuery("INSERT INTO tbl_support (".$fields.") VALUES (".$values.")");
		}

		return $sc === TRUE ? dbInsertId() : FALSE;
	}



  public function update($id, array $ds)
	{
		$sc = FALSE;
		if( ! empty( $ds ) )
		{
			$set 	= "";
			$i		= 1;
			foreach( $ds as $field => $value )
			{
				$set .= $i == 1 ? $field . " = '" . $value . "'" : ", ".$field . " = '" . $value . "'";
				$i++;
			}
			$sc = dbQuery("UPDATE tbl_support SET " . $set . " WHERE id = '".$id."'");
		}

		return $sc;
	}





  public function delete($id){
    return dbQuery("DELETE FROM tbl_support WHERE id = ".$id);
  }

  


  public function isExistsEmployee($id_employee)
  {
    $sc = FALSE;
    $qs = dbQuery("SELECT id FROM tbl_support WHERE id_employee = '".$id_employee."'");
    if( dbNumRows($qs) > 0)
    {
      $sc = TRUE;
    }

    return $sc;
  }





  public function getId($id_employee)
  {
    $sc = 0;
    $qs = dbQuery("SELECT id FROM tbl_support WHERE id_employee = '".$id_employee."'");
    if( dbNumRows($qs) == 1 )
    {
      list( $sc ) = dbFetchArray($qs);
    }

    return $sc;
  }





  public function getSupportAndBudgetBalance($txt, $date)
  {
    $qr = "SELECT s.id_employee, s.name, s.id_budget, b.balance FROM tbl_support AS s ";
    $qr .= "LEFT JOIN tbl_support_budget AS b ON s.id_budget = b.id ";
    $qr .= "WHERE b.active = 1 AND b.is_deleted = 0 AND s.name LIKE '%".$txt."%' ";
    $qr .= "AND b.start <= '".fromDate($date)."' AND b.end >= '".toDate($date)."'";

    return dbQuery($qr);
  }


  public function getBudgetBalanceByEmployee($id_employee)
  {
    $sc = 0;
    $qr  = "SELECT balance FROM tbl_support AS s ";
    $qr .= "JOIN tbl_support_budget AS b ON s.id_budget = b.id ";
    $qr .= "WHERE s.id_employee = '".$id_employee."'";

    $qs = dbQuery($qr);
    if( dbNumRows($qs) == 1)
    {
      list( $sc ) = dbFetchArray($qs);
    }

    return $sc;
  }


} //--- End class
 ?>
