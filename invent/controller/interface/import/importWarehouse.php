<?php

	$result 	= 'success';
	$path		= getConfig('IMPORT_WAREHOUSE_PATH');
	$move		= getConfig('MOVE_WAREHOUSE_PATH');
	
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
			
			$warehouse	= new warehouse();
			$i 	= 1;
			foreach ( $collection as $rs )
			{
				if( $i != 1 ) //---- Skip first row
				{
					$id			= trim( $rs['A'] );
					$code 	= trim( $rs['B'] );
					$name 	= trim( $rs['C'] );
					$active 	= trim($rs['F'] );
					if( $warehouse->isExists($id) === FALSE )
					{
						//-- If not exists do insert
						$arr = array(
								'id'			=> $id,
								'code'		=> $code,
								'name'		=> $name,
								'active'	=> $active == '' ? 1 : 0
								);
						$warehouse->add($arr);	
					}
					else
					{
						//--- If exists do update
						$arr = array(
								'code'		=> $code,
								'name' 	=> $rs['C'],
								'active'	=> $active == '' ? 1 : 0
								);
						$warehouse->update( $id, $arr);
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