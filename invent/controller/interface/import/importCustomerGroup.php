<?php

	$sc = TRUE;
	$import = 0;
	$update = 0;
	$error  = 0;

	$path		= getConfig('IMPORT_CUSTOMER_GROUP_PATH');
	$move		= getConfig('MOVE_CUSTOMER_GROUP_PATH');

	$dr	= opendir($path);
	if( $dr !== FALSE )
	{
		while( $file = readdir($dr) )
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

			$cs	= new customer_group();
			$i 	= 1;
			foreach ( $collection as $rs )
			{
				if( $i != 1 ) //---- Skip first row
				{
					$id = trim( $rs['A'] );
					$code = trim( $rs['B'] );
					//echo $rs['C'];

					if( $cs->isExists( $id ) === FALSE )
					{
						//-- If not exists do insert
						$arr = array(
								'id'			=> $id,
								'code'		=> $code,
								'name'		=> trim( $rs['C'] )
								);

						$import++;
						if($cs->add($arr) === FALSE)
						{
							$sc = FALSE;
							$message = 'เพิ่มข้อมูลไม่สำเร็จ';
							$error++;
						}
					}
					else
					{
						//--- If exists do update
						$arr = array(
								'code'		=> $code,
								'name' 	=> trim($rs['C'])
								);
						$update++;
						if($cs->update($id, $arr) === FALSE)
						{
							$sc = FALSE;
							$message = 'ปรับปรุงข้อมูลไม่สำเร็จ';
							$error++;
						}
						
					}	/// end if

				}//-- end if not first row
				$i++;
			}//---- end foreach
			rename($fileName, $moveName); //---- move each file to another folder
		}//--- end while
	} //--- end if
	else
	{
		$sc = FALSE;
		$message = "Can not open folder please check connection";
	}

	$result = $sc === TRUE ? 'SUCCESS' : 'ERROR';

	writeImportLogs('กลุ่มลูกค้า', $result, $import, $update, $error);

	echo $sc === TRUE ? 'success' : $message;

?>
