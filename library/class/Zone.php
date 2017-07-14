<?php

	class zone
	{
		public $id;
		public $barcode;
		public $name;
		public $id_warehouse;
				
		public function __construct($id='')
		{
			if( is_numeric($id) )
			{
				$qs = dbQuery("SELECT * FROM tbl_zone WHERE id_zone = ".$id);
				if( dbNumRows($qs) == 1 )
				{
					$rs = dbFetchObject($qs);
					$this->id		= 	$rs->id_zone;
					$this->barcode	= $rs->barcode_zone;
					$this->name	= $rs->zone_name;
					$this->id_warehouse = $rs->id_warehouse;
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
		
			
		
	} 	//----- End class


?>