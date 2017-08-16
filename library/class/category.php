<?php
class category 
{
	public $id;
	public $code;
	public $name;
	
	public function __construct($id = '')
	{
		if( $id != '' )
		{
			$qs = dbQuery("SELECT * FROM tbl_product_category WHERE id = '".$id."'");
			if( dbNumRows($qs) == 1 )
			{
				$rs = dbFetchObject($qs);
				$this->id 	= $rs->id;
				$this->code = $rs->code;
				$this->name = $rs->name;	
			}
		}
	}
	
	
	
	public function getCategoryName($id)
	{
		
	}
	
	public function getCategory()
	{
		return dbQuery("SELECT * FROM tbl_product_category");	
	}
		
}// --- จบ class	
?>							