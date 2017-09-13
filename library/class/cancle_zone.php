<?php

class cancle_zone
{
	public function __construct(){}
	
	public function getCancleQty($id_pd)
	{
		$qr = "SELECT SUM(qty) AS qty FROM tbl_cancle AS c ";
		$qr .= "JOIN tbl_warehouse AS w ON c.id_warehouse = w.id ";
		$qr .= "WHERE c.id_product = '".$id_pd."' AND w.sell = 1";
		$qs = dbQuery($qr);
		list( $qty ) = dbFetchArray($qs);
		
		return is_null( $qty ) ? 0 : $qty;	
	}
	
	public function getStyleCancleQty($id_style)
	{
		$qr = "SELECT SUM(qty) AS qty FROM tbl_cancle AS c ";
		$qr .= "JOIN tbl_warehouse AS w ON c.id_warehouse = w.id ";
		$qr .= "WHERE c.id_style = '".$id_style."' AND w.sell = 1";
		$qs = dbQuery($qr);
		list( $qty ) = dbFetchArray($qs);
		return is_null( $qty ) ? 0 : $qty;	
	}
	
	
}
?>