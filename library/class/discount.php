<?php
class discount 
{
	public function __construct()
	{
		
	}
	
	
	public function getDiscount($id_pd, $id_cus, $qty, $payment = "credit")
	{
		$sc = array(
							"discount"	=> 40, //-- ส่วนลด
							"unit"			=> "percent", //-- หน่วย
							"type"			=> "item", //--- item = ที่รายการ bill = ท้ายบิล
							"pCode"		=> "MD-1701001",
							"idRule"		=> "1"
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