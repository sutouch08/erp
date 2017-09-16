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
	public $year;
	public $cost;
	public $price;
	public $id_unit;
	public $weight;
	public $width;
	public $length;
	public $height;
	public $count_stock;
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
				$this->year						= $rs->year;
				$this->cost						= $rs->cost;
				$this->price						= $rs->price;
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
	
	
	
	public function isDisactiveAll($id_style)
	{
		$qs = dbQuery("SELECT id FROM tbl_product WHERE id_style = '".$id_style."' AND active = 1");
		return dbNumRows($qs) > 0 ? FALSE : TRUE;	
	}
	
	
	
	
	public function getProductsByStyle($id_style)
	{
		return dbQuery("SELECT * FROM tbl_product WHERE id_style = '".$id_style."'");	
	}
	
	
	
	public function hasImage($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id_image FROM tbl_product_image WHERE id_product = '".$id."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;	
		}
		return $sc;
	}
	
	
	
	
	public function addImage($id, $id_image)
	{
		return dbQuery("INSERT INTO tbl_product_image (id_product, id_image) VALUES ('".$id."', '".$id_image."')");	
	}
	
	
	
	
	public function updateImage($id, $id_image)
	{
		return dbQuery("UPDATE tbl_product_image SET id_image = '".$id_image."' WHERE id_product = '".$id."'");	
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
	
	
	
	
	///------- get images list of this style
	public function getProductImages($id_style)
	{
		return dbQuery("SELECT * FROM tbl_image WHERE id_style = '".$id_style."'");
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
	
	
	
	public function getStyleId($id)
	{
		$sc = 0;
		$qs = dbQuery("SELECT id_style FROM tbl_product WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray( $qs);
		}
		return $sc;
	}
	
	
	public function getAllColors($id_style)
	{
		$sc = array();
		$qs = dbQuery("SELECT p.id_color, c.code, c.name FROM tbl_product AS p JOIN tbl_color AS c ON p.id_color = c.id WHERE p.id_style = '".$id_style."' GROUP BY p.id_color ORDER BY c.code ASC");
		if( dbNumRows($qs) > 0 )
		{
			while( $rs = dbFetchObject($qs) )
			{
				$sc[$rs->id_color]		= array("code" => $rs->code, "name" => $rs->name);	
			}
		}
		return $sc;
	}
	
	
	
	public function getAllSizes($id_style)
	{
		$sc = array();
		$qs = dbQuery("SELECT p.id_size, s.code, s.name FROM tbl_product AS p JOIN tbl_size AS s ON p.id_size = s.id WHERE p.id_style = '".$id_style."' GROUP BY p.id_size ORDER BY s.position ASC");
		if( dbNumRows($qs) > 0 )
		{
			while( $rs = dbFetchObject($qs) )
			{
				$sc[$rs->id_size]		= array("code" => $rs->code, "name" => $rs->name);	
			}
		}
		return $sc;	
	}
	
	public function countAttribute($id_style)
	{
		$color = dbNumRows(dbQuery("SELECT id FROM tbl_product WHERE id_style = '".$id_style."' AND id_color != '0' AND id_color != '' GROUP BY id_style"));
		$size = dbNumRows(dbQuery("SELECT id FROM tbl_product WHERE id_style = '".$id_style."' AND id_size != '0' AND id_size != '' GROUP BY id_style"));
		return $color + $size;
	}
	
	
	
	public function getNameByCode($code)
	{
		$sc = "";
		$qs = dbQuery("SELECT name FROM tbl_product WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}
	
	
	public function getId($code)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_product WHERE code = '".$code."'");
		if( dbNumRows($qs ) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}
	
	public function getName($id)
	{
		$sc = "";
		$qs = dbQuery("SELECT name FROM tbl_product WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}
	
	
	public function getCode($id)
	{
		$sc = "";
		$qs = dbQuery("SELECT code FROM tbl_product WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;	
	}
	
	
	public function getUnitCode($id)
	{
		$sc = "";
		$qs = dbQuery("SELECT u.code FROM tbl_product AS p JOIN tbl_unit AS u ON p.id_unit = u.id WHERE p.id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);	
		}
		return $sc;
	}
	
	public function getStylePrice($id_style)
	{
		$sc = 0;
		$qs = dbQuery("SELECT MAX( price ) AS price FROM tbl_product WHERE id_style = '".$id_style."'");
		list( $price ) = dbFetchArray($qs);
		if( ! is_null( $price ) )
		{
			$sc = $price;
		}
		return $sc;
	}
	
	public function getPrice($id)
	{
		$sc = 0;
		$qs = dbQuery("SELECT price FROM tbl_product WHERE id_product = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}
	
	public function isCountStock($id_style)
	{
		$sc = TRUE;
		$qs = dbQuery("SELECT id FROM tbl_product WHERE id_style = '".$id_style."' AND count_stock = 0");
		if( dbNumRows($qs) > 0 )
		{
			$sc = FALSE;
		}
		return $sc;
	}
	
	
	//--- ยอดรวมทุกคลังทุกโซนทั้งขายได้และไม่ได้
	public function getStock($id)
	{
		$stock = new stock();
		return $stock->getStock($id);	
	}
	
	//---- ยอดรวมสินค้าที่สั่งได้
	public function getSellStock($id)
	{
		$order = new order();
		$stock = new stock();
		$cancle = new cancle_zone();
		$sellStock = $stock->getSellStock($id);
		$reservStock = $order->getReservQty($id);
		$cancleQty = $cancle->getCancleQty($id);
		$availableStock = $sellStock - $reservStock + $cancleQty;
		return $availableStock < 0 ? 0 : $availableStock;
	}
	
	
	
	//---- ยอดรวมของรุ่นสินค้าที่สั่งได้
	public function getStyleSellStock($id_style)
	{
		$order = new order();
		$stock = new stock();
		$cancle = new cancle_zone();
		$sellStock = $stock->getStyleSellStock($id_style);
		$reservStock = $order->getStyleReservQty($id_style);
		$cancleQty = $cancle->getStyleCancleQty($id_style);
		
		$availableStock = $sellStock - $reservStock + $cancleQty;
		
		return $availableStock < 0 ? 0 : $availableStock;
		
	}
	
	
		
}//จบ class
?>
