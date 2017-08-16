<?php

class product
{
	public $id;
	public $code;
	public $name;
	public $id_style;
	public $id_color;
	public $id_size;
	public $id_kind;
	public $id_type;
	public $id_group;
	public $id_category;
	public $id_brand;
	public $cost;
	public $price;
	public $discount_amount;
	public $discount_percent;
	public $id_unit;
	public $weight;
	public $width;
	public $length;
	public $height;
	public $is_visual;
	public $show_in_sale;
	public $show_in_customer;
	public $show_in_online;
	public $active;
	public $is_deleted;
	public $emp_deleted;
	public $date_upd;
	
	public function __construct($id = '' )
	{
		if( $id != '' )
		{
			$qs = dbQuery("SELECT * FROM tbl_product WHERE id = '".$id."'");
			if( dbNumRows($qs) == 1 )
			{
				$rs = dbFetchObject($qs);
				$this->id			= $rs->id;
				$this->code		= $rs->code;
				$this->name		= $rs->name;
				$this->id_style	= $rs->id_style;
				$this->id_color	= $rs->id_color;
				$this->id_size	= $rs->id_size;
				$this->id_kind	= $rs->id_kind;
				$this->id_type	= $rs->id_type;
				$this->id_group	= $rs->id_group;
				$this->id_category	= $rs->id_category;
				$this->id_brand	= $rs->id_brand;
				$this->cost		= $rs->cost;
				$this->price		= $rs->price;
				$this->discount_amount	= $rs->discount_amount;
				$this->discount_percent	= $rs->discount_percent;
				$this->id_unit	= $rs->id_unit;
				$this->weight	= $rs->weight;
				$this->width		= $rs->width;
				$this->length	= $rs->length;
				$this->height	= $rs->hight;
				$this->is_visual		= $rs->is_visual;
				$this->show_in_sale	= $rs->show_in_sale;
				$this->show_in_customer	= $rs->show_in_customer;
				$this->show_in_online		= $rs->show_in_online;
				$this->active	= $rs->active;
				$this->is_deleted	= $rs->is_deleted;
				$this->emp_deleted	= $rs->emp_deleted;
				$this->date_upd	= $rs->date_upd;
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
			$sc = dbQuery("INSERT INTO tbl_product (".$fields.") VALUES (".$values.")");
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
			$sc = dbQuery("UPDATE tbl_product SET " . $set . " WHERE id = '".$id."'");
		}
		return $sc;
	}
	
	
	
	public function delete($id)
	{
		return dbQuery("DELETE FROM tbl_product WHERE id = '".$id."'");
	}
	
	
	public function isExists($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_product WHERE id = '".$id."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;	
		}
		return $sc;
	}
	

	

	
}//จบ class
?>
