<?php
class customer {
	public $id; 				//-- id customer
	public $code;			//-- Customer code
	public $name;			//-- Customer's name
	public $address1;
	public $address2;
	public $address3;
	public $tel;				//-- Phone No.
	public $fax;				//-- Fax No.
	public $m_id;			//-- Citizen ID
	public $tax_id;
	public $contact;			//-- Contact Person Name
	public $email;
	public $id_kind;
	public $id_type;
	public $id_class;
	public $id_group;		//-- Group of customer
	public $id_area;		//-- Area of customer
	public $id_sale;		//-- Sale of Customer	
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
	public $is_deleted;
	public $emp;				//--- employee who delete or restore
	public $date_upd;		
	public function __construct( $id = '')
	{
		if( $id != '' )
		{
			$qs = dbQuery("SELECT * FROM tbl_customer WHERE id = '".$id."'");
			if( dbNumRows($qs) == 1 )
			{
				$rs = dbFetchObject($qs);
				$this->id 			= $rs->id;
				$this->code			= $rs->code;
				$this->name			= $rs->name;
				$this->address1	= $rs->address1;
				$this->address2	= $rs->address2;
				$this->address3	= $rs->address3;
				$this->tel				= $rs->tel;
				$this->fax			= $rs->fax;
				$this->m_id			= $rs->m_id;
				$this->tax_id		= $rs->tax_id;
				$this->contact		= $rs->contact;
				$this->email			= $rs->email;
				$this->id_kind		= $rs->id_kind;
				$this->id_type		= $rs->id_type;
				$this->id_class		= $rs->id_class;
				$this->id_group	= $rs->id_group;
				$this->id_area		= $rs->id_area;
				$this->id_sale		= $rs->id_sale;
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
				$this->is_deleted	= $rs->is_deleted;
				$this->emp			= $rs->emp;
				$this->date_upd	= $rs->date_upd;
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
	
	public function delete($id, $emp)
	{
		$sc = FALSE;
		if( $this->hasTransection($id) === FALSE )
		{
			$sc = dbQuery("DELETE FROM tbl_customer WHERE id = '".$id."'");	
		}
		else
		{
			$sc = dbQuery("UPDATE tbl_customer SET is_deleted = 1, emp = ".$emp." WHERE id = '". $id ."'");
		}
		return $sc;
	}
	
	
	public function unDelete($id, $emp)
	{
		return dbQuery("UPDATE tbl_customer  SET is_deleted = 0, emp = ".$emp." WHERE id = '".$id."'");	
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
	
	
	public function hasTransection($id)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id_customer FROM tbl_order WHERE id_customer = '".$id."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}
		return $sc;		
	}
	
	
	public function getName($id)
	{
		$cs = '';
		$qs = dbQuery("SELECT name FROM tbl_customer WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $cs ) = dbFetchArray($qs);	
		}
		return $cs;
	}
	
	
	public function getSale($id)
	{
		$sc = "0000";
		$qs = dbQuery("SELECT id_sale FROM tbl_customer WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}
	
	
	
	public function getProvince($id)
	{
		$sc = "";
		$qs = dbQuery("SELECT province FROM tbl_customer WHERE id = '".$id."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}
	
	
	
	
	public function searchId($txt)
	{
		return dbQuery("SELECT id FROM tbl_customer WHERE name LIKE '%".$txt."%'");
	}
	
	public function search($txt, $fields = "")
	{
		if( $fields == "" )
		{
			return dbQuery("SELECT * FROM tbl_customer WHERE code LIKE '%".$txt."%' OR name LIKE '%".$txt."%'");
		}
		else
		{
			return dbQuery("SELECT ".$fields." FROM tbl_customer WHERE code LIKE '%".$txt."%' OR name LIKE '%".$txt."%'");	
		}	
	}
	
}//--- end class


?>