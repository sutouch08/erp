<?php
class payment
{
	public function __construct($id="")
	{
		if( $id != "" )
		{
			$qs = dbQuery("SELECT * FROM tbl_payment WHERE id_order = ".$id);	
			if( dbNumRows( $qs ) == 1 )
			{
				$rs = dbFetchArray($qs);
				foreach( $rs as $key => $val )
				{
					$this->$key = $val;
				}
			}
		}
	}
	

	
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
			$sc = dbQuery("INSERT INTO tbl_payment (".$fields.") VALUES (".$values.")");
		}
		return $sc;
	}
	
	
	
	//----- Update order
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
			$sc = dbQuery("UPDATE tbl_payment SET ". $set ." WHERE id_order = ".$id);
		}
		return $sc;
	}
	
	
	
	public function isExists($id_order)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_payment WHERE id_order = ".$id_order);
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;	
		}
		return $sc;
	}
	
	
}
	
?>