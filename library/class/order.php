<?php
class order
{
	public $id;
	public $bookcode;
	public $reference;
	public $id_customer;
	public $id_sale;
	public $id_employee;
	public $id_payment; //--- ช่องทางการชำระเงิน
	public $id_channels; //--- ช่องทางการขาย
	public $id_cart;
	public $state;	//---	สถานะออเดอร์ เช่น รอชำระเงิน รอเปิดบิล
	public $isPaid;	//---	จ่ายเงินแล้วหรือยัง
	public $isExpire;	//---	หมดอายุหรือยัง
	public $isCancle;	//---	ยกเลิกหรือไม่
	public $isOnline;		//--- เป็นออเดอร์ออนไลน์หรือไม่
	public $status;	//---	บันทึกแล้วหรือยัง
	public $bDiscText;	//---	ส่วนลดท้ายบิลเป็นข้อความเช่น 20%+5% หรือ 500 + 10%;
	public $bDiscAmount;	//---	มูลค่าส่วนลดท้ายบิล
	public $id_rule;	//--- 	เลขที่นโยบายส่วนลดท้ายบิล
	public $date_add;
	public $date_upd;
	public $emp_upd;	//---	พนักงานที่ทำรายการล่าสุด
	public $isExported;	//---	ส่งออกไป Formula แล้วหรือยัง 1 = Exported | 0 = not export
	public $id_branch;
	public $remark;
	public $online_code;
	public $id_shipping;
	public $shipping_code;
	public $shipping_fee;
	public $service_fee;
	public $hasPayment;	//---	แจ้งชำระแล้วหรือไม่
	public $is_so;
	public $id_budget = 0; //--- ไอดี ของงบประมาณ (กรณี อภินันท์ หรือ สปอนเซอร์)
	public $hasNotSaveDetail = TRUE;

	public function __construct($id = "")
	{
		if( $id != "" )
		{
			$this->getData($id);
		}
	}


	public function getData($id)
	{
		$qs = dbQuery("SELECT * FROM tbl_order WHERE id = ".$id);
		if( dbNumRows($qs) == 1 )
		{
			$rs = dbFetchArray($qs);
			foreach($rs as $key => $value)
			{
				$this->$key = $value;
			}

			$this->hasPayment	= $this->hasPayment($this->id);

			$this->hasNotSaveDetail = $this->hasNotSaveDetail($this->id);
		}
	}





	public function getDataByReference($reference)
	{
		$qs = dbQuery("SELECT * FROM tbl_order WHERE reference = '".$reference."'");
		if( dbNumRows($qs) == 1 )
		{
			$rs = dbFetchArray($qs);
			foreach($rs as $key => $value)
			{
				$this->$key = $value;
			}

			$this->hasPayment	= $this->hasPayment($this->id);

			$this->hasNotSaveDetail = $this->hasNotSaveDetail($this->id);
		}
	}




	//---- Add new order
	public function add(array $ds = array() )
	{
		$sc = FALSE;
		if( ! empty( $ds ) )
		{
			$fields = "";
			$values = "";
			$i = 1;
			foreach( $ds as $field => $value )
			{
				$fields .= $i == 1 ? $field : ", ".$field;
				$values .= $i == 1 ? "'".$value."'" : ", '".$value."'";
				$i++;
			}
			$sc = dbQuery("INSERT INTO tbl_order (".$fields.") VALUES (".$values.")");
		}
		return $sc;
	}






	//----- Update order
	public function update($id, array $ds)
	{
		$sc = FALSE;
		if( count( $ds ) > 0 )
		{
			$set = "";
			$i = 1;
			foreach( $ds as $field => $value )
			{
				$set .= $i == 1 ? $field ." = '".$value."'" : ", ". $field." = '".$value."'";
				$i++;
			}
			$sc = dbQuery("UPDATE tbl_order SET ". $set ." WHERE id = ".$id);
		}
		return $sc;
	}



	public function addDetail(array $ds)
	{
		$sc = FALSE;
		if( count( $ds ) > 0 )
		{
			$fields = "";
			$values = "";
			$i = 1;
			foreach( $ds as $field => $value )
			{
				$fields .= $i == 1 ? $field : ", ".$field;
				$values .= $i == 1 ? "'".$value."'" : ", '".$value."'";
				$i++;
			}
			$sc = dbQuery("INSERT INTO tbl_order_detail (".$fields.") VALUES (".$values.")");
		}
		return $sc;
	}



