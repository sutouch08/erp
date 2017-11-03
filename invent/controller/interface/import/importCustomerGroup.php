<?php

	$result 	= 'success';
	$path		= getConfig('IMPORT_CUSTOMER_GROUP_PATH');
	$move		= getConfig('MOVE_CUSTOMER_GROUP_PATH');

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

			$group	= new customer_group();
			$i 	= 1;
			foreach ( $collection as $rs )
			{
				if( $i != 1 ) //---- Skip first row
				{
					$id = trim( $rs['A'] );
					$code = trim( $rs['B'] );
					//echo $rs['C'];

					if( $group->isExists( $id ) === FALSE )
					{
						//-- If not exists do insert
						$arr = array(
								'id'			=> $id,
								'code'		=> $code,
								'name'		=> trim( $rs['C'] )
								);
						$group->add($arr);
					}
					else
					{
						//--- If exists do update
						$arr = array(
								'code'		=> $code,
								'name' 	=> trim($rs['C'])
								);
						$group->update( $id, $arr);
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
