<?php

class image
{
	public $id;
	public $id_style;
	public $position;
	public $cover;
	
	public function __construct($id = "")
	{
		if( $id != "" )
		{
			$qs = dbQuery("SELECT * FROM tbl_image WHERE id = ".$id);
			if( dbNumRows($qs) == 1 )
			{
				$rs = dbFetchObject($qs);
				$this->id		= $rs->id;
				$this->id_style	= $rs->id_style;
				$this->position	= $rs->position;
				$this->cover	= $rs->cover;
			}
		}
	}
	
	
	public function add(array $ds)
	{
		$sc = FALSE;
		if( count($ds) > 0 )
		{
			$fields	= "";
			$values	= "";
			$i			= 1;
			foreach( $ds as $field => $value )
			{
				$fields	.= $i == 1 ? $field : ", ".$field;
				$values	.= $i == 1 ? "'". $value ."'" : ", '". $value ."'";
				$i++;	
			}
			$sc = dbQuery("INSERT INTO tbl_image (".$fields.") VALUES (".$values.")");
		}
		return $sc;			
	}
	
	
	public function update($id, array $ds)
	{
		$sc = FALSE;
		if( count( $ds ) > 0 )
		{
			$set 	= "";
			$i		= 1;
			foreach( $ds as $field => $value )
			{
				$set .= $i == 1 ? $field . " = '" . $value . "'" : ", ".$field . " = '" . $value . "'";
				$i++;	
			}
			$sc = dbQuery("UPDATE tbl_image SET " . $set . " WHERE id = '".$id."'");
		}
		return $sc;
	}
	
	
	public function getImage($id)
	{
			
	}
	
	
	
	public function getImagePath($id, $useSize = 2)
	{
		$count	= strlen($id);
		$arr		= str_split($id);
		$path		= WEB_ROOT."img/product";
		$n			= 0;
		while( $n < $count )
		{
			$path .= '/' . $arr[$n];
			$n++;
		}
		$path	.= '/';
		$size		= '_default';
		switch( $useSize )
		{
			case 1 :
				$size = '_mini';
			break;
			case 2 :
				$size = '_default';
			break;
			case 3 :
				$size = '_medium';
			break;
			case 4 :
				$size = '_lage';
			break;
		}
		
		if( $count == 0 )
		{
			//----- If image no found
			$path .= 'no_image' . $size . '.jpg';
		}
		else
		{
			//---- if image found
			$path .= 'product' . $size . '_'. $id .'.jpg';
		}
		
		return $path;
	}
	
	
}//---- End class

?>