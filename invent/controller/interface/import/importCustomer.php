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
					$active 	= $rs['J'] == '' ? 1 : 0;
					if( $customer->isExists( $id ) === FALSE )
					{
						//-- If not exists do insert
						$arr = array(
								'id'					=> $id,
								'code'				=> trim( $rs['B'] ),
								'name'				=> trim( $rs['C'] ),
								'address1'		=> trim( $rs['D'] ),
								'address2'		=> trim( $rs['E'] ),
								'address3'		=> trim( $rs['F'] ),
								'tel'				=> $rs['H'],
								'fax'				=> $rs['I'],
								'm_id'				=> $rs['L'],
								'tax_id'			=> $rs['M'],
								'contact'			=> $rs['AF'],
								'email'				=> $rs['X'],
								'id_group'		=> $cg->getGroupId( trim( $rs['Y'] ) ),
								'id_area'			=> $ca->getAreaId( trim( $rs['AA'] ) ),
								'id_sale'			=> $sale->getSaleId( trim( $rs['AT'] ) ),
								'credit'			=> $rs['AC'],
								'term'				=> $rs['AE'],
								'address_no'	=> $rs['AG'],
								'room_no'		=> $rs['AI'],
								'floor_no'			=> $rs['AH'],
								'building'			=> $rs['AJ'],
								'village_no'		=> $rs['AK'],
								'soi'				=> $rs['AL'],
								'road'				=> $rs['AM'],
								'tambon'			=> $rs['AN'],
								'amphur'			=> $rs['AO'],
								'province'		=> $rs['AP'],
								'zip'				=> $rs['G'],
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
								'address1'		=> trim( $rs['D'] ),
								'address2'		=> trim( $rs['E'] ),
								'address3'		=> trim( $rs['F'] ),
								'tel'				=> $rs['H'],
								'fax'				=> $rs['I'],
								'm_id'				=> $rs['L'],
								'tax_id'			=> $rs['M'],
								'contact'			=> $rs['AF'],
								'email'				=> $rs['X'],
								'id_group'		=> $cg->getGroupId( trim( $rs['Y'] ) ),
								'id_area'			=> $ca->getAreaId( trim( $rs['AA'] ) ),
								'id_sale'			=> $sale->getSaleId( trim( $rs['AT'] ) ),
								'credit'			=> $rs['AC'],
								'term'				=> $rs['AE'],
								'address_no'	=> $rs['AG'],
								'room_no'		=> $rs['AI'],
								'floor_no'			=> $rs['AH'],
								'building'			=> $rs['AJ'],
								'village_no'		=> $rs['AK'],
								'soi'				=> $rs['AL'],
								'road'				=> $rs['AM'],
								'tambon'			=> $rs['AN'],
								'amphur'			=> $rs['AO'],
								'province'		=> $rs['AP'],
								'zip'				=> $rs['G'],
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