	//--- Update order detail With id_order_detail
	public function updateDetail($id, array $ds)
	{
		$sc = FALSE;
		if( count( $ds ) > 0 )
		{
			$set = "";
			$i = 1;
			foreach( $ds as $field => $value )
			{
				$set .= $i == 1 ? $field ." = '".$value."'" : ", ". $field." = '".$value."'";
				$i++;
			}
			$sc = dbQuery("UPDATE tbl_order_detail SET ". $set ." WHERE id = ".$id);
		}
		return $sc;
	}



	public function deleteDetail($id)
	{
		return dbQuery("DELETE FROM tbl_order_detail WHERE id = ".$id);
	}



	//---- Get 1 row in order_detail
	public function getDetail($id_order, $id_pd)
	{
		$qs = dbQuery("SELECT * FROM tbl_order_detail WHERE id_order = ".$id_order." AND id_product = '".$id_pd."'");
		return dbNumRows($qs) == 1 ? dbFetchObject($qs) : FALSE;
	}


	//---- Get 1 row in order_detail by id_order_detail
	public function getDetailData($id)
	{
		$qs = dbQuery("SELECT * FROM tbl_order_detail WHERE id = ".$id);
		return dbNumRows($qs) == 1 ? dbFetchObject($qs) : FALSE;
	}





	//--------- ยอดรวมสินค้า 1 รายการ ( isSaved = 1 ) ที่บันทึกแล้ว ใช้ในกรณีคืนเครดิต
	public function getDetailAmountSaved($id)
	{
		$sc = 0;
		$qs = dbQuery("SELECT total_amount FROM tbl_order_detail WHERE id = ".$id." AND isSaved = 1");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}



	//---- get all detail in order
	public function getDetails($id)
	{
		if( $id != "" )
		{
			return dbQuery("SELECT * FROM tbl_order_detail WHERE id_order = ".$id);
		}
		else
		{
			return FALSE;
		}
	}



	//---	รายการที่จัดสินค้าครบแล้ว
	public function getValidDetails($id)
	{
		return dbQuery("SELECT * FROM tbl_order_detail WHERE id_order = ".$id." AND valid = 1");
	}



	//---	รายการที่จัดสินค้ายังไม่ครบ หรือยังไม่ได้จัด
	public function getNotValidDetails($id)
	{
		return dbQuery("SELECT * FROM tbl_order_detail WHERE id_order = ".$id." AND valid = 0");
	}





	//---	จัดครบแล้ว 1 รายการ
	public function validDetail($id_order, $id_pd)
	{
		return dbQuery("UPDATE tbl_order_detail SET valid = 1 WHERE id_order = ".$id_order." AND id_product = '".$id_pd."'");
	}




	//---	จัดเสร็จแล้วทุกรายการ หรือ มีการบังคับจบ
	public function validDetails($id_order)
	{
		return dbQuery("UPDATE tbl_order_detail SET valid = 1 WHERE id_order = ".$id_order);
	}



	public function get_id($reference)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_order WHERE reference = '".$reference."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}





	public function getTotalQty($id_order)
	{
		$sc = 0;
		$qs = dbQuery("SELECT SUM(qty) AS qty FROM tbl_order_detail WHERE id_order = ".$id_order);
		list( $qty ) = dbFetchArray($qs);
		if( ! is_null( $qty ) )
		{
			$sc = $qty;
		}
		return $sc;
	}




	//---

