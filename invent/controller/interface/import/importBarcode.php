<?php

	$result 	= 'success';
	$path		= getConfig('IMPORT_BARCODE_PATH');
	$move		= getConfig('MOVE_BARCODE_PATH');
	
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
			
			$barcode 	= new barcode();
			$i 	= 1;
			foreach ( $collection as $rs )
			{
				if( $i != 1 ) //---- Skip first row
				{
					$code = trim( $rs['A'] );
					if( $barcode->isExists($code) === FALSE )
					{
						//-- If not exists do insert
						$arr = array(
								'barcode'	=> $code,
								'reference'	=> trim( $rs['B'] ),
								'unit_code'	=> $rs['C'],
								'unit_qty'		=> $rs['D']
								);
						$barcode->add($arr);	
					}
					else
					{
						//--- If exists do update
						$arr = array(
								'reference'	=> trim( $rs['B'] ),
								'unit_code'	=> $rs['C'],
								'unit_qty'		=> $rs['D']
								);
						$barcode->update( $code, $arr);
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