<?php

	$sc = TRUE;
	$import = 0;
	$update = 0;
	$error  = 0;
	$path		= getConfig('IMPORT_BARCODE_PATH');
	$move		= getConfig('MOVE_BARCODE_PATH');

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

			$cs 	= new barcode();
			$pd			= new product();
			$i 	= 1;
			foreach ( $collection as $rs )
			{
				if( $i != 1 ) //---- Skip first row
				{
					$id = trim( $rs['A'] );
					$code = trim( $rs['B'] );
					$id_pd = $pd->getId( trim( $rs['C'] ) );
					if( $cs->isExists($id) === FALSE )
					{
						//-- If not exists do insert
						$arr = array(
								'id'				=> $id,
								'barcode'	=> $code,
								'id_product'	=> $id_pd,
								'reference'	=> trim( $rs['C'] ),
								'unit_code'	=> $rs['D'],
								'unit_qty'		=> $rs['E']
								);
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
								'barcode'	=> $code,
								'id_product'	=> $id_pd,
								'reference'	=> trim( $rs['C'] ),
								'unit_code'	=> $rs['D'],
								'unit_qty'		=> $rs['E']
								);
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

	writeImportLogs('บาร์โค้ด', $result, $import, $update, $error);

	echo $sc === TRUE ? 'success' : $message;

?>
