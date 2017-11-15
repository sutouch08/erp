<?php

	class zone
	{
		public $id;
		public $barcode;
		public $name;
		public $id_warehouse;
		public $id_customer;

		public function __construct($id = '')
		{
			if( $id != '' )
			{
				$qs = dbQuery("SELECT * FROM tbl_zone WHERE id_zone = '".$id."'");
				if( dbNumRows($qs) == 1 )
				{
					$rs = dbFetchObject($qs);
					$this->id		= 	$rs->id_zone;
					$this->barcode	= $rs->barcode_zone;
					$this->name	= $rs->zone_name;
					$this->id_warehouse = $rs->id_warehouse;
					$this->id_customer = $rs->id_customer;
				}
			}
		}



		public function add(array $ds)
		{
			$fields 	= '';
			$values 	= '';
			$n 		= count($ds);
			$i 			= 1;
			foreach( $ds as $key => $val )
			{
				$fields .=	 $key;
				if( $i < $n ){ $fields .= ', '; }
				$values .= "'".$val."'";
				if( $i < $n ){ $values .= ', '; }
				$i++;
			}
			$qs = dbQuery("INSERT INTO tbl_zone (".$fields.") VALUES (".$values.")");
			if( $qs )
			{
				return dbInsertId();
			}
			else
			{
				return FALSE;
			}
		}


		public function update($id, array $ds)
		{
			$set = '';
			$n = count($ds);
			$i = 1;
			foreach( $ds as $key => $val )
			{
				$set .= $key." = '".$val."'";
				if( $i < $n ){ $set .= ", "; }
				$i++;
			}
			return dbQuery("UPDATE tbl_zone SET ".$set." WHERE id_zone = ".$id);
		}


		public function deleteZone($id)
		{
			if( $this->isZoneEmpty($id) === TRUE && $this->isExistsTransection($id) === FALSE )
			{
				return $this->actionDelete($id);
			}
			else
			{
				return FALSE;
			}
		}


		public function isZoneEmpty($id)
		{
			$sc = TRUE;
			$qs = dbQuery("SELECT id_stock FROM tbl_stock WHERE id_zone = ".$id);
			if( dbNumRows($qs) > 0 )
			{
				$sc = FALSE;
			}
			return $sc;
		}


		public function isExistsTransection($id)
		{
			$sc = FALSE;
			list( $movement ) 		= dbFetchArray( dbQuery("SELECT COUNT(*) FROM tbl_stock_movement WHERE id_zone = ".$id) );
			list( $consignment ) 	= dbFetchArray( dbQuery("SELECT COUNT(*) FROM tbl_order_consignment WHERE id_zone = ".$id) );
			list( $consign )			= dbFetchArray( dbQuery("SELECT COUNT(*) FROM tbl_order_consign WHERE id_zone = ".$id) );
			list( $received )			= dbFetchArray( dbQuery("SELECT COUNT(*) FROM tbl_receive_product_detail WHERE id_zone = ".$id) );
			list( $transform )		= dbFetchArray( dbQuery("SELECT COUNT(*) FROM tbl_receive_tranform_detail WHERE id_zone = ".$id) );
			list( $return )			= dbFetchArray( dbQuery("SELECT COUNT(*) FROM tbl_return_order_detail WHERE id_zone = ".$id) );
			list( $return_spon )		= dbFetchArray( dbQuery("SELECT COUNT(*) FROM tbl_return_sponsor_detail WHERE id_zone = ".$id) );
			list( $return_supp )		= dbFetchArray( dbQuery("SELECT COUNT(*) FROM tbl_return_support_detail WHERE id_zone = ".$id) );
			list( $transfer )			= dbFetchArray( dbQuery("SELECT COUNT(*) FROM tbl_tranfer_detail WHERE id_zone_from = ".$id." OR id_zone_to = ".$id) );
			$all = $movement + $consignment + $consign + $received + $transform + $return + $return_spon + $return_supp + $transfer;
			if( $all > 0 )
			{
				$sc = TRUE;
			}
			return $sc;
		}



		private function actionDelete($id)
		{
			return dbQuery("DELETE FROM tbl_zone WHERE id_zone = ".$id);
		}




		public function getWarehouseId($id)
		{
			$sc = "";
			$qs = dbQuery("SELECT id_warehouse FROM tbl_zone WHERE id_zone = ".$id);
			if( dbNumRows($qs) == 1 )
			{
				list( $sc ) = dbFetchArray($qs);
			}
			return $sc;
		}




		public function getId($barcode)
		{
			$sc = FALSE;
			$qs = dbQuery("SELECT id_zone FROM tbl_zone WHERE barcode_zone ='".$barcode."'");
			if( dbNumRows($qs) == 1 )
			{
				list( $sc ) = dbFetchArray($qs);
			}
			return $sc;
		}



		public function getName($id)
		{
			$sc = "";
			$qs = dbQuery("SELECT zone_name FROM tbl_zone WHERE id_zone = ".$id);
			if( dbNumRows($qs) == 1 )
			{
				list( $sc ) = dbFetchArray($qs);
			}
			return $sc;
		}





		public function isAllowUnderZero($id_zone)
		{
			$sc = FALSE;
			$qs = dbQuery("SELECT id_zone FROM tbl_zone AS z JOIN tbl_warehouse AS w ON z.id_warehouse = w.id WHERE z.id_zone = ".$id_zone." AND w.allow_under_zero = 1");
			if( dbNumRows($qs) > 0 )
			{
				$sc = TRUE;
			}
			return $sc;
		}




		public function isExistsZoneCode($code, $id = '')
		{
			$sc = FALSE;
			if( $id != '' )
			{
				$qs = dbQuery("SELECT id_zone FROM tbl_zone WHERE barcode_zone = '".$code."' AND id_zone != ".$id);
			}
			else
			{
				$qs = dbQuery("SELECT id_zone FROM tbl_zone WHERE barcode_zone = '".$code."'");
			}
			if( dbNumRows($qs) > 0 )
			{
				$sc = TRUE;
			}

			return $sc;
		}





		public function isExistsZoneName($name, $id = '')
		{
			$sc = FALSE;
			if( $id != '' )
			{
				$qs = dbQuery("SELECT id_zone FROM tbl_zone WHERE zone_name = '".$name."' AND id_zone != ".$id);
			}
			else
			{
				$qs = dbQuery("SELECT id_zone FROM tbl_zone WHERE zone_name = '".$name."'");
			}
			if( dbNumRows($qs) > 0 )
			{
				$sc = TRUE;
			}

			return $sc;
		}




		public function getZoneDetail($id)
		{
			$sc = FALSE;
			$qs = dbQuery("SELECT * FROM tbl_zone WHERE id_zone = ".$id);
			if( dbNumRows($qs) == 1 )
			{
				$sc = dbFetchObject($qs);
			}

			return $sc;
		}



		public function getZoneDetailByBarcode($barcode, $id_warehouse = '')
		{
			$sc = FALSE;



			if( $id_warehouse != '')
			{
				$qr  = "SELECT * FROM tbl_zone AS z ";
				$qr .= "JOIN tbl_warehouse AS w ON z.id_warehouse = w.id ";
				$qr .= "WHERE barcode_zone = '".$barcode."' AND z.id_warehouse = '".$id_warehouse."' ";
				$qr .= "AND w.active = 1 ";

				$qs = dbQuery($qr);
			}
			else
			{
				$qr  = "SELECT * FROM tbl_zone AS z ";
				$qr .= "JOIN tbl_warehouse AS w ON z.id_warehouse = w.id ";
				$qr .= "WHERE barcode_zone = '".$barcode."' ";
				$qr .= "AND w.active = 1 ";

				$qs = dbQuery($qr);
			}

			if( dbNumRows($qs) == 1)
			{
				$sc = dbFetchObject($qs);
			}

			return $sc;
		}




		public function searchId($txt)
		{
			//---	role = 2 คือ คลังฝากขาย
			$qr  = "SELECT id_zone FROM tbl_zone WHERE zone_name LIKE '%".$txt."%'";

			return dbQuery($qr);
		}



		public function search($txt, $role = '')
		{
			$qr  = "SELECT z.* FROM tbl_zone AS z ";
			$qr .= "JOIN tbl_warehouse AS w ON z.id_warehouse = w.id ";
			$qr .= "WHERE ";
			$qr .= $role == '' ? '' : "w.role IN(".$role.") AND ";
			$qr .= "w.active = 1 AND zone_name LIKE '%".$txt."%' ";
			$qr .= "ORDER BY zone_name ASC";

			return dbQuery($qr);
		}



		//---	 ค้นหาโซนเฉพาะในคลังนี้เท่านั้น
		public function searchWarehouseZone($txt, $id_warehouse)
		{
			$qr  = "SELECT * FROM tbl_zone AS z ";
			$qr .= "JOIN tbl_warehouse AS w ON z.id_warehouse = w.id ";
			$qr .= "WHERE w.id = '".$id_warehouse."' ";
			$qr .= "AND w.active = 1 ";
			if( $txt != '*')
			{
				$qr .= "AND z.zone_name LIKE '%".$txt."%'";
			}
			
			return dbQuery($qr);
		}





		//---	auto complete โซนรับสินค้า
		public function searchReceiveZone($txt)
		{
			$role = getConfig('RECEIVE_WAREHOUSE');
			$role = $role == '' ? 5 : $role;
			//---	search role 1 คือคลังซื้อขาย, 5 คือ คลังรับสินค้า
			$qr  = "SELECT z.* FROM tbl_zone AS z ";
			$qr .= "JOIN tbl_warehouse AS w ON z.id_warehouse = w.id ";
			$qr .= "WHERE w.role IN(".$role.") AND w.active = 1 AND zone_name LIKE '%".$txt."%' ";

			return dbQuery($qr);
		}


		public function searchConsignZoneId($txt)
		{
			//---	role = 2 คือ คลังฝากขาย
			$qr  = "SELECT id_zone FROM tbl_zone AS z ";
			$qr .= "JOIN tbl_warehouse AS w ON z.id_warehouse = w.id ";
			$qr .= "WHERE w.role = 2 AND w.active = 1 AND zone_name LIKE '%".$txt."%' ";

			return dbQuery($qr);
		}


		//---	auto complete โซนในคลังฝากขาย
		public function searchConsignZone($txt, $id_customer)
		{
			//---	role = 2 คือ คลังฝากขาย
			$qr  = "SELECT z.* FROM tbl_zone AS z ";
			$qr .= "JOIN tbl_warehouse AS w ON z.id_warehouse = w.id ";
			$qr .= "WHERE w.role = 2 ";
			$qr .= "AND w.active = 1 ";
			$qr .= "AND z.id_customer = '".$id_customer."' ";

			if( $txt != '*')
			{
				$qr .= "AND z.zone_name LIKE '%".$txt."%' ";
			}

			return dbQuery($qr);
		}


		//---	auto complete โซนในคลังระหว่าทำ
		public function searchWIPZone($txt)
		{
			$qr  = "SELECT z.* FROM tbl_zone AS z ";
			$qr .= "JOIN tbl_warehouse AS w ON z.id_warehouse = w.id ";
			$qr .= "WHERE w.role = 7 ";
			$qr .= "AND w.active = 1 ";
			if( $txt != '*')
			{
				$qr .= "AND z.zone_name LIKE '%".$txt."%'";
			}

			return dbQuery($qr);
		}



		//---	auto complete โซนในคลังยืมสินค้า
		public function searchLendZone($txt)
		{
			$qr  = "SELECT z.* FROM tbl_zone AS z ";
			$qr .= "JOIN tbl_warehouse AS w ON z.id_warehouse = w.id ";
			$qr .= "WHERE w.role = 8 ";
			$qr .= "AND w.active = 1 ";
			if( $txt != '*')
			{
				$qr .= "AND z.zone_name LIKE '%".$txt."%'";
			}

			return dbQuery($qr);
		}


	} 	//----- End class


?>
