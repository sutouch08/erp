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
	$result = 'success';
	$path		= getConfig('IMPORT_UNIT_PATH');
	$move	= getConfig('MOVE_UNIT_PATH');
	
	$sc = opendir($path);
	
	if( $sc !== FALSE )
	{
		while( $file = readdir($sc) )
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
			
			
			$unit	= new unit();
			$i = 1;
			foreach( $collection as $rs )
			{
				if( $i > 1 ) //--- skip first row
				{
					$code = trim( $rs['A'] );
					$date	 = PHPExcel_Shared_Date::ExcelToPHPObject($rs['E']);
					
					if( $unit->isExists($code) === FALSE )
					{
						//---- If not exists do insert
						$ds = array(
										"code"	=> $code,
										"name"	=> $rs['B'],
										"date_upd"	=> $date->format('Y-m-d')
										);
						$unit->add($ds);	
					}
					else
					{
						//--- If exists do update
						$ds = array(
									"name"	=> $rs['B'],
									"date_upd"	=> $date->format('Y-m-d')
									);
						$unit->update($code, $ds);
					}	
				}
				
				$i++;
			}//-- end foreach	
			rename($fileName, $moveName); //---- move each file to another folder		
		}//-- end while readdir
		
	}//- end if
	else
	{
		$result = "Can not open folder please check connection";	
	}
	
	echo $result;

?>