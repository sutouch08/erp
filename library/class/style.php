<?php
class style
{
	public $id;
	public $code;
	public $name;
	public $active;
	public $show_in_shop;
	public $is_visual;
	public $error = '';
	
	public function __construct($id = "")
	{
		if( $id != "" )
		{
			$qs = dbQuery("SELECT * FROM tbl_style WHERE id = '".$id."'");
			if( dbNumRows($qs) == 1 )
			{
				$rs = dbFetchObject($qs);
				$this->id		= $rs->id;
				$this->code	= $rs->code;
				$this->name	= $rs->name;
				$this->active	= $rs->active;
				$this->show_in_shop	= $rs->show_in_shop;
				$this->is_visual		= $rs->is_visual;
			}
		}
	}
	
	
	public function add(array $ds )
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
			$sc = dbQuery("INSERT INTO tbl_style (".$fields.") VALUES (".$values.")");
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
			$sc = dbQuery("UPDATE tbl_style SET " . $set . " WHERE id = '".$id."'");
		}
		return $sc;
	}
	
	
	public function delete($id)
	{
		$sc = FALSE;
		if( $this->hasTransection($id) === FALSE )
		{
			$sc = dbQuery("DELETE FROM tbl_style WHERE id = '".$id."'");	
		}
		else
		{
			$this->error = "Transection Exists";
		}
		return $sc;
	}
	
	
	
	
	public function unDelete($id, $emp)
	{
		return dbQuery("UPDATE tbl_style  SET is_deleted = 0, emp = ".$emp." WHERE id = '".$id."'");	
	}
	
	
	
	
	public function isExists($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_style WHERE id = '".$id."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;	
		}
		return $sc;
	}
	
	
	
	public function getCode($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT code FROM tbl_style WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}
	
	
	
	public function getId($code)
	{
		$sc = 0;
		$qs = dbQuery("SELECT id FROM tbl_style WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);	
		}
		return $sc;
	}
	
	
	public function hasTransection($id)
	{
		$sc = FALSE;
		//--- Stock
		$stock	= dbNumRows( dbQuery("SELECT id_style FROM tbl_stock AS s JOIN tbl_product AS p ON s.id_product = p.id_product WHERE id_style = '".$id."'") );
		//--- Receive
		$receive	= dbNumRows( dbQuery("SELECT id_style FROM tbl_receive_detail AS r JOIN tbl_product AS p ON r.id_product = p.id_product WHERE id_style = '".$id."'") );
		//--- Order
		$order 	= dbNumRows( dbQuery("SELECT id_style FROM tbl_order_detail AS o JOIN tbl_product AS p ON o.id_product = p.id_product WHERE id_style = '".$id."'") );
		//--- PO
		$po		= dbNumRows( dbQuery("SELECT id_style FROM tbl_po AS po JOIN tbl_product AS pd ON po.id_product = pd.id_product WHERE id_style = '".$id."'") );
		
		$transection		= $stock + $receive + $order + $po;
		
		if( $transection > 0 )
		{
			$sc = TRUE;
		}
		return $sc;		
	}
	
	
	
	
	
}//--- End class

?>