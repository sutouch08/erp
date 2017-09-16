<?php 
class state 
{
	public function __construct(){}
	
	//--- 1 = รอชำระเงิน
	//--- 2 = แจ้งชำระเงิน
	//--- 3 = รอจัดสินค้า
	//--- 4 = กำลังจัดสินค้า
	//--- 5 = รอแพ็ค
	//--- 6 = กำลังแพ็ค
	//--- 7 = รอเปิดบิล
	//--- 8 = รอการจัดส่ง
	//--- 9 = กำลังจัดส่ง
	//--- 10 = จัดส่งแล้ว
	//--- 11 = ยกเลิก
	public function add($id_order, $id_state, $id_employee)
	{
		return dbQuery("INSERT INTO tbl_order_state (id_order, id_state, id_employee) VALUES (".$id_order.", ".$id_state.", ".$id_employee.")");
	}
	
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