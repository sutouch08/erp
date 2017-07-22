<?php
class customer_group 
{
	public $id;
	public $code;
	public $name;
	
	public function __construct($id = '')
	{
		if( $id != '' )
		{
			$qs = dbQuery("SELECT * FROM tbl_customer_group WHERE id = '".$id."'");
			if( dbNumRows($qs) == 1 )
			{
				$rs 				= dbFetchObject($qs);
				$this->id 		= $rs->id;
				$this->code 	= $rs->code;
				$this->name	 	= $rs->name;	
			}
		}
	}
	
	
	
	
	public function add(array $ds)
	{
		$sc = FALSE;
		if( count( $ds ) > 0 )
		{
			if( $this->isExists($ds['id']) === FALSE )
			{
				$sc = dbQuery("INSERT INTO tbl_customer_group (id, code, name) VALUES ('".$ds['id']."', '".$ds['code']."', '".$ds['name']."')");
			}
		}
		
		return $sc;
	}
	
	
	
	public function update($id, array $ds)
	{
		return dbQuery("UPDATE tbl_customer_group SET code = '".$ds['code']."', name = '".$ds['name']."' WHERE id = '".$id."'");	
	}
	
	
	public function isExists($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT * FROM tbl_customer_group WHERE id = '".$id."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}
		return $sc;
	}
	
	
	
	public function delete($id)
	{
		return dbQuery("DELETE FROM tbl_customer_group WHERE id = '".$id."'");
	}
	
	
	
	public function hasMember($code)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id_customer FROM tbl_customer WHERE group_code = '".$code."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}
		return $sc;
	}
	
	
	
	public function countMember($code)
	{
		$qs = dbQuery("SELECT COUNT(*) FROM tbl_customer WHERE group_code = '".$code."'");
		list( $sc ) = dbFetchArray($qs);
		return  $sc;
	}
	
	
	
	public function getGroupCode($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT code FROM tbl_customer_group WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);	
		}
		return $sc;
	}
	
	
	
	public function getGroupId($code)
	{
		$sc = 0;
		$qs = dbQuery("SELECT id FROM tbl_customer_group WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);	
		}
		return $sc;
	}
}

?>