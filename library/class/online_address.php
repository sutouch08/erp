<?php
class online_address
{
	public function __construct($id="")
	{
		if( $id != "" )
		{
			$qs = dbQuery("SELECT * FROM tbl_address_online WHERE id = ".$id);
			if( dbNumRows($qs) == 1 )
			{
				$rs = dbFetchArray($qs);
				foreach( $rs as $key => $value )
				{
					$this->$key = $value;	
				}
			}
		}
	}
	
	
		
	public function add( array $ds = array() )
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
			$sc = dbQuery("INSERT INTO tbl_address_online (".$fields.") VALUES (".$values.")");
		}
		return $sc;
	}
	
	
	
	
	
	
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
			$sc = dbQuery("UPDATE tbl_address_online SET ". $set ." WHERE id = ".$id);
		}
		return $sc;
	}
	
	
	
	
	
	
	public function delete($id)
	{
		return dbQuery("DELETE FROM tbl_address_online WHERE id = ".$id);
	}
	
	
	
	
	
	public function getCode($id)
	{
		$sc = '';
		$qs = dbQuery("SELECT customer_code FROM tbl_address_online WHERE id = ".$id);	
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}
	
	
	
	
	public function setDefault($id)
	{
		$code = $this->getCode($id);
		$qs = dbQuery("UPDATE tbl_address_online SET is_default = 1 WHERE id = ".$id);
		$qr = dbQuery("UPDATE tbl_address_online SET is_default = 0 WHERE id != ".$id." AND customer_code = '".$code."'");
		return ( $qs === TRUE && $qr === TRUE ) ? TRUE : FALSE;
	}
	
	
	
	public function getAddress($id)
	{
		return dbQuery("SELECT * FROM tbl_address_online WHERE id = ".$id);	
	}
	
	
	
	
	public function getAddressByCode(	$online_code)
	{
		return dbQuery("SELECT * FROM tbl_address_online WHERE customer_code = '".$online_code."'");
	}
	
	
	
	
}

?>