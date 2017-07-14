<?php
class size {
	
	public function __construct(){}
	
	public function add($code)
	{
		$sc = FALSE;
		if( $this->isExists($code) === FALSE )
		{
			$qs = "INSERT INTO tbl_size (size_name, position) VALUES ";
			$qs .= "('".$code."', ".$this->getTopPosition().")";
			$sc = dbQuery($qs);
		}
		
		return $sc;
	}
	
	public function isExists($code)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT * FROM tbl_size WHERE size_name = '".$code."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;	
		}
		
		return $sc;
	}
	
	
	public function getTopPosition()
	{
		$sc = 1;
		$qs = dbQuery("SELECT MAX(position) FROM tbl_size");
		list( $max ) = dbFetchArray($qs);
		if( ! is_null($max) )
		{
			$sc = $max;
		}
		return $sc;		
	}
	
}///


?>