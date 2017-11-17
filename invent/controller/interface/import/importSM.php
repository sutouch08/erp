<?php

	$result 	= 'success';

	//---	Path ของไฟล์ที่จะ import
	$path		= getConfig('IMPORT_SM_PATH');

	//---	Path ของไฟล์ที่จะย้ายไปเก็บเมื่อ import เสร็จแล้ว
	$move	= getConfig('MOVE_SM_PATH');

	//---	เปิด path เพื่อดูไฟล์ทั้งหมดใน directory
	$sc	= opendir($path);

	//---	ถ้าใน directory มีไฟล์อยู่
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
			$es 			= new PHPExcel();
			$reader		= new PHPExcel_Reader_Excel5();

			$excel		= $reader->load($fileName);
			$collection	= $excel->getActiveSheet()->toArray(NULL, TRUE, TRUE, TRUE);

			$cs   = new return_order();
			$cus = new customer();
			$sale	= new sale();
			$wh	= new warehouse();
			$pd	= new product();
			$i 	= 1;
			foreach ( $collection as $rs )
			{
				if( $i != 1 )
				{
					$bookcode 	= trim( $rs['G'] );

					//---	รหัสเอกสาร
					$reference	= trim( $rs['I'] );

					//---	คืนสินค้าหรือไม่
					$isReturn   = trim($rs['AO']) == 'Y' ? 1 : 0;

					//---	อ้างอิงใบส่งสินค้า/ใบกำกับภาษี
					$invoice    = trim($rs['AQ']).'-'.trim($rs['AR']);

					//---	รหัสสินค้า
					$product		= trim( $rs['AC'] );

					//---	ไอดีสินค้า
					$id_pd		= $pd->getId($product);

					//---	ไอดีรุ่นสินค้า
					$id_style		= $pd->getStyleId($id_pd);

					//---	ยกเลิกหรือไม่
					$isCancle	= $rs['F'] == "C" ? 1 : 0;

					//---	valid ถ้าไม่มีการคืนสินค้าให้ valid = 1
					$valid = $isReturn == 1 ? 0 : 1;

					$id = $cs->getId($bookcode, $reference, $product);
					if( $id === FALSE )
					{
						$arr = array(
											'bookcode'	    => $bookcode,
											'code'			    => trim( $rs['H']),
											'reference'	    => $reference,
											'invoice'		    => $invoice,
											'id_customer'   => $cus->getId(trim($rs['M'])),
											'id_sale'		    => $sale->getId(trim($rs['N'])),
											'id_warehouse'	=> $wh->getId(trim($rs['AD'])),
											'id_style'	    => $id_style,
											'id_product'	  => $id_pd,
											'product_code'	=> $product,
											'price'			    => trim($rs['AI']),
											'qty'		        => trim($rs['AF']),
											'unit_code'	    => trim($rs['AG']),
											'umqty'	        => trim($rs['AH']),
											'discount'			=> trim($rs['AJ']),
											'bill_discount'	=> trim($rs['U']),
											'amount_ex'	    => trim($rs['V']),
											'vat_amount'	  => trim($rs['W']),
											'date_add'			=> dbDate($rs['J']),
											'isCancle'			=> $isCancle,
											'valid'					=> $valid,
											'isReturn'			=> $isReturn
										);

						$cs->add($arr);
					}

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
