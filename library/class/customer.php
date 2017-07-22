<?php
class customer {
	public $id; 				//-- id customer
	public $code;			//-- Customer code
	public $pre_name;		//-- Prefix name
	public $name;			//-- Customer's name
	public $tel;				//-- Phone No.
	public $fax;				//-- Fax No.
	public $mobile;			//-- Mobile No.
	public $m_id;			//-- Citizen ID
	public $tax_id;
	public $contact;			//-- Contact Person Name
	public $email;
	public $group_id;		//-- Group of customer
	public $area_id;		//-- Area of customer
	public $credit;			//-- Credti Amount
	public $term;			//-- Credit Term
	public $address_no;	//-- address no like 75/65, 
	public $room_no;		//-- Room no.
	public $floor_no;		//-- ชั้นที่
	public	$building;			
	public $soi;				//-- ซอย
	public $village_no;		//-- Moo
	public $road;
	public $tambon;			//-- ตำบล
	public $amphur;			//-- อำเภอ
	public $province;		//-- จังหวัด
	public $zip;				//-- Post code , zip code
	public $active;			//-- status 1 = Active,  0 = Inactive
	public function __construct( $id = '')
	{
		if( $id != '' )
		{
			$qs = dbQuery("SELECT * FROM tbl_customers WHERE id = '".$id."'");
			if( dbNumRows($qs) == 1 )
			{
				$rs = dbFetchObject($qs);
				$this->id 			= $rs->id;
				$this->code			= $rs->code;
				$this->pre_name	= $rs->pre_name;
				$this->name			= $rs->name;
				$this->tel				= $rs->tel;
				$this->fax			= $rs->fax;
				$this->mobile		= $rs->mobile;
				$this->m_id			= $rs->m_id;
				$this->tax_id		= $rs->tax_id;
				$this->contact		= $rs->contact;
				$this->email			= $rs->email;
				$this->group_id	= $rs->group_id;
				$this->area_id		= $rs->area_id;
				$this->credit		= $rs->credit;
				$this->term			= $rs->term;
				$this->address_no	= $rs->address_no;
				$this->room_no	= $rs->room_no;
				$this->floor_no		= $rs->floor_no;
				$this->building		= $rs->building;
				$this->soi			= $rs->soi;
				$this->road			= $rs->road;
				$this->village_no	= $rs->village_no;
				$this->tambon		= $rs->tambon;
				$this->amphur		= $rs->amphur;
				$this->province	= $rs->province;
				$this->zip			= $rs->zip;
				$this->active		= $rs->active;
			}
		}
	}
	
	public function add(array $ds)
	{
		$sc = FALSE;
		if( count($ds) > 0 )
		{
			$fields	= "";
			$values	= "";
			$i			= 1;
			foreach( $ds as $field => $value )
			{
				$fields	.= $i == 1 ? $field : ", ".$field;
				$values	.= $i == 1 ? "'". $value ."'" : ", '". $value ."'";
				$i++;	
			}
			$sc = dbQuery("INSERT INTO tbl_customer (".$fields.") VALUES (".$values.")");
		}
		return $sc;			
	}
	
	
	public function update($id, array $ds)
	{
		$sc = FALSE;
		if( count( $ds ) > 0 )
		{
			$set 	= "";
			$i		= 1;
			foreach( $ds as $field => $value )
			{
				$set .= $i == 1 ? $field . " = '" . $value . "'" : ", ".$field . " = '" . $value . "'";
				$i++;	
			}
			$sc = dbQuery("UPDATE tbl_customer SET " . $set . " WHERE id = '".$id."'");
		}
		return $sc;
	}
	
	public function isExists($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_customer WHERE id = '".$id."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;	
		}
		return $sc;
	}
	
	
	
	public function getCustomerCode($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT code FROM tbl_customer WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}
	
	
	
	public function getCustomerId($code)
	{
		$sc = 0;
		$qs = dbQuery("SELECT id FROM tbl_customers WHERE code = '".$code."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);	
		}
		return $sc;
	}
	

	
}//--- end class


?>