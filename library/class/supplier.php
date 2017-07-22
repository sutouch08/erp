<?php
class supplier 
{
	public $id;
	public $code;
	public $name;
	public $group_code;
	public $credit_amount;
	public $credit_term;
	public $active;
	
	public function __construct($id = "")
	{
		if( $id != "" )
		{
			$qs = dbQuery("SELECT * FROM tbl_supplier WHERE id = '".$id."'");
			if( dbNumRows($qs) == 1 )
			{
				$rs = dbFetchObject($qs);
				$this->id			= $rs->id;
				$this->code	 	= $rs->code;
				$this->name		= $rs->name;
				$this->group_code	= $rs->group_code;
				$this->credit_amount	= $rs->credit_amount;
				$this->credit_term		= $rs->credit_term;	
				$this->active	= $rs->active;
			}
		}
	}
	
	public function add(array $ds)
	{
		$sc = FALSE;
		if( count( $ds ) > 0 )
		{
			$fields	= "";
			$values	= "";
			$i			= 1;
			foreach( $ds as $field => $value )
			{
				$fields	.= $i == 1 ? $field : ", ".$field;
				$values	.= $i == 1 ? "'".$value."'" : ", '".$value."'";
				$i++;	
			}
			$sc = dbQuery("INSERT INTO tbl_supplier (".$fields.") VALUES (".$values.")");
		}
		
		return $sc;
	}
	
	
	
	public function update($id, array $ds)
	{
		$sc = FALSE;
		if( count( $ds ) > 0 )
		{
			$set 	= "";
			$i 		= 1;
			foreach( $ds as $field => $value )
			{
				$set	.= $i == 1 ? $field." = '".$value."'" : ", ".$field." = '".$value."'";
				$i++;	
			}	
			$sc = dbQuery("UPDATE tbl_supplier SET ".$set." WHERE id = '".$id."'");
		}
		return $sc;
	}
	
	
	public function delete($id, $emp)
	{
		if( $this->hasTransection($id) === FALSE )
		{
			return dbQuery("DELETE FROM tbl_supplier WHERE id = '".$id."'");	
		}
		else
		{
			return dbQuery("UPDATE tbl_supplier SET is_deleted = 1, emp_delete = ".$emp." WHERE id = '".$id."'");	
		}
	}
	
	
	public function unDelete($id)
	{
		return dbQuery("UPDATE tbl_supplier SET is_deleted = 0, emp_delete = ".$emp." WHERE id = '".$id."'");	
	}
	
	
	public function hasTransection($id)
	{
		$sc = FALSE;
		$code = $this->getCode($id);
		if( $code !== FALSE )
		{
			$qs = dbQuery("SELECT id FROM tbl_po WHERE supplier_code = '".$code."'");
			if( dbNumRows($qs) > 0 )
			{
				$sc = TRUE;
			}
		}
		return $sc;
	}
	
	public function isExists($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT code FROM tbl_supplier WHERE id = '".$id."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}
		return $sc;
	}
	
	public function getCode($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT code FROM tbl_supplier WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);	
		}
		return $sc;
	}
		
	
}//----end class
?>