<?php

	$sc = TRUE;
	$import = 0;
	$update = 0;
	$error  = 0;
	$path		= getConfig('IMPORT_PO_PATH');
	$move	= getConfig('MOVE_PO_PATH');

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
			$es 			= new PHPExcel();
			$reader		= new PHPExcel_Reader_Excel5();

			$excel		= $reader->load($fileName);
			$collection	= $excel->getActiveSheet()->toArray(NULL, TRUE, TRUE, TRUE);

			$cs 	= new po();
			$sp	= new supplier();
			$wh	= new warehouse();
			$pd	= new product();
			$i 	= 1;
			foreach ( $collection as $rs )
			{
				if( $i != 1 )
				{
					$bookcode 	= trim( $rs['G'] );
					$reference	= trim( $rs['I'] );
					$product		= trim( $rs['Z'] );
					$id_pd		= $pd->getId($product);
					$id_style		= $pd->getStyleId($id_pd);
					$isCancle	= $rs['F'] == "C" ? 1 : 0;
					if( $cs->isExists($bookcode, $reference, $id_pd) === FALSE )
					{
						$arr = array(
											"bookcode"			=> $bookcode,
											"code"					=> trim( $rs['H'] ),
											"reference"			=> $reference,
											"id_supplier"		=> $sp->getId( $rs['M'] ),
											"id_warehouse"	=> $wh->getId( $rs['AA'] ),
											"credit_term"		=> $rs['N'],
											"vat_type"			=> $rs['P'],
											"vat_is_out"		=> $rs['O'],
											"vat_amount"		=> $rs['T'],
											"amount_ex"			=> $rs['S'],
											"bill_discount"	=> $rs['R'],
											"date_add"			=> dbDate( $rs['J'] ),
											"date_need"			=> dbDate( $rs['K'] ),
											"due_date"			=> dbDate( $rs['L'] ),
											"id_style"			=> $id_style,
											"id_product"		=> $id_pd,
											"price"					=> $rs['AF'],
											"discount"			=> $rs['AG'],
											"qty"						=> $rs['AC'],
											"unit_code"			=> $rs['AD'],
											"unit_qty"			=> $rs['AE'],
											"isCancle"			=> $isCancle
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
						$arr = array(
											"id_supplier"		=> $sp->getId( $rs['M'] ),
											"id_warehouse"	=> $wh->getId( $rs['AA'] ),
											"credit_term"		=> $rs['N'],
											"vat_type"			=> $rs['P'],
											"vat_is_out"		=> $rs['O'],
											"vat_amount"		=> $rs['T'],
											"amount_ex"			=> $rs['S'],
											"bill_discount"	=> $rs['R'],
											"date_add"			=> dbDate( $rs['J'] ),
											"date_need"			=> dbDate( $rs['K'] ),
											"due_date"			=> dbDate( $rs['L'] ),
											"price"					=> $rs['AF'],
											"discount"			=> $rs['AG'],
											"qty"						=> $rs['AC'],
											"unit_code"			=> $rs['AD'],
											"unit_qty"			=> $rs['AE'],
											"isCancle"			=> $isCancle
										);

						$update++;
						if($cs->update($bookcode, $reference, $product, $arr) === FALSE)
						{
							$sc = FALSE;
							$message = 'ปรับปรุงข้อมูลไม่สำเร็จ';
							$error++;
						}

					}//---- end if exists
				}//--- end if first row
				$i++;
			}//------ end foreach
			rename($fileName, $moveName); //---- move each file to another folder
		}//----- end while
	}
	else
	{
		$sc = FALSE;
		$message = "Can not open folder please check connection";
	}

	$result = $sc === TRUE ? 'SUCCESS' : 'ERROR';

	writeImportLogs('ใบสั่งซื้อ', $result, $import, $update, $error);

	echo $sc === TRUE ? 'success' : $message;

?>
