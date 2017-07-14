<?php

class product_group {
	public $code;
	public $name;
	public $date_upd;
	public function __construct(){}
	
	public function add(array $ds)
	{
		$sc 		= FALSE;
		$fields 	= '';
		$values	= '';
		$n			= count($ds);
		$i			= 1;
		if( $n > 0 )
		{
			foreach( $ds as $field => $value )
			{
				$fields 	.= $i == 1 ? $field : ', '.$field;
				$values  .= $i == 1 ? "'".$value."'" : ", '".$value."'";	
				$i++;
			}
			$qs = dbQuery("INSERT INTO tbl_product_group (".$fields.") VALUES (".$values.")");
			if( $qs )
			{
				$sc = dbInsertId();	
			}
		}
		
		return $sc;			
	}
	
	
	public function update( $code, array $ds)
	{
		$sc 	= FALSE;
		$set 	= '';
		$n		= count($ds);
		$i		= 1;
		if( $n > 0 )
		{
			foreach( $ds as $field => $value )
			{
				$set .= $i == 1 ? $field." = '".$value."'" : ", ".$field." = '".$value."'";
				$i++;	
			}
			$sc = dbQuery("UPDATE tbl_product_group SET ". $set ." WHERE code = '".$code."'");
		}
		
		return $sc;
	}
	
	
	public function isExists($code)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT code FROM tbl_product_group WHERE code = '".$code."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;	
		}
		
		return $sc;
	}
	

	

	
}


?>