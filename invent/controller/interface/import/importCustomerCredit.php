<?php

	$sc = TRUE;
	$import = 0;
	$update = 0;
	$error  = 0;

	$path		= getConfig('IMPORT_CUSTOMER_CREDIT_PATH');
	$move		= getConfig('MOVE_CUSTOMER_CREDIT_PATH');

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

			$cs	= new customer_credit();
			$customer = new customer();
			$i 	= 1;
			foreach ( $collection as $rs )
			{
				if( $i != 1 ) //---- Skip first row
				{

					$code = addslashes( $rs['C'] );
					if( $cs->isExists( $code ) === FALSE )
					{
						//-- If not exists do insert
						$arr = array(
								'id_customer'	=> $customer->getId($code),
								'code'		=> $code,
								'name'		=> addslashes( $rs['D'] ),
								'credit'	=> addslashes( $rs['E'] ),
								'used'		=> addslashes( $rs['F'] ),
								'balance'	=> addslashes( $rs['G'] )
								);

						$import++;
						if($cs->add($arr) === FALSE)
						{
							$sc = FALSE;
							$message = 'เพิ่มข้อมูลไม่สำเร็จ';
							$error++;
							writeErrorLogs('Credit', $cs->error);
						}
					}
					else
					{
						//--- If exists do update
						$id = $cs->getid($code);
						$arr = array(
								'code'		=> $code,
								'name'		=> addslashes( $rs['D'] ),
								'credit'	=> addslashes( $rs['E'] ),
								'used'		=> addslashes( $rs['F'] ),
								'balance'	=> addslashes( $rs['G'] )
								);
						$update++;
						if($cs->update($id, $arr) === FALSE)
						{
							$sc = FALSE;
							$message = 'ปรับปรุงข้อมูลไม่สำเร็จ';
							$error++;
							writeErrorLogs('Credit', $cs->error);
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


	writeImportLogs('เครดิตคงเหลือ', $import, $update, $error);

	echo $sc === TRUE ? 'success' : $message;

?>
