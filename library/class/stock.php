<?php
class stock
{
	public $error = '';

	public function __construct()
	{

	}



	public function updateStockZone($id_zone, $id_pd, $qty)
	{
		$sc = FALSE;
		$zone = new zone();
		$cQty = $this->isExists($id_zone, $id_pd); ///-- return FALSE if not exists , return current qty if exists
		$auz  = $zone->isAllowUnderZero($id_zone); //--- อนุญาติให้ติดลบได้หรือไม่
		if( $cQty !== FALSE )
		{
			//--- ถ้าจำนวนที่ update รวมกับจำนวนที่มีแล้ว มากกว่า 0
			//--- หรือถ้าน้อยกว่าแต่โซนนี้ยอมให้ติดลบได้
			if( ( $cQty + $qty ) >= 0 OR $auz === TRUE )
			{
				$sc = $this->update($id_zone, $id_pd, $qty);
			}
			else
			{
				$this->error = 'This Warehouse cannot store under zero stock';
			}
		}
		else
		{
			if( $qty >= 0 OR $auz === TRUE )
			{
				$sc =  $this->add( $id_zone, $id_pd, $qty );
			}
			else
			{
				$this->error = 'This Warehouse cannot store under zero stock';
			}
		}

		$this->removeZero();

		return $sc;
	}





	private function add($id_zone, $id_pd, $qty)
	{
		return dbQuery("INSERT INTO tbl_stock (id_zone, id_product, qty) VALUES ('".$id_zone."', '".$id_pd."', '".$qty."')");
	}





	private function update($id_zone, $id_pd, $qty)
	{
		return dbQuery("UPDATE tbl_stock SET qty = qty + ".$qty." WHERE id_zone = '".$id_zone."' AND id_product = '".$id_pd."'");
	}






	private function removeZero()
	{
		return dbQuery("DELETE FROM tbl_stock WHERE qty = 0");
	}





	private function isExists($id_zone, $id_pd)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT qty FROM tbl_stock WHERE id_zone = ".$id_zone." AND id_product = '".$id_pd."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}





	//---	มีสต็อกคงเหลือเพียงพอให้ตัดหรือไม่
	public function isEnough($id_zone, $id_pd, $qty)
	{
		$qs = dbQuery("SELECT id_stock FROM tbl_stock WHERE id_zone = '".$id_zone."' AND id_product = '".$id_pd."' AND qty >= ".$qty);
		return dbNumRows($qs) == 1 ? TRUE : FALSE;
	}




	public function getStockZone($id_zone, $id_pd)
	{
		$sc = 0;
		$qs = dbQuery("SELECT qty FROM tbl_stock WHERE id_zone = ".$id_zone." AND id_product = '".$id_pd."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}




	//----- จำนวนรวมทุกโซนทุกคลัง ที่คลัง Active
	public function getStock($id_pd)
	{
		$sc = 0;
		$qr = "SELECT SUM(s.qty) FROM tbl_stock AS s ";
		$qr .= "JOIN tbl_product AS p ON s.id_product = p.id ";
		$qr .= "JOIN tbl_zone AS z ON s.id_zone = z.id_zone ";
		$qr .= "JOIN tbl_warehouse AS w ON z.id_warehouse = w.id ";
		$qr .= "WHERE s.id_product = '".$id_pd."' AND p.is_deleted = 0 AND w.active = 1";
		$qs = dbQuery($qr);
		list( $qty ) = dbFetchArray($qs);
		if( ! is_null( $qty ) )
		{
			$sc = $qty;
		}
		return $sc;
	}





	//----- จำนวนรวมทุกโซนทุกคลัง ทุกสถานะ
	public function getAllStock($id_pd)
	{
		$sc = 0;
		$qs = dbQuery("SELECT SUM(qty) FROM tbl_stock WHERE id_product = '".$id_pd."'");
		list( $qty ) = dbFetchArray($qs);
		if( ! is_null( $qty ) )
		{
			$sc = $qty;
		}
		return $sc;
	}





	//--- จำนวนรวมทุกคลังทุกโซนเป็นรุ่น เฉพาะ คลังที่ Active
	public function getStyleStock($id_style)
	{
		$sc = 0;
		$qr = "SELECT SUM(s.qty) FROM tbl_stock AS s ";
		$qr .= "JOIN tbl_zone AS z ON s.id_zone = z.id_zone ";
		$qr .= "JOIN tbl_product AS p ON s.id_product = p.id ";
		$qr .= "JOIN tbl_warehouse AS w ON z.id_warehouse = w.id ";
		$qr .= "WHERE p.id_style = '".$id_style."' AND p.is_deleted = 0 AND w.active = 1";
		$qs = dbQuery($qr);
		list( $qty ) = dbFetchArray($qs);
		if( ! is_null( $qty ) )
		{
			$sc = $qty;
		}
		return $sc;
	}





	//--- จำนวนรวมทุกคลังทุกโซนเป็นรุ่น ทั้ง Active และ ไม่ Actie
	public function getAllStyleStock($id_style)
	{
		$sc = 0;
		$qs = dbQuery("SELECT SUM(qty) AS qty FROM tbl_stock AS s JOIN tbl_product AS p ON s.id_product = p.id WHERE id_style = '".$id_style."'");
		list( $qty ) = dbFetchArray($qs);
		if( ! is_null( $qty ) )
		{
			$sc = $qty;
		}
		return $sc;
	}





	//---- จำนวนรวมสินค้าที่คลังระบุว่า ขายได้
	public function getSellStock($id_pd)
	{
		$qr = "SELECT SUM(qty) AS qty FROM tbl_stock AS s ";
		$qr .= "JOIN tbl_product AS p ON s.id_product = p.id ";
		$qr .= "JOIN tbl_zone AS z ON s.id_zone = z.id_zone ";
		$qr .= "JOIN tbl_warehouse AS w ON z.id_warehouse = w.id ";
		$qr .= "WHERE s.id_product = '".$id_pd."' AND p.can_sell = 1 AND p.active = 1 AND p.is_deleted = 0 AND w.sell = 1 AND w.active = 1";
		$qs = dbQuery($qr);
		list( $qty ) = dbFetchArray($qs);

		return is_null( $qty ) ? 0 : $qty;
	}





	public function getStyleSellStock($id_style)
	{
		$qr = "SELECT SUM(qty) AS qty FROM tbl_stock AS s ";
		$qr .= "JOIN tbl_product AS p ON s.id_product = p.id ";
		$qr .= "JOIN tbl_zone AS z ON s.id_zone = z.id_zone ";
		$qr .= "JOIN tbl_warehouse AS w ON z.id_warehouse = w.id ";
		$qr .= "WHERE p.id_style = '".$id_style."' AND p.can_sell = 1 AND p.active = 1 AND p.is_deleted = 0 AND w.sell = 1 AND w.active = 1";
		$qs = dbQuery($qr);
		list( $qty ) = dbFetchArray($qs);
		return is_null( $qty ) ? 0 : $qty;
	}





	//---- แสดงที่เก็บสินค้า สำหรับการจัดสินค้า
	public function stockInZone($id_pd)
	{
		$qr = "SELECT z.zone_name AS name, s.qty FROM tbl_stock AS s ";
		$qr .= "JOIN tbl_zone AS z ON s.id_zone = z.id_zone ";
		$qr .= "JOIN tbl_warehouse AS w ON z.id_warehouse = w.id ";
		$qr .= "WHERE id_product = '".$id_pd."' AND w.prepare = 1 AND w.active = 1 ";

		return dbQuery($qr);
	}

}//--- end class

?>
