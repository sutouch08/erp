<?php

	$result 	= 'success';
	$path		= getConfig('IMPORT_SALE_GROUP_PATH');
	$move		= getConfig('MOVE_SALE_GROUP_PATH');
	
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
			
			$st	= new sale_team();
			$i 	= 1;
			foreach ( $collection as $rs )
			{
				if( $i != 1 ) //---- Skip first row
				{
					if( $st->isExists( trim( $rs['A'] )) === FALSE )
					{
						//-- If not exists do insert
						$arr = array(
								'id'			=> trim( $rs['A'] ),
								'code'		=> trim( $rs['B'] ),
								'name'		=> trim( $rs['C'] )
								);
						$st->add($arr);	
					}
					else
					{
						//--- If exists do update
						$arr = array(
								'code'		=> trim( $rs['B'] ),
								'name' 	=> trim( $rs['C'] )
								);
						$st->update( trim( $rs['A'] ), $arr);
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