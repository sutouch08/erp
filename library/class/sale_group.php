<?php
class sale_group
{

	public function __construct(){}
	
	public function add(array $ds)
	{
		return dbQuery("INSERT INTO tbl_sale_group (id, code, name) VALUES ('".$ds['id']."', '".$ds['code']."' , '".$ds['name']."')");
	}
	
	public function update( $id, array $ds)
	{
		return dbQuery("UPDATE tbl_sale_group SET code = '".$ds['code']."' , name = '".$ds['name']."' WHERE id = ".$id);
	}
	
	public function isExists($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT * FROM tbl_sale_group WHERE id = ".$id);
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}
		return $sc;
	}
	
	public function countMember($code)
	{
		$sc = 0;
		$qs = dbQuery("SELECT COUNT(*) FROM tbl_sale WHERE group_code = '".$code."'");
		list( $sc ) = dbFetchArray( $qs );
		return $sc;
	}
	
	
	public function getSaleGroupName($code)
	{
		$sc = "";
		$qs = dbQuery("SELECT name FROM tbl_sale_group WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);	
		}
		return $sc;
	}
	
}


?>