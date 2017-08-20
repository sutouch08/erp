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
	public $can_sell;
	public $active;
	public $is_deleted;
	public $emp;
	public $date_upd;
	
	public function __construct($id = '' )
	{
		if( $id != '' )
		{
			$qs = dbQuery("SELECT * FROM tbl_product WHERE id = '".$id."'");
			if( dbNumRows($qs) == 1 )
			{
				$rs = dbFetchObject($qs);
				$this->id							= $rs->id;
				$this->code						= $rs->code;
				$this->name						= $rs->name;
				$this->id_style					= $rs->id_style;
				$this->id_color					= $rs->id_color;
				$this->id_size					= $rs->id_size;
				$this->id_kind					= $rs->id_kind;
				$this->id_type					= $rs->id_type;
				$this->id_group				= $rs->id_group;
				$this->id_category			= $rs->id_category;
				$this->id_brand				= $rs->id_brand;
				$this->cost						= $rs->cost;
				$this->price						= $rs->price;
				$this->discount_amount		= $rs->discount_amount;
				$this->discount_percent		= $rs->discount_percent;
				$this->id_unit					= $rs->id_unit;
				$this->weight					= $rs->weight;
				$this->width						= $rs->width;
				$this->length					= $rs->length;
				$this->height					= $rs->height;
				$this->count_stock			= $rs->count_stock;
				$this->show_in_sale			= $rs->show_in_sale;
				$this->show_in_customer	= $rs->show_in_customer;
				$this->show_in_online			= $rs->show_in_online;
				$this->can_sell					= $rs->can_sell;
				$this->active					= $rs->active;
				$this->is_deleted				= $rs->is_deleted;
				$this->emp						= $rs->emp;
				$this->date_upd				= $rs->date_upd;
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
	
	
	
	public function updateProducts($id_style, array $ds)
	{
		$sc = TRUE;
		if( count( $ds ) > 0 )
		{
			$qs = $this->getProductsByStyle($id_style); ///-- get all products in this style
			if( dbNumRows($qs) > 0 )
			{
				startTransection();
				while( $rs = dbFetchObject($qs) )
				{
					if( ! $this->update($rs->id, $ds) )
					{
						$sc = FALSE;
					}
				}
				if( $sc === TRUE )
				{
					commitTransection();
				}
				else
				{
					dbRollback();
				}
				endTransection();
			}
		}
		
		return $sc;
	}
	
	
	
	
	public function delete($id)
	{
		return dbQuery("DELETE FROM tbl_product WHERE id = '".$id."'");
	}
	
	
	
	public function updateDescription($id_style, $desc)
	{
		if( $this->hasDescription($id_style) === TRUE )
		{
			return dbQuery("UPDATE tbl_product_detail SET description = '".$desc."' WHERE id_style = '".$id_style."'");
		}
		else
		{
			return dbQuery("INSERT INTO tbl_product_detail ( id_style, description ) VALUES ('".$id_style."', '".$desc."')");
		}
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
	
	
	
	
	
	
	public function getDescription($id_style)
	{
		$sc = '';
		$qs = dbQuery("SELECT description FROM tbl_product_detail WHERE id_style = '".$id_style."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}
	
	
	
	
	public function hasDescription($id_style)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_product_detail WHERE id_style = '".$id_style."'");
		if( dbNumRows($qs) == 1 )
		{
			$sc = TRUE;	
		}
		return $sc;
	}
	
	
	
	
	
	public function getProductsByStyle($id_style)
	{
		return dbQuery("SELECT * FROM tbl_product WHERE id_style = '".$id_style."'");	
	}
	
	
	
	public function getImageId($id)
	{
		$sc = '';
		$qs = dbQuery("SELECT id_image FROM tbl_product_image WHERE id_product = '".$id."' LIMIT 1");
		if( dbNumRows($qs) > 0 )
		{
			list( $sc ) = dbFetchArray($qs);	
		}
		return $sc;
	}
	
	
	//---- get Status of specific field
	public function getStatus($id, $field)
	{
		$sc = 0;
		$qs = dbQuery("SELECT ".$field." FROM tbl_product WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}
	
	
	
	//----- set status of specific field
	public function setStatus($id, $field, $val)
	{
		return dbQuery("UPDATE tbl_product SET ".$field." = '".$val."' WHERE id = '".$id."'");	
	}
	
	

	

	
}//จบ class
?>
