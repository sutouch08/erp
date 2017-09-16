<?php
class discount 
{
	public function __construct()
	{
		
	}
	
	
	public function getItemDiscount($id_pd, $id_cus, $qty, $id_payment, $id_channels)
	{
		$pd = new product($id_pd);
		$discount = 40;
		$sc = array(
							"discount"	=> '40%', //-- ส่วนลด
							"amount"		=> $qty * ( $pd->price * ($discount * 0.01 ) ), 
							"id_rule"		=> "1" //---  id of discount rule
							);
		return $sc;						
	}
	
	public function getBillDiscount($id_order)
	{
		$sc = array(
							"discount_step_1" => 	10,
							"unit_step_1"	 => "percent",
							"discount_stem_2"	=> 5,
							"unit_step_2"	=> "percent",
							"id_rule"				=> "2"
							);
		return $sc;
	}
	
	/*
	public function add(array $ds)
	{
		$sc = FALSE;
		if( count( $ds ) > 0 )
		{
			$fields	= "";
			$values	= "";
			$i	= 1;
			foreach( $ds as $field => $value )
			{
				$fields	.= $i == 1 ? $field : ", ".$field;
				$values	.= $i == 1 ? "'".$value."'" : ", '".$value."'";
				$i++;	
			}
			$sc = dbQuery("INSERT INTO tbl_customer_discount (".$fields.") VALUES (".$values.")");
		}
		return $sc;
	}
	
	
	
	
	public function update($id, array $ds)
	{
		$sc = FALSE;
		if( count( $ds ) > 0 )
		{
			$set	= "";
			$i 		= 1;
			foreach( $ds as $field => $value )
			{
				$set .= $i == 1 ? $field." = '".$value."'" : ", ".$field." = '".$value."'";
				$i++;
			}
			$sc = dbQuery("UPDATE tbl_customer_discount SET ".$set." WHERE id = '".$id."'");
		}
		return $sc;
	}
	
	
	
	public function delete($id)
	{
		return dbQuery("DELETE FROM tbl_customer_discount WHERE id = ".$id);	
	}
	
	//--------------------- เฉพาะกรณีที่ใช้กลุ่มสินค้าในการอ้างอิงเท่านั้น ---------------------//
	public function getDiscountIdByGroup($id_customer, $id_group)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_customer_discount WHERE id_customer = '".$id_customer."' AND id_product_group = '".$id_group."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}
	
	
	//----------------- ตรวจสอบว่า มีส่วนลดกับกลุ่มสินค้าสำหรับลูกค้าค้นนี้อยู่หรือไม่------------//
	public function isExistsGroupDiscount($id_customer, $id_group)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT discount FROM tbl_customer_discount WHERE id_customer = '".$id_customer."' AND id_product_group = '".$id_group."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;	
		}
		return $sc;
	}
	
	*/
	
	
}//-- end class

?>