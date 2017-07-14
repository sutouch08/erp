<?php
class products {
	
	public function __instruct(){}
	
	public function add(array $ds)
	{
		$sc = FALSE;
		if( count($ds) > 0 )
		{
			$fields	= "";
			$values	= "";
			$i		= 1;
			foreach( $ds as $field => $value )
			{
				$fields .= $i == 1 ? $field : ", ". $field;
				$values	.= $i == 1 ? "'".$value."'" : ", '". $value . "'";
				$i++;	
			}
			$sc = dbQuery("INSERT INTO tbl_product(".$fields.") VALUES (".$values.")");
				
		}
		return $sc;			
	}
	
	
	public function isExists($code)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT * FROM tbl_product WHERE product_code = '" .$code. "'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;	
		}
		
		return $sc;
	}
	
	public function getProductId($code)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id_product FROM tbl_product WHERE product_code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);	
		}
		
		return $sc;			
	}
	
}//--end class

?>