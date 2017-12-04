<?php
	function exportAJ($id)
	{
		//---	ไว้สตรวจสอบความถูกต้อง
		$sc = FALSE;

		//---	ข้อมูลเอกสาร
		$cs = new adjust($id);

		//--- ตรวจสอบก่อนว่าบันทึเอกสารแล้วหรือยัง
		if( $cs->isSaved == 1)
		{
			//---	 ถ้าบันทึกแล้ว

			$pd			= new product();
			$zone 	= new zone();
			$wh			= new warehouse();
			$emp		= new employee();
			$excel 	= new PHPExcel();

			//--------------------  กำหนดค่าตัวแปรที่ต้องมีทุกๆ บรรทัด	-----------------//

			//---	Path ของเอกสาร (tbl_config)
			$path			= getConfig('EXPORT_AJ_PATH');

			//---	เว้นว่างไว้เพื่อเติมข้อมูลกรณีที่ formula importing error
			$ERRMSG		= "";

			//---	รหัสประเภทเอกสาร AJ = ปรับยอดสินค้า
			$REFTYPE	= 'AJ';

			//---	รหัสบริษัท
			$QCCORP		= getConfig('COMPANY_CODE');

			//---	รหัสสาขา
			$QCBRANCH	= getConfig('BRANCH_CODE');

			//---	รหัสแผนก
			$QCSECT		= $emp->getDivisionCode($cs->id_employee);

			//--  รหัสโครงการ (ถ้ามี)
			$QCJOB		= "";

			//-- 	สถานะของเอกสาร ปกติว่างไว้,  C = CANCEL
			$STAT			= "";

			//--	รหัสเล่มเอกสาร
			$QCBOOK 	= $cs->bookcode;

			//--	เลขที่เอกสาร ถ้าว่างไว้ระบบจะ gen ให้เอง
			$CODE			= "";

			//--	เลขที่อ้างอิง (เลขที่เอกสารใน Smart Invent)
			$REFNO	 	= $cs->reference;

			//---	วันที่ของเอกสาร ต้องเป็นปี ค.ศ. Format DD/MM/YYYY เช่น 20/6/2016
			$DATE			= thaiDate($cs->date_add, '/');

			//--- ผู้ขอปรับยอด
			$RECVMAN 	= $cs->requester;

			//---	LOT สินค้า(ถ้ามี)
			$LOT = '';

			//---	หมายเหตุที่หัวเอกสาร
			$REMARKH1	= $cs->remark;

			//---	หมายเหตุที่รายการสินค้า
			$REMARKI1 = '';

			//------------------------ จบกำหนดค่าตัวแปรที่ต้องใส่ในทุกบรรทัด-----------------//


			$excel->getProperties()->setCreator("Samart Invent 2.0");
			$excel->getProperties()->setLastModifiedBy("Samart Invent 2.0");
			$excel->getProperties()->setTitle("AJ");
			$excel->getProperties()->setSubject("AJ");
			$excel->getProperties()->setDescription("This file was generate by Smart invent web application via PHPExcel v.1.8");
			$excel->getProperties()->setKeywords("AJ");
			$excel->getProperties()->setCategory("AJ");
			$excel->setActiveSheetIndex(0);
			$excel->getActiveSheet()->setTitle('AJ');

			//------- SET Header Row
			$excel->getActiveSheet()->setCellValue('A1','ERRMSG');

			//---	รหัสประเภทเอกสาร TR = โอนสินค้าข้ามคลัง
			$excel->getActiveSheet()->setCellValue('B1','REFTYPE');

			//---	รหัสบริษัท
			$excel->getActiveSheet()->setCellValue('C1','QCCORP');

			//---	รหัสสาขา
			$excel->getActiveSheet()->setCellValue('D1','QCBRANCH');

			//---	สถานะของเอกสาร ปกติว่างไว้,  C = CANCEL
			$excel->getActiveSheet()->setCellValue('E1','STAT');

			//---	รหัสเล่มเอกสาร
			$excel->getActiveSheet()->setCellValue('F1','QCBOOK');

			//---	เลขที่เอกสาร ว่างไว้ formula จะ Gen ให้เอง
			$excel->getActiveSheet()->setCellValue('G1','CODE');

			//---	เลขเอกสารใน Smart Invent (เลขที่อ้างอิงฝั่ง formula)
			$excel->getActiveSheet()->setCellValue('H1','REFNO');

			//---	วันที่ของเอกสาร ต้องเป็นปี ค.ศ. Format DD/MM/YYYY เช่น 20/6/2016
			$excel->getActiveSheet()->setCellValue('I1','DATE');

			//---	รหัสแผนก
			$excel->getActiveSheet()->setCellValue('J1','QCSECT');

			//---	รหัสโครงการ(ถ้ามี ไม่มีเว้นว่างไว้)
			$excel->getActiveSheet()->setCellValue('K1','QCJOB');

			//---	ผู้ขอปรับยอด
			$excel->getActiveSheet()->setCellValue('L1', 'RECVMAN');

			//---	หมายเหตุที่หัวเอกสาร
			$excel->getActiveSheet()->setCellValue('M1','REMARKH1');

			//---	รหัสสินค้า
			$excel->getActiveSheet()->setCellValue('N1','QCPROD');

			//---	รหัสคลัง
			$excel->getActiveSheet()->setCellValue('O1','QCWHOUSE');

			//---	LOT ของสินค้า (ไม่มีเว้นว่างไว้)
			$excel->getActiveSheet()->setCellValue('P1','LOT');

			//---	จำนวนสินค้า
			$excel->getActiveSheet()->setCellValue('Q1','QTY');

			//---	รหัสหน่วยนับ
			$excel->getActiveSheet()->setCellValue('R1','QCUM');

			//---	อัตราส่วน หน่วยนับ(QCUM) / หน่วยมาตรฐานในฐานข้อมูลสินค้า
			$excel->getActiveSheet()->setCellValue('S1','UMQTY');

			//---	ต้นทุน/หน่วย
			$excel->getActiveSheet()->setCellValue('T1', 'COST');

			//---	หมายเหตุที่รายการสินค้า
			$excel->getActiveSheet()->setCellVAlue('U1', 'REMARKI1');


			//------ End Header Row

			//---- Start
			$row = 2;  //--- start on row 2
			$qs = $cs->getDetails($cs->id);
			if( dbNumRows($qs) > 0 )
			{
				while( $rs = dbFetchObject($qs) )
				{
					//---- SET Cell Format AS TEXT
					$excel->getActiveSheet()->getStyle('A'.$row.':H'.$row)->getNumberFormat()->setFormatCode('@');
					$excel->getActiveSheet()->getStyle('J'.$row.':P'.$row)->getNumberFormat()->setFormatCode('@');
					$excel->getActiveSheet()->getStyle('R'.$row)->getNumberFormat()->setFormatCode('@');
					$excel->getActiveSheet()->getStyle('U'.$row)->getNumberFormat()->setFormatCode('@');

					//--- SET Cell Format AS DATE
					$excel->getActiveSheet()->getStyle('I'.$row)->getNumberFormat()->setFormatCode('dd/mm/yy');

					//--- Set Cell Format AS Numeric
					$excel->getActiveSheet()->getStyle('Q'.$row)->getNumberFormat()->setFormatCode('0.0000'); //-- creadit term
					$excel->getActiveSheet()->getStyle('S'.$row.':T'.$row)->getNumberFormat()->setFormatCode('0.0000'); //-- bill_discount



					//--------------	Fill data to cells with format

					//---	เว้นว่างเพื่อให้ formula ใส่ข้อมูลการนำเข้าที่ผิดพลาด
					$excel->getActiveSheet()->setCellValueExplicit('A'.$row, $ERRMSG, PHPExcel_Cell_DataType::TYPE_STRING);

					//---	รหัสประเภทเอกสาร
					$excel->getActiveSheet()->setCellValueExplicit('B'.$row, $REFTYPE, PHPExcel_Cell_DataType::TYPE_STRING);

					//---	รหัสบริษัท
					$excel->getActiveSheet()->setCellValueExplicit('C'.$row, $QCCORP, PHPExcel_Cell_DataType::TYPE_STRING);

					//---	รหัสสาขา
					$excel->getActiveSheet()->setCellValueExplicit('D'.$row, $QCBRANCH, PHPExcel_Cell_DataType::TYPE_STRING);

					//---	สถานะเอกสาร ปกติ = ว่างไว้ , C = CANCLE
					$excel->getActiveSheet()->setCellValueExplicit('E'.$row, $STAT, PHPExcel_Cell_DataType::TYPE_STRING);

					//---	รหัสเล่มเอกสาร
					$excel->getActiveSheet()->setCellValueExplicit('F'.$row, $QCBOOK, PHPExcel_Cell_DataType::TYPE_STRING);

					//---	เลขที่เอกสารใน formula ว่างไว้ formula จะ GEN ให้เอง
					$excel->getActiveSheet()->setCellValueExplicit('G'.$row, $CODE, PHPExcel_Cell_DataType::TYPE_STRING);

					//---	เลขที่เอกสารใน Smart Invent
					$excel->getActiveSheet()->setCellValueExplicit('H'.$row, $REFNO, PHPExcel_Cell_DataType::TYPE_STRING);

					//---	วันที่เอกสาร
					$excel->getActiveSheet()->setCellValue('I'.$row, $DATE);

					//---	รหัสแผนก
					$excel->getActiveSheet()->setCellValueExplicit('J'.$row, $QCSECT, PHPExcel_Cell_DataType::TYPE_STRING);

					//---	รหัสโครงการ(ถ้ามี)
					$excel->getActiveSheet()->setCellValueExplicit('K'.$row, $QCJOB, PHPExcel_Cell_DataType::TYPE_STRING);

					//---	ผู้ขอปรับยอด
					$excel->getActiveSheet()->setCellValueExplicit('L'.$row, $RECVMAN, PHPExcel_Cell_DataType::TYPE_STRING);

					//---	หมายเหตุที่หัวเอกสาร
					$excel->getActiveSheet()->setCellValueExplicit('M'.$row, $REMARKH1, PHPExcel_Cell_DataType::TYPE_STRING);

					//---	รหัสสินค้า(SKU)
					$excel->getActiveSheet()->setCellValueExplicit('N'.$row, $pd->getCode($rs->id_product), PHPExcel_Cell_DataType::TYPE_STRING );

					//---	รหัสคลัง
					$excel->getActiveSheet()->setCellValueExplicit('O'.$row, $wh->getCode($zone->getWarehouseId($rs->id_zone)), PHPExcel_Cell_DataType::TYPE_STRING );

					//---	LOT สินค้า(ถ้ามี)
					$excel->getActiveSheet()->setCellValueExplicit('P'.$row, $LOT, PHPExcel_Cell_DataType::TYPE_STRING);

					//---	จำนวนสินค้า
					$excel->getActiveSheet()->setCellValue('Q'.$row, $rs->qty);

					//---	รหัสหน่วยนับ
					$excel->getActiveSheet()->setCellValueExplicit('R'.$row, $pd->getUnitCode($rs->id_product) , PHPExcel_Cell_DataType::TYPE_STRING);

					//---	อัตราส่วนหน่ายนับ
					$excel->getActiveSheet()->setCellValue('S'.$row, 1);

					//---	ต้นทุน/หน่วย
					$excel->getActiveSheet()->setCellValue('T'.$row, $pd->getCost($rs->id_product));

					//---	หมายเหตุที่ตัวสินค้า
					$excel->getActiveSheet()->setCellValue('U'.$row, '');

					$row++;

				}
			}

			$file_name = $path . $cs->reference ."-".date('YmdHis').".xls";

			$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
			try
			{
				$writer->save($file_name);
				$sc = TRUE;
				$cs->exported($cs->id);
			}
			catch (Exception $e)
			{
				$sc = "ERROR : ".$e->getMessage();
			}
		}
		else
		{
			$sc = 'ยังไม่ได้บันทึกเอกสาร';
		}

		return $sc;
	}


?>
