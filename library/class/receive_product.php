<?php
class receive_product
{
	public $id;
	public $bookcode;
	public $reference;
	public $id_supplier;
	public $po;
	public $invoice;
	public $id_employee;
	public $date_add;
	public $date_upd;
	public $emp_upd;
	public $isCancle;
	public $remark;
	public $isExported;
	public $approver;
	public $approvKey;
	public $credit_term = 0;


	public function __construct($id = "")
	{
		if( $id != "" )
		{
			$qs = dbQuery("SELECT * FROM tbl_receive_product WHERE id = ".$id);

			if( dbNumRows($qs) == 1 )
			{
				$rs = dbFetchObject($qs);
				$this->id 	= $rs->id;
				$this->bookcode	= $rs->bookcode;
				$this->reference 	= $rs->reference;
				$this->id_supplier	= $rs->id_supplier;
				$this->po			= $rs->po;
				$this->invoice		= $rs->invoice;
				$this->id_employee = $rs->id_employee;
				$this->date_add	= $rs->date_add;
				$this->date_upd	= $rs->date_upd;
				$this->emp_upd	= $rs->emp_upd;
				$this->isCancle		= $rs->isCancle;
				$this->remark		= $rs->remark;
				$this->isExported	= $rs->isExported;
				$this->approver	= $rs->approver;
				$this->approvKey	= $rs->approvKey;
			}
		}
	}


	public function add(array $ds)
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
			$sc = dbQuery("INSERT INTO tbl_receive_product (".$fields.") VALUES (".$values.")");
		}
		return $sc;
	}

	public function getDetail($id)
	{
		return dbQuery("SELECT * FROM tbl_receive_product_detail WHERE id_receive_product = ".$id);
	}


	public function get_id($reference)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_receive_product WHERE reference = '".$reference."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}

	public function getTotalQty($id_receive_product)
	{
		$sc = 0;
		$qs = dbQuery("SELECT SUM(qty) AS qty FROM tbl_receive_product_detail WHERE id_receive_product = ".$id_receive_product);
		list( $qty ) = dbFetchArray($qs);
		if( ! is_null( $qty ) )
		{
			$sc = $qty;
		}
		return $sc;
	}


	public function hasDetails($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_receive_product_detail WHERE id_receive_product = ".$id);
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
			$sc = dbQuery("INSERT INTO tbl_receive_product_detail (".$fields.") VALUES (".$values.")");
		}
		return $sc;
	}



	public function cancleDetail($id)
	{
		return dbQuery("UPDATE tbl_receive_product_detail SET is_cancle = 1 WHERE id = ".$id);
	}



	public function cancleReceived($id, $emp)
	{
		return dbQuery("UPDATE tbl_receive_product SET isCancle = 1, emp_upd = ".$emp." WHERE id = ".$id);
	}


	public function exported($id)
	{
		return dbQuery("UPDATE tbl_receive_product SET isExported = 1 WHERE id = ".$id);
	}



	//-----------------  New Reference --------------//
	public function getNewReference($date = '')
	{
		$date = $date == '' ? date('Y-m-d') : $date;
		$Y		= date('y', strtotime($date));
		$M		= date('m', strtotime($date));
		$runDigit = getConfig('RUN_DIGIT');
		$prefix = getConfig('PREFIX_RECEIVE');
		$preRef = $prefix . '-' . $Y . $M;
		$qs = dbQuery("SELECT MAX(reference) AS reference FROM tbl_receive_product WHERE reference LIKE '".$preRef."%' ORDER BY reference DESC");
		list( $ref ) = dbFetchArray($qs);
		if( ! is_null( $ref ) )
		{
			$runNo = mb_substr($ref, ($runDigit*(-1)), NULL, 'UTF-8') + 1;
			$reference = $prefix . '-' . $Y . $M . sprintf('%0'.$runDigit.'d', $runNo);
		}
		else
		{
			$reference = $prefix . '-' . $Y . $M . sprintf('%0'.$runDigit.'d', '001');
		}
		return $reference;
	}


	//-- มูลค่าสินค้ารามทั้งบิล
	public function getTotalAmount($id, $poCode)
	{
		$sc = 0;
		$qs = $this->getDetail($id);
		if( dbNumRows($qs) > 0 )
		{
			$po = new po();
			while( $rs = dbFetchObject($qs) )
			{
				$price = $po->getPrice($poCode, $rs->id_product);
				$sc += $rs->qty * $price;
			}
		}
		return $sc;
	}



	public function getNotExportData()
	{
		return dbQuery("SELECT id FROM tbl_receive_product WHERE isExported = 0 AND isCancle = 0");
	}
}//--end class

?>
