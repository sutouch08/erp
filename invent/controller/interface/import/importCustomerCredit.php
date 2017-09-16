<?php

	$result 	= 'success';
	$path		= getConfig('IMPORT_CUSTOMER_CREDIT_PATH');
	$move		= getConfig('MOVE_CUSTOMER_CREDIT_PATH');
	
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
			
			$cs	= new customer_credit();
			$customer = new customer();
			$i 	= 1;
			foreach ( $collection as $rs )
			{
				if( $i != 1 ) //---- Skip first row
				{
					
					$code = trim( $rs['C'] );
					if( $cs->isExists( $code ) === FALSE )
					{
						//-- If not exists do insert
						$arr = array(
								'id_customer'	=> $customer->getId($code),
								'code'		=> $code,
								'name'		=> trim( $rs['D'] ),
								'credit'	=> trim( $rs['E'] ),
								'used'		=> trim( $rs['F'] ),
								'balance'	=> trim( $rs['G'] )
								);
						$cs->add($arr);	
					}
					else
					{
						//--- If exists do update
						$id = $cs->getid($code);
						$arr = array(
								'code'		=> $code,
								'name'		=> trim( $rs['D'] ),
								'credit'	=> trim( $rs['E'] ),
								'used'		=> trim( $rs['F'] ),
								'balance'	=> trim( $rs['G'] )
								);
						$cs->update( $id, $arr);
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