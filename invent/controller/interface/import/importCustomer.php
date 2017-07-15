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
			
			$i 	= 1;
			foreach ( $collection as $rs )
			{
				if( $i != 1 ) //---- Skip first row
				{
					$cid 	= trim( $rs['A'] );
					$active = $rs['S'] == '' ? 1 : 0;
					if( $customer->isExists( $cid ) === FALSE )
					{
						//-- If not exists do insert
						$arr = array(
								'cid'				=> $cid,
								'code'				=> trim( $rs['B'] ),
								'pre_name'		=> trim( $rs['C'] ),
								'name'				=> trim( $rs['D'] ),
								'tel'				=> $rs['P'],
								'fax'				=> $rs['Q'],
								'mobile'			=> $rs['V'],
								'm_id'				=> $rs['W'],
								'tax_id'			=> $rs['X'],
								'contact'			=> $rs['AA'],
								'email'				=> $rs['AO'],
								'group_id'		=> $cg->getGroupId( trim( $rs['AP'] ) ),
								'area_id'			=> $ca->getAreaId( trim( $rs['AS'] ) ),
								'credit'			=> $rs['AU'],
								'term'				=> $rs['AX'],
								'address_no'	=> $rs['BK'],
								'room_no'		=> $rs['BM'],
								'floor_no'			=> $rs['BL'],
								'building'			=> $rs['BN'],
								'village_no'		=> $rs['BO'],
								'soi'				=> $rs['BP'],
								'road'				=> $rs['BQ'],
								'tambon'			=> $rs['BR'],
								'amphur'			=> $rs['BS'],
								'province'		=> $rs['BT'],
								'zip'				=> $rs['O'],
								'active'			=> $active							
								);
						$customer->add($arr);	
					}
					else
					{
						//--- If exists do update
						$arr = array(
								'code'				=> trim( $rs['B'] ),
								'pre_name'		=> trim( $rs['C'] ),
								'name'				=> trim( $rs['D'] ),
								'tel'				=> $rs['P'],
								'fax'				=> $rs['Q'],
								'mobile'			=> $rs['V'],
								'm_id'				=> $rs['W'],
								'tax_id'			=> $rs['X'],
								'contact'			=> $rs['AA'],
								'email'				=> $rs['AO'],
								'group_id'		=> $cg->getGroupId( trim( $rs['AP'] ) ),
								'area_id'			=> $ca->getAreaId( trim( $rs['AS'] ) ),
								'credit'			=> $rs['AU'],
								'term'				=> $rs['AX'],
								'address_no'	=> $rs['BK'],
								'room_no'		=> $rs['BM'],
								'floor_no'			=> $rs['BL'],
								'building'			=> $rs['BN'],
								'village_no'		=> $rs['BO'],
								'soi'				=> $rs['BP'],
								'road'				=> $rs['BQ'],
								'tambon'			=> $rs['BR'],
								'amphur'			=> $rs['BS'],
								'province'		=> $rs['BT'],
								'zip'				=> $rs['O'],
								'active'			=> $active							
								);
						$customer->update( $cid, $arr);
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