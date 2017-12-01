<?php
/*
//-------- Import form excel file ------------//
// -- Colum--//------ Meaning -----//
//		A					CODE
//		B					Name
//		C					Name2
//		D					FTLASTUPD
//		E					FTDATETIME
*/
	$sc = TRUE;
	$import = 0;
	$update = 0;
	$error  = 0;
	$path		= getConfig('IMPORT_UNIT_PATH');
	$move	  = getConfig('MOVE_UNIT_PATH');

	$dr = opendir($path);

	if( $dr !== FALSE )
	{
		while( $file = readdir($dr) )
		{
			if( $file == '.' OR $file == '..' )
			{
				continue;
			}

			$fileName 	= $path . $file;
			$moveName	= $move . $file;
			$reader		= new PHPExcel_Reader_Excel5();
			$excel		= $reader->load($fileName);
			$collection	= $excel->getActiveSheet()->toArray(NULL, TRUE, TRUE, TRUE);


			$cs	= new unit();
			$i = 1;
			foreach( $collection as $rs )
			{
				if( $i > 1 ) //--- skip first row
				{
					$id = trim( $rs['A'] );

					if( $cs->isExists($id) === FALSE )
					{
						//---- If not exists do insert
						$arr = array(
										"id"			=> $id,
										"code"	=> $rs['B'],
										"name"	=> $rs['C']
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
									"code"	=> $rs['B'],
									"name"	=> $rs['C']
									);

						$update++;
						if($cs->update($id, $arr) === FALSE)
						{
							$sc = FALSE;
							$message = 'ปรับปรุงข้อมูลไม่สำเร็จ';
							$error++;
						}
					}
				}

				$i++;
			}//-- end foreach
			rename($fileName, $moveName); //---- move each file to another folder
		}//-- end while readdir

	}//- end if
	else
	{
		$sc = FALSE;
		$message = "Can not open folder please check connection";
	}

	$result = $sc === TRUE ? 'SUCCESS' : 'ERROR';

	writeImportLogs('หน่วยนับ', $result, $import, $update, $error);

	echo $sc === TRUE ? 'success' : $message;

?>
