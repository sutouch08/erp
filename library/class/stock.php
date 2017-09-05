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
	
}//--- end class

?>