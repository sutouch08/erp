<?php
class barcode {

public function __construct(){}

public function add(array $ds)
{
	$sc = FALSE;
	if( count($ds) > 0 )
	{
		if( $this->isExists( $ds['id'] ) === FALSE )
		{
			$qs = "INSERT INTO tbl_barcode (id, barcode, reference, unit_code, unit_qty) VALUES ";
			$qs .= "('".$ds['id']."', '".$ds['barcode']."', '".$ds['reference']."', '".$ds['unit_code']."', ".$ds['unit_qty'].")";
			$sc = dbQuery($qs);	
		}
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
			$set .= $i== 1 ? $field ." = '".$value."'" : ", ".$field . " = '".$value."'";
			$i++;	
		}
		$sc = dbQuery("UPDATE tbl_barcode SET ".$set." WHERE id = '".$id."'");		
	}
	
	return $sc;
}
	
	
public function delete($id)
{
	return dbQuery("DELETE FROM tbl_barcode WHERE id = '" .$id."'");	
}


public function isExists($id)
{
	$sc = FALSE;
	$qs = dbQuery("SELECT barcode FROM tbl_barcode WHERE id = '".$id."'");
	if( dbNumRows($qs) > 0 )
	{
		$sc = TRUE;
	}
	return $sc;		
}


}//// end class



?>