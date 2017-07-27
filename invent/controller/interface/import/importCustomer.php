<?php

	$result 	= 'success';
	$path		= getConfig('IMPORT_CUSTOMER_PATH');
	$move		= getConfig('MOVE_CUSTOMER_PATH');
	
	$sc	= opendir($path);
	if( $sc !== FALSE )
	{
		while( $file = readdir($sc) )
		{
			if( $file == '.' OR $file == '..' )
			{
				continue;
			}
			$fileName	= $path . $file;
			$moveName	= $move . $file;
			$reader		= new PHPExcel_Reader_Excel5();
			$excel		= $reader->load($fileName);
			$collection	= $excel->getActiveSheet()->toArray(NULL, TRUE, TRUE, TRUE);
			
			$customer	= new customer();
			$cg			= new customer_group();
			$ca			= new customer_area();
			$sale			= new sale();
			
			$i 	= 1;
			foreach ( $collection as $rs )
			{
				if( $i != 1 ) //---- Skip first row
				{
					$id 		= trim( $rs['A'] );
					$active 	= $rs['Q'] == '' ? 1 : 0;
					if( $customer->isExists( $id ) === FALSE )
					{
						//-- If not exists do insert
						$arr = array(
								'id'					=> $id,
								'code'				=> trim( $rs['B'] ),
								'name'				=> trim( $rs['C'] ),
								'address1'		=> trim( $rs['E'] ),
								'address2'		=> trim( $rs['F'] ),
								'address3'		=> trim( $rs['G'] ),
								'tel'				=> $rs['N'],
								'fax'				=> $rs['O'],
								'mobile'			=> $rs['T'],
								'm_id'				=> $rs['U'],
								'tax_id'			=> $rs['V'],
								'contact'			=> $rs['AW'],
								'email'				=> $rs['AL'],
								'id_group'		=> $cg->getGroupId( trim( $rs['AM'] ) ),
								'id_area'			=> $ca->getAreaId( trim( $rs['AP'] ) ),
								'id_sale'			=> $sale->getSaleId( trim( $rs['CF'] ) ),
								'credit'			=> $rs['AR'],
								'term'				=> $rs['AU'],
								'address_no'	=> $rs['BQ'],
								'room_no'		=> $rs['BS'],
								'floor_no'			=> $rs['BR'],
								'building'			=> $rs['BT'],
								'village_no'		=> $rs['BU'],
								'soi'				=> $rs['BV'],
								'road'				=> $rs['BW'],
								'tambon'			=> $rs['BX'],
								'amphur'			=> $rs['BY'],
								'province'		=> $rs['BZ'],
								'zip'				=> $rs['M'],
								'active'			=> $active							
								);
						$customer->add($arr);	
					}
					else
					{
						//--- If exists do update
						$arr = array(
								'code'				=> trim( $rs['B'] ),
								'name'				=> trim( $rs['C'] ),
								'address1'		=> trim( $rs['E'] ),
								'address2'		=> trim( $rs['F'] ),
								'address3'		=> trim( $rs['G'] ),
								'tel'				=> $rs['N'],
								'fax'				=> $rs['O'],
								'mobile'			=> $rs['T'],
								'm_id'				=> $rs['U'],
								'tax_id'			=> $rs['V'],
								'contact'			=> $rs['AW'],
								'email'				=> $rs['AL'],
								'id_group'		=> $cg->getGroupId( trim( $rs['AM'] ) ),
								'id_area'			=> $ca->getAreaId( trim( $rs['AP'] ) ),
								'id_sale'			=> $sale->getSaleId( trim( $rs['CF'] ) ),
								'credit'			=> $rs['AR'],
								'term'				=> $rs['AU'],
								'address_no'	=> $rs['BQ'],
								'room_no'		=> $rs['BS'],
								'floor_no'			=> $rs['BR'],
								'building'			=> $rs['BT'],
								'village_no'		=> $rs['BU'],
								'soi'				=> $rs['BV'],
								'road'				=> $rs['BW'],
								'tambon'			=> $rs['BX'],
								'amphur'			=> $rs['BY'],
								'province'		=> $rs['BZ'],
								'zip'				=> $rs['M'],
								'active'			=> $active							
								);
						$customer->update( $id, $arr);
					}	/// end if
				}//-- end if not first row
				$i++;	
			}//---- end foreach
			rename($fileName, $moveName); //---- move each file to another folder	
		}//--- end while
	} //--- end if
	else
	{
		$result = 'Can not open folder';	
	}
	
	echo $result;

?>