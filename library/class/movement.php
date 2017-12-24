<?php
class movement
{
	public $error;

	public function __construct(){}



	public function move_in($reference, $id_warehouse, $id_zone, $id_pd, $qty, $date_upd)
	{
		if( $this->isMoveInExists($reference, $id_warehouse, $id_zone, $id_pd) )
		{
			$qr = "UPDATE tbl_stock_movement SET move_in = move_in + ".$qty." ";
			$qr .= "WHERE reference = '".$reference."' ";
			$qr .= "AND id_warehouse = '".$id_warehouse."' ";
			$qr .= "AND id_zone = '".$id_zone."' ";
			$qr .= "AND id_product = '".$id_pd."' ";

		}
		else
		{
			$qr = "INSERT INTO tbl_stock_movement ";
			$qr .= "(reference, id_warehouse, id_zone, id_product, move_in, date_upd) ";
			$qr .= "VALUES ";
			$qr .= "('".$reference."', '".$id_warehouse."', '".$id_zone."', '".$id_pd."', '".$qty."', '".$date_upd."')";

		}

		$sc = dbQuery($qr);
		if($sc === FALSE )
		{
			$this->error = dbError();
		}

		return $sc;
	}




	public function move_out($reference, $id_warehouse, $id_zone, $id_pd, $qty, $date_upd)
	{
		if( $this->isMoveOutExists($reference, $id_warehouse, $id_zone, $id_pd) )
		{
			$qr = "UPDATE tbl_stock_movement SET move_out = move_out + ".$qty." ";
			$qr .= "WHERE reference = '".$reference."' ";
			$qr .= "AND id_warehouse = '".$id_warehouse."' ";
			$qr .= "AND id_zone = '".$id_zone."' ";
			$qr .= "AND id_product = '".$id_pd."' ";

		}
		else
		{
			$qr = "INSERT INTO tbl_stock_movement ";
			$qr .= "(reference, id_warehouse, id_zone, id_product, move_out, date_upd) ";
			$qr .= "VALUES ";
			$qr .= "('".$reference."', '".$id_warehouse."', '".$id_zone."', '".$id_pd."', '".$qty."', '".$date_upd."')";

		}

		$sc = dbQuery($qr);

		if($sc === FALSE )
		{
			$this->error = dbError();
		}

		return $sc;
	}





	private function isMoveInExists($reference, $id_warehouse, $id_zone, $id_pd)
	{
		$sc = FALSE;
		$qr = "SELECT id FROM tbl_stock_movement ";
		$qr .= "WHERE reference = '".$reference."' ";
		$qr .= "AND id_warehouse = '".$id_warehouse."' ";
		$qr .= "AND id_zone = '".$id_zone."' ";
		$qr .= "AND id_product = '".$id_pd."' ";
		$qr .= "AND move_in	 > 0 AND move_out = 0";
		$qs = dbQuery($qr);
		if( dbNumRows($qs) == 1 )
		{
			$sc = TRUE;
		}
		return $sc;
	}





	private function isMoveOutExists($reference, $id_warehouse, $id_zone, $id_pd)
	{
		$sc = FALSE;
		$qr = "SELECT id FROM tbl_stock_movement ";
		$qr .= "WHERE reference = '".$reference."' ";
		$qr .= "AND id_warehouse = '".$id_warehouse."' ";
		$qr .= "AND id_zone = '".$id_zone."' ";
		$qr .= "AND id_product = '".$id_pd."' ";
		$qr .= "AND move_out	 > 0 AND move_in = 0";
		$qs = dbQuery($qr);
		if( dbNumRows($qs) == 1 )
		{
			$sc = TRUE;
		}
		return $sc;
	}





	public function dropMovement($reference)
	{
		return dbQuery("DELETE FROM tbl_stock_movement WHERE reference = '".$reference."'");
	}




	public function removeMovement($reference, $id_pd)
	{
		return dbQuery("DELETE FROM tbl_stock_movement WHERE reference = '".$reference."' AND id_product = '".$id_pd."'");
	}



	public function dropMoveIn($reference, $id_zone, $id_pd)
	{
		return dbQuery("DELETE FROM tbl_stock_movement WHERE reference = '".$reference."' AND id_product = '".$id_pd."' AND move_in > 0 AND id_zone = '".$id_zone."'");
	}



	public function dropMoveOut($reference, $id_zone, $id_pd)
	{
		return dbQuery("DELETE FROM tbl_stock_movement WHERE reference = '".$reference."' AND id_product = '".$id_pd."' AND move_out > 0 AND id_zone = '".$id_zone."'");
	}



}//--- end class

?>
