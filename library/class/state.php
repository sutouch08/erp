<?php 
class state 
{
	public function __construct(){}
	
	public function getName($id)
	{
		$sc = 1;
		$qs = dbQuery("SELECT name FROM tbl_state WHERE id = ".$id);
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);	
		}
		return $sc;
	}
	
	public function getColor($id)
	{
		$sc = "";
		$qs = dbQuery("SELECT color FROM tbl_state WHERE id = ".$id);
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}
	
	public function getOrderStateList($id_order)
	{
		$qr = "SELECT o.id_employee, o.date_upd, s.name, s.color FROM tbl_order_state AS o ";
		$qr .= "JOIN tbl_state AS s ON o.id_state = s.id WHERE o.id_order = ".$id_order." ORDER BY o.date_upd DESC, s.id ASC";
		return dbQuery($qr);	
	}
	
}//--- end class