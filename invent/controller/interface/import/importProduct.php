<?php

	$result 	= 'success';
	$path		= getConfig('IMPORT_PRODUCT_PATH');
	$move		= getConfig('MOVE_PRODUCT_PATH');
	
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
			
			$pd = new products();
			$pa = new items();
			$i 	= 1;
			foreach ( $collection as $rs )
			{
				if( $i != 1 )
				{
					$pdCode = $rs['AO'];
					$color	= $rs['AQ'];
					$size		= $rs['AP'];
					$price	= $rs['AR'];
					$cost		= $rs['G'];
					$pdGroup	= $rs['H'];
					$active	= $rs['I'];
					if( $pd->isExists($pdCode) === FALSE )
					{
						$arr = array(
								'product_code'	=> $pdCode,
								'product_name'	=> ''
								);
						$pd->add($arr);	
					}
					
					if( $item->isExists($rs['A'] === FALSE ) )
					{					
					
						$arr = array(
								"reference"	=> $rs['A'],
								"id_product"	=> $pd->getProductId($pdCode),
								
								);
					}
					else
					{
							
					}
				}
			}
		}		
	}
	else
	{
		$result = 'Can not open folder';	
	}
	
	echo $result;

?>