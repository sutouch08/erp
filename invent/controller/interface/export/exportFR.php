<?php
	//---	$id = id_receive_transform
	function exportFR($id)
	{

		$sc 	= FALSE;
		$cs 	= new receive_transform($id);
		$tr   = new transform($cs->id_order);
		$zone = new zone();

		$wh		= new warehouse();
		$emp	= new employee();
		$excel = new PHPExcel();

		//---	Export path
		$path		  = getConfig('EXPORT_FR_PATH');

		//---	เว้นว่างไว้
		$ERRMSG		= "";

		//--	ประเภทเอกสาร  FR = รับสินค้าจากการผลิต
		$REFTYPE	= 'FR';

		//-- รหัสบริษัท
		$QCCORP		= getConfig('COMPANY_CODE');

		//-- รหัสสาขา
		$QCBRANCH	= getConfig('BRANCH_CODE');

		//-- รหัสแผนก
		$QCSECT		= $emp->getDivisionCode($cs->id_employee);

		//--  รหัสโครงการ (ถ้ามี)
		$QCJOB		= "";

		//-- 	สถานะของเอกสาร ปกติว่างไว้,  C = CANCEL
		$STAT		  = "";

		//--	รหัสเล่มเอกสาร
		$QCBOOK 	= $cs->bookcode;

		//--	เลขที่เอกสาร ถ้าว่างไว้ระบบจะ gen ให้เอง
		$CODE			= "";

		//--	เลขที่อ้างอิง (เลขที่เอกสารใน Smart Invent)
		$REFNO	 	= $cs->reference;

		//--	วันที่ของเอกสาร
		$DATE			= thaiDate($cs->date_add, '/');

		//---	รหัสคลังต้นทาง (ประเภทคลังระหว่างทำ)
		$QCFRWHOUSE = $wh->getCode( $zone->getWarehouseId($tr->id_zone) );

		//--- LOT สินค้า
		$LOT 		= "";

		//--- หมายเหตุที่หัวเอกสาร
		$REMARKH1	= $cs->remark;

		//---	หมายเหตุที่รายการสินค้า
		$REMARKI1	= "";



		//---------- SET File meta data
		$excel->getProperties()->setCreator("Samart Invent 2.0");
		$excel->getProperties()->setLastModifiedBy("Samart Invent 2.0");
		$excel->getProperties()->setTitle("FR");
		$excel->getProperties()->setSubject("FR");
		$excel->getProperties()->setDescription("This file was generate by Smart invent web application via PHPExcel v.1.8");
		$excel->getProperties()->setKeywords("FR");
		$excel->getProperties()->setCategory("FR");
		$excel->setActiveSheetIndex(0);
		$excel->getActiveSheet()->setTitle('FR');


		//------- SET Header Row
		$excel->getActiveSheet()->setCellValue('A1','ERRMSG');
		$excel->getActiveSheet()->setCellValue('B1','REFTYPE');
		$excel->getActiveSheet()->setCellValue('C1','QCCORP');
		$excel->getActiveSheet()->setCellValue('D1','QCBRANCH');
		$excel->getActiveSheet()->setCellValue('E1','STAT');
		$excel->getActiveSheet()->setCellValue('F1','QCBOOK');
		$excel->getActiveSheet()->setCellValue('G1','CODE');
		$excel->getActiveSheet()->setCellValue('H1','REFNO');
		$excel->getActiveSheet()->setCellValue('I1','DATE');
		$excel->getActiveSheet()->setCellValue('J1','QCFRWHOUSE');
		$excel->getActiveSheet()->setCellValue('K1','QCTOWHOUSE');
		$excel->getActiveSheet()->setCellValue('L1','QCSECT');
		$excel->getActiveSheet()->setCellValue('M1','QCJOB');
		$excel->getActiveSheet()->setCellValue('N1','REMARKH1');
		$excel->getActiveSheet()->setCellValue('O1','QCPROD');
		$excel->getActiveSheet()->setCellValue('P1','LOT');
		$excel->getActiveSheet()->setCellValue('Q1','QTY');
		$excel->getActiveSheet()->setCellValue('R1','QCUM');
		$excel->getActiveSheet()->setCellValue('S1','UMQTY');
		$excel->getActiveSheet()->setCellValue('T1','COST');
		$excel->getActiveSheet()->setCellValue('U1','REMARKI1');

		//------ End Header Row

		//------------------------- Start
		//--- start @ row 2
		$row = 2;

		//---	รายการที่รับเข้า
		$qs = $cs->getDetail($cs->id);

		if( dbNumRows($qs) > 0 )
		{
			while( $rs = dbFetchObject($qs) )
			{
				$pd		= new product($rs->id_product);


				//---- SET Cell Format AS TEXT
				$excel->getActiveSheet()->getStyle('A'.$row.':H'.$row)->getNumberFormat()->setFormatCode('@');
				$excel->getActiveSheet()->getStyle('J'.$row.':P'.$row)->getNumberFormat()->setFormatCode('@');
				$excel->getActiveSheet()->getStyle('R'.$row)->getNumberFormat()->setFormatCode('@');
				$excel->getActiveSheet()->getStyle('U'.$row)->getNumberFormat()->setFormatCode('@');


				//--- SET Cell Format AS DATE
				$excel->getActiveSheet()->getStyle('I'.$row)->getNumberFormat()->setFormatCode('dd/mm/yy');


				//--- Set Cell Format AS Numeric
				$excel->getActiveSheet()->getStyle('Q'.$row)->getNumberFormat()->setFormatCode('0.0000');
				$excel->getActiveSheet()->getStyle('S'.$row)->getNumberFormat()->setFormatCode('0.0000');
				$excel->getActiveSheet()->getStyle('T'.$row)->getNumberFormat()->setFormatCode('0.0000');


				//--- Fill data
				//---	เว้นว่างไว้
				$excel->getActiveSheet()->setCellValueExplicit('A'.$row, $ERRMSG, PHPExcel_Cell_DataType::TYPE_STRING);

				//---	ประเภทเอกสาร FR = รับสินค้าจากการผลิต
				$excel->getActiveSheet()->setCellValueExplicit('B'.$row, $REFTYPE, PHPExcel_Cell_DataType::TYPE_STRING);

				//---	รหัสบริษัท
				$excel->getActiveSheet()->setCellValueExplicit('C'.$row, $QCCORP, PHPExcel_Cell_DataType::TYPE_STRING);

				//---	รหัสสาขา
				$excel->getActiveSheet()->setCellValueExplicit('D'.$row, $QCBRANCH, PHPExcel_Cell_DataType::TYPE_STRING);

				//---	สถานะของเอกสาร ปกติว่างไว้,  C = CANCEL
				$excel->getActiveSheet()->setCellValueExplicit('E'.$row, $STAT, PHPExcel_Cell_DataType::TYPE_STRING);

				//---	รหัสเล่มเอกสาร
				$excel->getActiveSheet()->setCellValueExplicit('F'.$row, $QCBOOK, PHPExcel_Cell_DataType::TYPE_STRING);

				//--- รหัสเอกสาร(เว้นว่างไว้ ไป gen ที่ formula)
				$excel->getActiveSheet()->setCellValueExplicit('G'.$row, $CODE, PHPExcel_Cell_DataType::TYPE_STRING);

				//---	เลขที่เอกสารจาก smart invent
				$excel->getActiveSheet()->setCellValueExplicit('H'.$row, $REFNO, PHPExcel_Cell_DataType::TYPE_STRING);

				//---	วันที่เอกสาร
				$excel->getActiveSheet()->setCellValue('I'.$row, $DATE);

				//---	รหัสคลังต้นทาง (คลังระหว่างทำ)
				$excel->getActiveSheet()->setCellValueExplicit('J'.$row, $QCFRWHOUSE, PHPExcel_Cell_DataType::TYPE_STRING);

				//---	รหัสคลังปลายทาง (คลังซื้อขาย)
				$excel->getActiveSheet()->setCellValueExplicit('K'.$row, $wh->getCode($rs->id_warehouse), PHPExcel_Cell_DataType::TYPE_STRING);

				//---	รหัสแผนก
				$excel->getActiveSheet()->setCellValueExplicit('L'.$row, $QCSECT, PHPExcel_Cell_DataType::TYPE_STRING);

				//---	รหัสโครงการ(ถ้ามี)
				$excel->getActiveSheet()->setCellValueExplicit('M'.$row, $QCJOB, PHPExcel_Cell_DataType::TYPE_STRING);

				//---	หมายเหตุที่หัวเอกสาร
				$excel->getActiveSheet()->setCellValueExplicit('N'.$row, $REMARKH1, PHPExcel_Cell_DataType::TYPE_STRING);

				//---	รหัสสินค้า
				$excel->getActiveSheet()->setCellValueExplicit('O'.$row, $pd->code, PHPExcel_Cell_DataType::TYPE_STRING );

				//---	LOT สินค้า
				$excel->getActiveSheet()->setCellValueExplicit('P'.$row, $LOT, PHPExcel_Cell_DataType::TYPE_STRING);

				//---	จำนวนสินค้า
				$excel->getActiveSheet()->setCellValue('Q'.$row, $rs->qty);

				//---	รหัสหน่วยนับ
				$excel->getActiveSheet()->setCellValueExplicit('R'.$row, $pd->getUnitCode($rs->id_product) , PHPExcel_Cell_DataType::TYPE_STRING);

				//---	ตัวแปลงหน่วยนับ
				$excel->getActiveSheet()->setCellValue('S'.$row, 1);

				//---	ต้นทุน/หน่วย
				$excel->getActiveSheet()->setCellValue('T'.$row, $pd->cost);

				//---	หมายเหตุที่ตัวสินค้า
				$excel->getActiveSheet()->setCellValueExplicit('U'.$row, $REMARKI1, PHPExcel_Cell_DataType::TYPE_STRING);

				$row++;

			}
		}

		//---	กำหนดชื่อไฟล์
		$file_name = $path. $REFNO ."-".date('YmdHis').".xls";

		$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
		try
		{
			$writer->save($file_name);
			$sc = TRUE;
			$cs->exported($cs->id);
		}
		catch (Exception $e)
		{
			$sc =  "ERROR : ".$e->getMessage();
		}
		
		return $sc;
	}


?>