	public function hasDetails($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_order_detail WHERE id_order = ".$id);
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}
		return $sc;
	}



	public function insertDetail(array $ds)
	{
		$sc = FALSE;
		if( count( $ds ) > 0 )
		{
			$fields = "";
			$values = "";
			$i = 1;
			foreach( $ds as $field => $value )
			{
				$fields .= $i == 1 ? $field : ", ".$field;
				$values .= $i == 1 ? "'".$value."'" : ", '".$value."'";
				$i++;
			}
			$sc = dbQuery("INSERT INTO tbl_order_detail (".$fields.") VALUES (".$values.")");
		}
		return $sc;
	}





	public function isExistsDetail($id_order, $id_pd)
	{
		$qs = dbQuery("SELECT id FROM tbl_order_detail WHERE id_order = ".$id_order." AND id_product = '".$id_pd."'");
		return dbNumRows($qs) == 1 ? TRUE : FALSE;
	}





	public function cancleDetail($id)
	{
		return dbQuery("UPDATE tbl_order_detail SET is_cancle = 1 WHERE id = ".$id);
	}




	public function exported($id)
	{
		return dbQuery("UPDATE tbl_order SET isExported = 1 WHERE id = ".$id);
	}



	//----- Change status
	public function changeStatus($id, $status)
	{
		$id_emp = getCookie('user_id');
		return dbQuery("UPDATE tbl_order SET status = ".$status.", emp_upd = ".$id_emp." WHERE id = ".$id);
	}





	public function hasNotSaveDetail($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT COUNT(*) AS qty FROM tbl_order_detail WHERE id_order = ".$id." AND isSaved = 0");
		list( $count ) = dbFetchArray($qs);
		if( $count > 0 )
		{
			$sc = TRUE;
		}
		return $sc;
	}





	public function saveDetail($id)
	{
		return dbQuery("UPDATE tbl_order_detail SET isSaved = 1 WHERE id = ".$id." AND isSaved = 0");
	}







	public function unSaveDetail($id)
	{
		return dbQuery("UPDATE tbl_order_detail SET isSaved = 0 WHERE id = ".$id." AND isSaved = 1");
	}






	public function saveDetails($id)
	{
		return dbQuery("UPDATE tbl_order_detail SET isSaved = 1 WHERE id_order = ".$id." AND isSaved = 0");
	}





	public function unSaveDetails($id)
	{
		return dbQuery("UPDATE tbl_order_detail SET isSaved = 0 WHERE id_order = ".$id." AND isSaved = 1");
	}




	public function stateChange($id, $state)
	{
		$id_emp = getCookie('user_id');
		$rs = dbQuery("UPDATE tbl_order SET state = ".$state.", emp_upd = ".$id_emp." WHERE id = ".$id);
		$cs = new state();
		$cs->add($id, $state, $id_emp);

		return $rs;
	}




	//-----------------  New Reference --------------//
	public function getNewReference($role = 1,$date = '')
	{
		$date = $date == '' ? date('Y-m-d') : $date;
		$Y		= date('y', strtotime($date));
		$M		= date('m', strtotime($date));
		$prefix = $this->getPrefix($role);
		$runDigit = getConfig('RUN_DIGIT'); //--- รันเลขที่เอกสารกี่หลัก
		$preRef = $prefix . '-' . $Y . $M;
		$qs = dbQuery("SELECT MAX(reference) AS reference FROM tbl_order WHERE reference LIKE '".$preRef."%' ORDER BY reference DESC");
		list( $ref ) = dbFetchArray($qs);
		if( ! is_null( $ref ) )
		{
			$runNo = mb_substr($ref, ($runDigit*-1), NULL, 'UTF-8') + 1;
			$reference = $prefix . '-' . $Y . $M . sprintf('%0'.$runDigit.'d', $runNo);
		}
		else
		{
			$reference = $prefix . '-' . $Y . $M . sprintf('%0'.$runDigit.'d', '001');
		}
		return $reference;
	}



	public function getPrefix($role)
	{
		switch($role){
			case 1 :
				$prefix = getConfig('PREFIX_ORDER');
				break;
			case 2 :
				$prefix = getConfig('PREFIX_CONSIGNMENT');
				break;
			case 3 :
				$prefix = getConfig('PREFIX_SUPPORT');
				break;
			case 4 :
				$prefix = getConfig('PREFIX_SPONSOR');
				break;
			case 5 :
				$prefix = getConfig('PREFIX_TRANSFORM');
				break;
			case 6 :
				$prefix = getConfig('PREFIX_LEND');
				break;
			case 7 :
				$prefix = getConfig('PREFIX_REQUISITION');
				break;
			default :
					$prefix = getConfig('PREFIX_ORDER');
				break;
		}

		return $prefix;

	}


	public function getReference($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT reference FROM tbl_order WHERE id = ".$id);
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}

		return $sc;
	}






	public function getOrderQty($id_order, $id_pd)
	{
		$sc = 0;
		$qs = dbQuery("SELECT qty FROM tbl_order_detail WHERE id_order = ".$id_order." AND id_product = '".$id_pd."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}





	//-- มูลค่าสินค้ารามทั้งบิล
	public function getTotalAmount($id)
	{
		$qs = dbQuery("SELECT SUM(total_amount) AS amount FROM tbl_order_detail WHERE id_order = '".$id."'");
		list( $amount ) = dbFetchArray($qs);
		return is_null( $amount ) ? 0.00 : $amount;
	}





	public function getTotalAmountNotSave($id)
	{
		$qs = dbQuery("SELECT SUM(total_amount) AS amount FROM tbl_order_detail WHERE id_order = '".$id."' AND isSaved = 0");
		list( $amount ) = dbFetchArray($qs);
		return is_null( $amount ) ? 0.00 : $amount;
	}





	public function getTotalAmountSaved($id)
	{
		$qs = dbQuery("SELECT SUM(total_amount) AS amount FROM tbl_order_detail WHERE id_order = '".$id."' AND isSaved = 1");
		list( $amount ) = dbFetchArray($qs);
		return is_null( $amount ) ? 0.00 : $amount;
	}






	public function orderQty($id)
	{
		$qs = dbQuery("SELECT SUM(qty) AS qty FROM tbl_order_detail WHERE id_order = ".$id);
		list( $qty ) = dbFetchArray($qs);
		return is_null( $qty ) ? 0 : $qty;
	}






	public function orderItems($id)
	{
		return  dbNumRows( dbQuery("SELECT id FROM tbl_order_detail	WHERE id_order = ".$id) );
	}





	//--- Use In class discount สำหรับ เก็บยอดรวมสินค้าในเงื่อนไขเดียวกัน กรณีที่จำนวนสั่งหรือมูลค่าสั่งซื้อ สามารถรวมกันได้
	public function getSumOrderStyleQty($id_order, $id_style)
	{
		$qs = dbQuery("SELECT SUM(qty) AS qty FROM tbl_order_detail WHERE id_order = '".$id_order."' AND id_style = '".$id_style."'");
		list( $qty ) = dbFetchArray($qs);
		return is_null( $qty ) ? 0 : $qty;
	}




	public function getNotExportData()
	{
		return dbQuery("SELECT id FROM tbl_order WHERE isExported = 0 AND isCancle = 0");
	}




	public function getReservQty($id_pd)
	{
		$qs = dbQuery("SELECT SUM(qty) AS qty FROM tbl_order_detail WHERE id_product = '".$id_pd."' AND valid = 0");
		list( $qty ) = dbFetchArray($qs);
		return is_null( $qty ) ? 0 : $qty;
	}





	public function getStyleReservQty($id_style)
	{
		$qs = dbQuery("SELECT SUM(qty) AS qty FROM tbl_order_detail WHERE id_style = '".$id_style."' AND valid = 0");
		list( $qty ) = dbFetchArray($qs);
		return is_null( $qty ) ? 0 : $qty;
	}






	public function calculateDiscount($id, array $ds = array())
	{
		$sc = TRUE;
		if( count( $ds ) > 0 )
		{
			$this->getData($id);
			$disc = new discount();
			$qs = $this->getDetails($id);
			if( dbNumRows($qs) > 0 )
			{
				while( $rs = dbFetchObject($qs) )
				{
					$discount = $disc->getItemRecalDiscount($id, $rs->id_product, $rs->price, $this->id_customer, $rs->qty, $this->id_payment, $this->id_channels, $this->date_add);

					$arr = array(
								"discount" => $discount['discount'],
								"discount_amount"	=> $discount['amount'],
								"total_amount" => ($rs->qty * $rs->price) - $discount['amount'],
								"id_rule"	=> $discount['id_rule']
								);

					$cs = $this->updateDetail($rs->id, $arr);

					if( $cs === FALSE )
					{
						$sc = FALSE;
					}

				} //--- End while

			} //--- End if dbNumRows

		}	//--- End if count

		return $sc;
	}	//--- End function





	public function searchOnlineCode($txt)
	{
		return dbQuery("SELECT DISTINCT online_code FROM tbl_order WHERE online_code LIKE '%".$txt."%'");
	}





	public function hasPayment($id)
	{
		$payment = new payment();
		return $payment->isExists($id);
	}






	public function getOnlineCode($id)
	{
		$sc = "";
		$qs = dbQuery("SELECT online_code FROM tbl_order WHERE id = ".$id);
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}







	public function paid($id)
	{
		return dbQuery("UPDATE tbl_order SET isPaid = 1 WHERE id = ".$id);
	}





	public function unPaid($id)
	{
		return dbQuery("UPDATE tbl_order SET isPaid = 0 WHERE id = ".$id);
	}





	public function roleName($role)
	{
		return dbQuery("SELECT name FROM tbl_order_role WHERE id = ".$role);
	}




	public function getState($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT state FROM tbl_order WHERE id = ".$id);
		if( dbNumRows($qs) == 1)
		{
			list( $sc ) = dbFetchArray($qs);
		}

		return $sc;
	}





	//---	บันทึกขายสินค้าตามยอดที่ได้
	//---	หาก ออเดอร์มากกว่ายอด ตรวจ ใช้ยอดตรวจ บันทึกขาย
	//---	หากยอดตรวจมากกว่าออเดอร์ ใช้ยอดจากออเดอร์ บันทึกขาย
	public function sold(array $ds = array())
	{
		$sc = FALSE;

		if( ! empty($ds))
		{
			$fields = "";
			$values = "";
			$i = 1;
			foreach($ds as $field => $value)
			{
				$fields .= $i == 1 ? $field : ", ".$field;
				$values .= $i == 1 ? "'".$value."'" : ", '".$value."'";
				$i++;
			}

			$sc = dbQuery("INSERT INTO tbl_order_sold (".$fields.") VALUES (".$values.")");
		}

		return $sc;
	}







	//---	รายการที่บันทึกขายไว้ เพื่อส่งออกไป Formula
	public function getSoldDetails($id)
	{
		$qr  = "SELECT id, id_order, reference, id_role, role_name, payment, channels, id_product, ";	//---	ข้อมูลออเดอร์
		$qr .= "product_code, product_name, color, color_group, size, size_group, product_style, ";	//--- ข้อมูลสินค้า
		$qr .= "product_group, product_category, product_kind, product_type, brand, year, ";	//---	ข้อมูลสินค้า(ต่อ)
		$qr .= "cost_ex, cost_inc, price_ex, price_inc, sell_ex, sell_inc, ";	//---	ข้อมูลสินค้า (ต่อ)
		$qr .= "SUM(qty) AS qty, ";	//---	จำนวนขายรวม
		$qr .= "discount_label, ";	//---	ส่วนลดเป็นตัวอักษร
		$qr .= "SUM(discount_amount) AS discount_amount, ";	//---	ส่วนลดรวม
		$qr .= "SUM(total_amount_ex) AS total_amount_ex, ";	//---	ยอดขายรวม ไม่รวม VAT
		$qr .= "SUM(total_amount_inc) AS total_amount_inc, ";	//---	ยอดขายรวม รวม VAT
		$qr .= "SUM(total_cost_ex) AS total_cost_ex, ";	//---	ต้นทุนรวม  ไม่รวม VAT
		$qr .= "SUM(total_cost_inc) AS total_cost_inc, ";	//---	ต้นทุนรวม รวม VAT
		$qr .= "SUM(margin_ex) AS margin_ex, ";	//---	กำไรขั้นต้น ไม่รวม VAT
		$qr .= "SUM(margin_inc) AS margin_inc, ";	//---	กำไรขั้นต้น รวม VAT
		$qr .= "id_policy, policy_code, policy_name, ";	//---	นโยบายส่วนลด
		$qr .= "id_rule, rule_code, rule_name, ";	//---	เงื่อนไขนโยบายส่วนลด
		$qr .= "id_customer, customer_code, customer_name, customer_group, customer_type, ";	//---	ข้อมูลลูกค้า
		$qr .= "customer_kind, customer_class, customer_area, province, ";	//---	ข้อมูลลูกค้า (ต่อ)
		$qr .= "id_sale, sale_code, sale_name, id_employee, employee_name, date_add, date_upd, id_zone, id_warehouse ";	//---	ขอ้มูลพนักงานขาย และ อื่นๆ
		$qr .= "FROM tbl_order_sold WHERE id_order = ".$id." GROUP BY id_product, id_warehouse";

		return dbQuery($qr);
	}

	//---	เรียกยอดขายรวมทั้งออเดอร์ แบบ รวม VAT หรือ ไม่รวม VAT
	public function getTotalSoldAmount($id, $inc = TRUE)
	{

		if( $inc === TRUE)
		{
			$qr = "SELECT SUM(total_amount_inc) AS amount FROM tbl_order_sold WHERE id_order = ".$id;
		}
		else
		{
			$qr = "SELECT SUM(total_amount_ex) AS amount FROM tbl_order_sold WHERE id_order = ".$id;
		}

		$qs = dbQuery($qr);

		list( $amount ) = dbFetchArray($qs);

		return is_null($amount) ? 0 : $amount;
	}




	public function getTotalSoldQty($id)
	{
		$qs = dbQuery("SELECT SUM(qty) AS qty FROM tbl_order_sold WHERE id_order = ".$id);

		list( $qty ) = dbFetchArray($qs);

		return is_null($qty) ? 0 : $qty;
	}



	//---	ชื่อผู้ที่เปิดบิล
	public function getBilledEmployee($reference)
	{
		$sc = '';
		$qs = dbQuery("SELECT emp_upd FROM tbl_order_sold WHERE reference = '".$reference."' LIMIT 1");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}

		return $sc;
	}





	public function isExitsTransection($id_customer, $role)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_order_sold WHERE id_customer = '".$id_customer."' AND id_role = ".$role." LIMIT 1");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}

		return $sc;
	}

}//--- End Class

?>
