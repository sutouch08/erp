<?php
class barcode {

public function __construct(){}

public function add(array $ds)
{
	$sc = FALSE;
	if( count($ds) > 0 )
	{
		if( $this->isExists( $ds['barcode'] ) === FALSE )
		{
			$qs = "INSERT INTO tbl_barcode (barcode, reference, unit_code, unit_qty) VALUES ";
			$qs .= "('".$ds['barcode']."', '".$ds['reference']."', '".$ds['unit_code']."', ".$ds['unit_qty'].")";
			$sc = dbQuery($qs);	
		}
	}
	
	return $sc;
}

public function update($code, array $ds)
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
		$sc = dbQuery("UPDATE tbl_barcode SET ".$set." WHERE barcode = '".$code."'");		
	}
	
	return $sc;
}
	
	
public function delete($code)
{
	return dbQuery("DELETE FROM tbl_barcode WHERE barcode = '" .$code."'");	
}


public function isExists($code)
{
	$sc = FALSE;
	$qs = dbQuery("SELECT barcode FROM tbl_barcode WHERE barcode = '".$code."'");
	if( dbNumRows($qs) > 0 )
	{
		$sc = TRUE;
	}
	return $sc;		
}


}//// end class



?>