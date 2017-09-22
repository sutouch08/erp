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
	public $hasPayment;
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
			$rs = dbFetchObject($qs);	
			$this->id		= $rs->id;
			$this->bookcode	= $rs->bookcode;
			$this->reference	= $rs->reference;
			$this->id_customer	= $rs->id_customer;
			$this->id_sale		= $rs->id_sale;
			$this->id_employee	= $rs->id_employee;
			$this->id_payment		= $rs->id_payment;
			$this->id_channels	= $rs->id_channels;
			$this->id_cart		= $rs->id_cart;
			$this->state			= $rs->state;
			$this->isPaid		= $rs->isPaid;
			$this->isExpire		= $rs->isExpire;
			$this->isCancle		= $rs->isCancle;
			$this->isOnline		= $rs->isOnline;
			$this->status		= $rs->status;
			$this->bDiscText	= $rs->bDiscText;
			$this->bDiscAmount	= $rs->bDiscAmount;
			$this->id_rule		= $rs->id_rule;
			$this->date_add	= $rs->date_add;
			$this->date_upd	= $rs->date_upd;
			$this->emp_upd	= $rs->emp_upd;	
			$this->isExported	= $rs->isExported;	
			$this->id_branch	= $rs->id_branch;
			$this->remark		= $rs->remark;		
			$this->online_code	= $rs->online_code; //----- รหัสอ้างอิงลูกค้าออนไลน์
			$this->id_shipping	= $rs->id_shipping;
			$this->shipping_code	= $rs->shipping_code;
			$this->shipping_fee	= $rs->shipping_fee;
			$this->service_fee	= $rs->service_fee;
			
			$this->hasPayment	= $this->hasPayment($rs->id);
			
			$this->hasNotSaveDetail = $this->hasNotSaveDetail($rs->id);
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
		if( $id !== "" )
		{
			return dbQuery("SELECT * FROM tbl_order_detail WHERE id_order = ".$id);	
		}
		else
		{
			return FALSE;
		}
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
	public function getNewReference($date = '')
	{
		$date = $date == '' ? date('Y-m-d') : $date;
		$Y		= date('y', strtotime($date));
		$M		= date('m', strtotime($date));
		$prefix = getConfig('PREFIX_ORDER');
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
	
}//--- End Class

?>