<?php

	$result 	= 'success';
	$path		= getConfig('IMPORT_PRODUCT_PATH');
	$move	= getConfig('MOVE_PRODUCT_PATH');
	
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
			
			$pd 	= new product();
			$pg 	= new product_group();
			$co 	= new color();
			$si 	= new size();
			$bd	= new brand();
			$st 	= new style();
			$un	= new unit();
			
			$i 	= 1;
			foreach ( $collection as $rs )
			{
				if( $i != 1 )
				{
					$id 		= trim( $rs['A'] );
					$isVisual	= $rs['G'] == 3 ? 1 : 0;
					$active	= $rs['F'] == 'I' ? 0 : 1;
					if( $pd->isExists($id) === FALSE )
					{
						$arr = array(
											"id"				=> $id,
											"code"		=> trim( $rs['B'] ),
											"name"		=> trim( $rs['C'] ),
											"id_style"		=> $st->getStyleId( $rs['J'] ),
											"id_color"		=> $co->getColorId( $rs['L'] ),
											"id_size"		=> $si->getSizeId( $rs['K'] ),
											"id_group"	=> $pg->getProductGroupId( $rs['E'] ),
											"id_brand"	=> $bd->getBrandId( $rs['I'] ),
											"cost"			=> $rs['D'],
											"price"		=> $rs['M'],
											"discount_amount"	=> $rs['N'],
											"id_unit"		=> $un->getUnitId( $rs['H'] ),
											"is_visual"	=> $isVisual,
											"active"		=> $active							
										);
						$pd->add($arr);	
					}
					else
					{
						$arr = array(
											"code"		=> trim( $rs['B'] ),
											"name"		=> trim( $rs['C'] ),
											"id_style"		=> $st->getStyleId( $rs['J'] ),
											"id_color"		=> $co->getColorId( $rs['L'] ),
											"id_size"		=> $si->getSizeId( $rs['K'] ),
											"id_group"	=> $pg->getProductGroupId( $rs['E'] ),
											"id_brand"	=> $bd->getBrandId( $rs['I'] ),
											"cost"			=> $rs['D'],
											"price"		=> $rs['M'],
											"discount_amount"	=> $rs['N'],
											"id_unit"		=> $un->getUnitId( $rs['H'] ),
											"is_visual"	=> $isVisual,
											"active"		=> $active							
										);
						$pd->update($id, $arr);	
					}//---- end if exists
				}//--- end if first row
				$i++;
			}//------ end foreach
			rename($fileName, $moveName); //---- move each file to another folder
		}//----- end while 		
	}
	else
	{
		$result = 'Can not open folder';	
	}
	
	echo $result;

?>