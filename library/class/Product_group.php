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
			$sc = dbQuery("INSERT INTO tbl_product_group (".$fields.") VALUES (".$values.")");
		}
		
		return $sc;			
	}
	
	
	public function update( $id, array $ds)
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
			$sc = dbQuery("UPDATE tbl_product_group SET ". $set ." WHERE id = '".$id."'");
		}
		return $sc;
	}
	
	
	public function isExists($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT code FROM tbl_product_group WHERE id = '".$id."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;	
		}
		
		return $sc;
	}
	

	

	
}


?>