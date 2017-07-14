<?php
class color{
	
public function __construct(){}


public function add($code, $name)
{
	$sc = FALSE;
	if( $this->isExists($code) === FALSE )
	{
		$qs = "INSERT INTO tbl_color (color_code, color_name, position) ";
		$qs .= "VALUES ";
		$qs .= "('".$code."', '".$name."', ". $this->getTopPosition().")";
		$sc = dbQuery($qs);
	}
	
	return $sc;
}


public function isExists($code)
{
	$sc = FALSE;
	$qs = dbQuery("SELECT * FROM tbl_color WHERE color_code = '".$code."'");
	if( dbNumRows($qs) > 0 )
	{
		$sc = TRUE;	
	}
	return $sc;
}

public function getTopPosition()
{
	$sc = 1;
	$qs = dbQuery("SELECT MAX(position) FROM tbl_color");
	list( $max ) = dbFetchArray($qs);
	if( ! is_null($max)	)
	{
		$sc = $max;	
	}
	
	return $sc;
}

public function getColorId($code)
{
	$sc = FALSE;
	$qs = dbQuery("SELECT id_color FROM tbl_color WHERE color_code = '".$code."'");
	if( dbNumRows($qs) == 1 )
	{
		list( $sc ) = dbFetchArray($qs);	
	}
	
	return $sc;
}



}////

?>