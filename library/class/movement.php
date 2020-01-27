<?php
class movement
{
	public $error;

	public function __construct(){}



	public function move_in($reference, $id_warehouse, $id_zone, $id_pd, $qty, $date_upd)
	{
		$movement = $this->get_move_in($reference, $id_warehouse, $id_zone, $id_pd);
		if(!empty($movement))
		{
			$arr = array(
				'move_in' => $movement->move_in + $qty,
				'date_upd' => $date_upd
			);

			return $this->update($movement->id, $arr);
		}
		else
		{
			$arr = array(
				'reference' => $reference,
				'id_warehouse' => $id_warehouse,
				'id_zone' => $id_zone,
				'id_product' => $id_pd,
				'move_in' => $qty,
				'date_upd' => $date_upd
			);

			return $this->add($arr);
		}
	}



	public function move_out($reference, $id_warehouse, $id_zone, $id_pd, $qty, $date_upd)
	{
		$movement = $this->get_move_out($reference, $id_warehouse, $id_zone, $id_pd);
		if(!empty($movement))
		{
			$arr = array(
				'move_out' => $movement->move_out + $qty,
				'date_upd' => $date_upd
			);

			return $this->update($movement->id, $arr);
		}
		else
		{
			$arr = array(
				'reference' => $reference,
				'id_warehouse' => $id_warehouse,
				'id_zone' => $id_zone,
				'id_product' => $id_pd,
				'move_out' => $qty,
				'date_upd' => $date_upd
			);

			return $this->add($arr);
		}
	}


	private function get_move_in($reference, $id_warehouse, $id_zone, $id_pd)
	{
		$qr = "SELECT * FROM tbl_stock_movement ";
		$qr .= "WHERE reference = '{$reference}' ";
		$qr .= "AND id_warehouse = '{$id_warehouse}' ";
		$qr .= "AND id_zone = {$id_zone} ";
		$qr .= "AND id_product = '{$id_pd}' ";
		$qr .= "AND move_in > 0 AND move_out = 0";

		$qs = dbQuery($qr);
		if(dbNumRows($qs) === 1)
		{
			return dbFetchObject($qs);
		}

		return FALSE;
	}



	private function get_move_out($reference, $id_warehouse, $id_zone, $id_pd)
	{
		$qr = "SELECT * FROM tbl_stock_movement ";
		$qr .= "WHERE reference = '{$reference}' ";
		$qr .= "AND id_warehouse = '{$id_warehouse}' ";
		$qr .= "AND id_zone = {$id_zone} ";
		$qr .= "AND id_product = '{$id_pd}' ";
		$qr .= "AND move_in = 0 AND move_out > 0";

		$qs = dbQuery($qr);

		if(dbNumRows($qs) === 1)
		{
			return dbFetchObject($qs);
		}

		return FALSE;
	}



	private function add(array $ds = array())
	{
		if(!empty($ds))
		{
			$fields = "";
			$values = "";
			$i = 1;
			foreach($ds as $field => $value)
			{
				$fields .= $i === 1 ? "{$field}" : ", {$field}";
				$values .= $i === 1 ? "'{$value}'" : ", '{$value}'";
				$i++;
			}

			$qr = "INSERT INTO tbl_stock_movement ({$fields}) VALUES ({$values})";
			
			return dbQuery($qr);
		}

		return FALSE;
	}



	private function update($id, $arr)
	{
		if(!empty($arr))
		{
			$i = 1;
			$set = "";
			foreach($arr as $field => $val)
			{
				$set .= $i === 1 ? "{$field} = '{$val}'" : ", {$field} = '{$val}'";
				$i++;
			}

			$qr = "UPDATE tbl_stock_movement SET {$set} WHERE id = {$id}";

			return dbQuery($qr);
		}

		return FALSE;
	}



	private function isMoveInExists($reference, $id_warehouse, $id_zone, $id_pd)
	{
		$qr  = "SELECT id FROM tbl_stock_movement ";
		$qr .= "WHERE reference = '".$reference."' ";
		$qr .= "AND id_warehouse = '".$id_warehouse."' ";
		$qr .= "AND id_zone = '".$id_zone."' ";
		$qr .= "AND id_product = '".$id_pd."' ";
		$qr .= "AND move_in	!= 0 ";
		$qr .= "AND move_out = 0";

		$qs = dbQuery($qr);

		if( dbNumRows($qs) > 0 )
		{
			return TRUE;
		}

		return FALSE;
	}





	private function isMoveOutExists($reference, $id_warehouse, $id_zone, $id_pd)
	{
		$qr  = "SELECT id FROM tbl_stock_movement ";
		$qr .= "WHERE reference = '".$reference."' ";
		$qr .= "AND id_warehouse = '".$id_warehouse."' ";
		$qr .= "AND id_zone = '".$id_zone."' ";
		$qr .= "AND id_product = '".$id_pd."' ";
		$qr .= "AND move_out != 0 ";
		$qr .= "AND move_in = 0";

		$qs = dbQuery($qr);

		if( dbNumRows($qs) > 0 )
		{
			return TRUE;
		}

		return FALSE;
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


	public function updateMoveIn($reference, $id_zone, $id_pd, $qty)
	{
		$qr  = "UPDATE tbl_stock_movement SET move_in = move_in + ".$qty." ";
		$qr .= "WHERE reference = '".$reference."' ";
		$qr .= "AND id_zone = '".$id_zone."' ";
		$qr .= "AND id_product = '".$id_pd."' ";

		$sc = dbQuery($qr);

		if($sc !== TRUE)
		{
			$this->error = dbError();
		}

		$this->dropZeroMovement();

		return $sc;
	}



	public function updateMoveOut($reference, $id_zone, $id_pd, $qty)
	{
		$qr  = "UPDATE tbl_stock_movement SET move_out = move_out + ".$qty." ";
		$qr .= "WHERE reference = '".$reference."' ";
		$qr .= "AND id_zone = '".$id_zone."' ";
		$qr .= "AND id_product = '".$id_pd."' ";

		$sc = dbQuery($qr);

		if($sc !== TRUE)
		{
			$this->error = dbError();
		}

		$this->dropZeroMovement();

		return $sc;
	}




	private function dropZeroMovement()
	{
		return dbQuery("DELETE FROM tbl_stock_movement WHERE move_out = 0 AND move_in = 0");
	}


	public function getStockBalance($id_pd, $wh_in = '', $date)
	{
		$qr  = "SELECT SUM(move_in) AS move_in, SUM(move_out) AS move_out ";
		$qr .= "FROM tbl_stock_movement WHERE id_product = '".$id_pd."' ";
		$qr .= "AND date_upd <= '".toDate($date)."' ";

		if($wh_in != '')
		{
			$qr .= "AND id_warehouse IN(".$wh_in.") ";
		}

		$qs = dbQuery($qr);

		list($move_in, $move_out) = dbFetchArray($qs);

		$move_in = is_null($move_in) ? 0 : $move_in;
		$move_out = is_null($move_out) ? 0 : $move_out;

		return $move_in - $move_out;
	}


	public function hasMovement($reference)
	{
		$qr = "SELECT id FROM tbl_stock_movement WHERE reference = '".$reference."'";
		$qs = dbQuery($qs);
		if(dbNumRows($qs) > 0)
		{
			return TRUE;
		}

		return FALSE;
	}


}//--- end class

?>
