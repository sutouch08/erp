<?php
class unit {
	public $code 		= '';
	public $name		= '';
	public $date_upd	= '';
	
	public function __construct($code = "")
	{
		if( $code != "" )
		{
			$qs = dbQuery("SELECT * FROM tbl_unit WHERE code = '".$code."'");
			if( dbNumRows($qs) == 1 )
			{
				$rs = dbFetchObject($qs);
				$this->code = $rs->code;
				$this->name = $rs->name;
				$this->date_upd = $rs->date_upd;
			}
		}
	}
	
	public function add(array $ds)
	{
		$sc = FALSE;
		if( count($ds) > 0 )
		{
			$fields = '';
			$values = '';
			$i = 1;
			foreach( $ds as $field => $value )
			{
				$fields 	.= $i == 1 ? $field : ", ".$field;
				$values 	.= $i == 1 ? "'".$value."'" : ", '".$value."'";
				$i++;
			}
			$sc = dbQuery("INSERT INTO tbl_unit (".$fields.") VALUES (".$values.")");
		}
		return $sc;
	}
	
	public function update($code, array $ds)
	{
		$sc = FALSE;
		if( count($ds) > 0 )
		{
			$set = "";
			$i = 1;
			foreach( $ds as $field => $value )
			{
				$set .= $i == 1 ? $field . " = '".$value."'" : ", ".$field . " = '".$value."'";
				$i++;	
			}
			$sc = dbQuery("UPDATE tbl_unit SET ".$set." WHERE code = '".$code."'");
		}
		return $sc;	
	}
	
	public function isExists($code)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT code FROM tbl_unit WHERE code = '". $code. "'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}
		return $sc;
	}
	
}// end class

?>