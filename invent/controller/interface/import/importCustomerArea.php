<?php

	$result 	= 'success';
	$path		= getConfig('IMPORT_CUSTOMER_AREA_PATH');
	$move		= getConfig('MOVE_CUSTOMER_AREA_PATH');
	
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
			
			$area	= new customer_area();
			$i 	= 1;
			foreach ( $collection as $rs )
			{
				if( $i != 1 ) //---- Skip first row
				{
					if( $area->isExists( trim( $rs['A'] )) === FALSE )
					{
						//-- If not exists do insert
						$arr = array(
								'code'		=> trim( $rs['A'] ),
								'name'		=> trim( $rs['B'] )
								);
						$area->add($arr);	
					}
					else
					{
						//--- If exists do update
						$arr = array(
								'name' 	=> trim( $rs['B'] )
								);
						$area->update( trim( $rs['A'] ), $arr);
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