<?php
	
	$result 	= 'success';
	$path		= getConfig('IMPORT_PRODUCT_GROUP_PATH');
	$move		= getConfig('MOVE_PRODUCT_GROUP_PATH');
	
	$sc 	= opendir($path);
	if( $sc )
	{
		while( $file = readdir($sc) )
		{
			if( $file == '.' OR $file == '..' )
			{
				continue;
			}//--- end if	
			
			$fileName 	= $path . $file;
			$moveName	= $move . $file;
			$reader		= new PHPExcel_Reader_Excel5();
			$excel		= $reader->load($fileName);
			$collection	= $excel->getActiveSheet()->toArray(NULL, TRUE, TRUE, TRUE);
			$i = 1;
			
			$pg = new product_group();
			foreach( $collection as $rs )
			{
				if( $i > 1 )  //--- Skip first row
				{
					$code = trim( $rs['A'] );
					
					if( $pg->isExists($code) === FALSE )
					{
						//---- If not exists do insert
						$ds = array(
									"code"	=> $code,
									"name"	=> $rs['B']									
									);
						$pg->add($ds);
					}
					else
					{
						//--- if exists do update
						$ds = array( 
									"name"	=> $rs['B']									
									);
						$pg->update($code, $ds);
						
					}//-- end if;
				}//--- end if;
				$i++;
			}//-- end foreach
			
			rename($fileName, $moveName); //-- move each file to another folder
		} //-- end while
		closedir($sc);
	}//-- end if
	else
	{
		$result = "Can not open folder please check connection";	
	}
	
	echo $result;

?>