<?php 
class customer_credit
{
	public function __construct()
	{
		
	}
	
	public function add(array $ds = array() )
	{
		$sc = FALSE;
		if( ! empty($ds) )
		{
			$fields = "";
			$values = "";
			$i = 1;
			foreach( $ds as $field => $value )
			{
				$fields .=	 $i == 1 ? $field : ", ".$field;
				$values .= $i == 1 ? "'". $value."'" : ", '".$value."'";
				$i++;
			}
			$sc = dbQuery("INSERT INTO tbl_customer_credit (".$fields.") VALUES (".$values.")");
		}
		return $sc;
	}
	
	public function update($id, array $ds = array() )
	{
		$sc = FALSE;
		if( ! empty($ds) )
		{
			$set  = "";
			$i = 1;
			foreach( $ds as $field => $value )
			{
				$set .= $i == 1 ? $field ." = '".$value."'" : ", ".$field." = '".$value."'";
			}
			$sc = dbQuery("UPDATE tbl_customer_credit SET ".$set." WHERE id_customer = '".$id."'");
		}
		return $sc;
	}
	
	
	
	public function isExists($code)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id_customer FROM tbl_customer_credit WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			$sc = TRUE;
		}
		return $sc;
	}
	
	public function getId($code)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id_customer FROM tbl_customer_credit WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}
	
}

?>