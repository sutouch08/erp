<?php
	function exportSO($id_order)
	{
		$sc 	= FALSE;
		$order 	= new order($id_order);
		$cust 	= new customer($order->id_customer);
		$sale 	= new sale();
		$pd			= new product();
		$wh			= new warehouse();
		$emp		= new employee();
		$excel 	= new PHPExcel();

		$path			= getConfig('EXPORT_SO_PATH');
		$ERRMSG		= "";
		$REFTYPE	= 'SO';
		$QCCORP		= getConfig('COMPANY_CODE'); 	//-- รหัสบริษัท
		$QCBRANCH	= getConfig('BRANCH_CODE'); 		//-- รหัสสาขา
		$QCSECT		= $emp->getDivisionCode($order->id_employee); 	//-- รหัสแผนก
		$QCJOB		= ""; 	//--  รหัสโครงการ (ถ้ามี)
		$STAT			= "";	//-- 	สถานะของเอกสาร ปกติว่างไว้,  C = CANCEL
		$QCBOOK 	= $order->getBookCode($order->role, $order->is_so);		//--	รหัสเล่มเอกสาร
		$CODE			= "";	//--	เลขที่เอกสาร ถ้าว่างไว้ระบบจะ gen ให้เอง
		$REFNO	 	= $order->reference;		//--	เลขที่อ้างอิง (เลขที่เอกสารใน Smart Invent)
		$DATE			= thaiDate($order->date_add, '/');	//--	วันที่ของเอกสาร
		$RECEDATE	= thaiDate($order->date_add, '/');;	//--	วันที่ยื่นภาษี
		$DUEDATE	= date('d/m/y', strtotime('+'.$cust->term.' day '.$order->date_add));	//--	วันที่ครบกำหนด
		$QCCOOR		= $cust->getCode($order->id_customer);	//--	รหัสลูกค้า
		$QCEMPL		= $sale->getCode($order->id_sale);
		$CREDTERM	= $cust->getTerm($order->id_customer);	//--	เครดิตเทอม (จำนวนวัน)
		$HASRET		= "Y"; 	//--	เป็นภาษีขอคืนได้ [ Y = ขอคืนได้,  N = ขอคืนไม่ได้ ]
		$DETAIL		= "";		//--	รายละเอียดที่พิมพ์ในรายงานภาษีซื้อ - ขาย
		$VATISOUT	= 'N';	//--	การคิดภาษี  Y = ภาษีแยกนอก,  N = ภาษีรวมใน
		$VATTYPE	= getConfig('ORDER_VAT_TYPE');		//--	ประเภทภาษี  1 = 7%, 2 = 1.5%, 3 = 0%, 4 = ไม่มี VAT,  5 = 10%
		$HDISCSTR	= $order->bDiscText;	//--	ข้อความ ส่วนลดท้ายบิลเป็น % หรือยอดเงิน เช่น 10%+5% , 200+3%
		$DISCAMT1	= $order->bDiscAmount;		//--	มูลค่าส่วนลดท้ายบิล เป็นจำนวนเงินบาท
		$AMT			= round( $order->getTotalSoldAmount($order->id, FALSE), 2);	//--	มูลค่าสินค้าไม่รวม VAT (เป็นยอดรวมทั้งบิล) FALSE = ไม่รวม VAT decmal 15,2
		$VATAMT		= round( getVatAmount( $order->getTotalSoldAmount($order->id,TRUE ), getVatRate($VATTYPE), FALSE),2 );	//--	มูลค่าภาษี (เป็นยอดรวมทั้งบิล) TRUE = VAT นอก FALSE = VAT ใน decmal 14,2
		$QCCURRENCY	= "";		//--	รหัสหน่วยเงิน
		$XRATE		= 1;			//--	อัตราแลกเปลี่ยนหน่วยเงิน
		$DISCAMTK	= $DISCAMT1;			//--	มูลค่าส่วนลดท้ายบิล เป็นจำนวนเงิน ตามหน่วยเงินที่คีย์
		$AMTKE		= $AMT;			//--	มูลค่าสินค้าไม่รวม VAT (เป็นยอดรวมทั้งบิล) ตามหน่วยเงินที่คีย์
		$VATAMTKE	= $VATAMT;			//-- 	มูลค่าภาษี (เป็นยอดรวมทั้งบิล) ตามหน่วยเงินที่คีย์
		$LOT			= "";
		$PRICEKE	= "";			//--	ราคาสินค้า ตามหน่วยเงินที่คีย์  กรณีมี Option Currency
		$DISCAMTIK	= "";		//--	ราคาสินค้า ตามหน่วยเงินที่คีย์
		$QCSECTI	= "";			//--	รหัสแผนก ที่รายการสินค้า (กรณีมี Feature แผนกที่ Item)
		$QCJOBI		= "";			//--	รหัส JOB  ที่รายการสินค้า (กรณีมี Feature Job ที่ Item)
		$REMARKH1	= $order->remark;
		$REMARKH2	= "";
		$REMARKH3	= "";
		$REMARKH4	= "";
		$REMARKH5	= "";
		$REMARKH6	= "";
		$REMARKH7	= "";
		$REMARKH8	= "";
		$REMARKH9	= "";
		$REMARKH10	= "";
		$REMARKI1	= "";
		$REMARKI2	= "";
		$REMARKI3	= "";
		$REMARKI4	= "";
		$REMARKI5	= "";
		$REMARKI6	= "";
		$REMARKI7	= "";
		$REMARKI8	= "";
		$REMARKI9	= "";
		$REMARKI10	= "";



		$excel->getProperties()->setCreator("Samart Invent 2.0");
		$excel->getProperties()->setLastModifiedBy("Samart Invent 2.0");
		$excel->getProperties()->setTitle("SO");
		$excel->getProperties()->setSubject("SO");
		$excel->getProperties()->setDescription("This file was generate by Smart invent web application via PHPExcel v.1.8");
		$excel->getProperties()->setKeywords("SO");
		$excel->getProperties()->setCategory("SO");
		$excel->setActiveSheetIndex(0);
		$excel->getActiveSheet()->setTitle('SO');

		//------- SET Header Row
		$excel->getActiveSheet()->setCellValue('A1','ERRMSG');
		$excel->getActiveSheet()->setCellValue('B1','REFTYPE');
		$excel->getActiveSheet()->setCellValue('C1','QCCORP');
		$excel->getActiveSheet()->setCellValue('D1','QCBRANCH');
		$excel->getActiveSheet()->setCellValue('E1','QCSECT');
		$excel->getActiveSheet()->setCellValue('F1','QCJOB');
		$excel->getActiveSheet()->setCellValue('G1','STAT');
		$excel->getActiveSheet()->setCellValue('H1','QCBOOK');
		$excel->getActiveSheet()->setCellValue('I1','CODE');
		$excel->getActiveSheet()->setCellValue('J1','REFNO');
		$excel->getActiveSheet()->setCellValue('K1','DATE');
		$excel->getActiveSheet()->setCellValue('L1','RECEDATE');
		$excel->getActiveSheet()->setCellValue('M1','DUEDATE');
		$excel->getActiveSheet()->setCellValue('N1','QCCOOR');
		$excel->getActiveSheet()->setCellValue('O1','QCEMPL');
		$excel->getActiveSheet()->setCellValue('P1','CREDTERM');
		$excel->getActiveSheet()->setCellValue('Q1','VATISOUT');
		$excel->getActiveSheet()->setCellValue('R1','VATTYPE');
		$excel->getActiveSheet()->setCellValue('S1','HDISCSTR');
		$excel->getActiveSheet()->setCellValue('T1','DISCAMT1');
		$excel->getActiveSheet()->setCellValue('U1','AMT');
		$excel->getActiveSheet()->setCellValue('V1','VATAMT');
		$excel->getActiveSheet()->setCellValue('W1','QCCURRENCY');
		$excel->getActiveSheet()->setCellValue('X1','XRATE');
		$excel->getActiveSheet()->setCellValue('Y1','DISCAMTK');
		$excel->getActiveSheet()->setCellValue('Z1','AMTKE');
		$excel->getActiveSheet()->setCellValue('AA1','VATAMTKE');
		$excel->getActiveSheet()->setCellValue('AB1','QCPROD');
		$excel->getActiveSheet()->setCellValue('AC1','QCWHOUSE');
		$excel->getActiveSheet()->setCellValue('AD1','LOT');
		$excel->getActiveSheet()->setCellValue('AE1','QTY');
		$excel->getActiveSheet()->setCellValue('AF1','QCUM');
		$excel->getActiveSheet()->setCellValue('AG1','UMQTY');
		$excel->getActiveSheet()->setCellValue('AH1','PRICE');
		$excel->getActiveSheet()->setCellValue('AI1','DISCSTR');
		$excel->getActiveSheet()->setCellValue('AJ1','PRICEKE');
		$excel->getActiveSheet()->setCellValue('AK1','DISCAMTIK');
		$excel->getActiveSheet()->setCellValue('AL1','QCSECTI');
		$excel->getActiveSheet()->setCellValue('AM1','QCJOBI');
		$excel->getActiveSheet()->setCellValue('AN1','REMARKH1');
		$excel->getActiveSheet()->setCellValue('AO1','REMARKH2');
		$excel->getActiveSheet()->setCellValue('AP1','REMARKH3');
		$excel->getActiveSheet()->setCellValue('AQ1','REMARKH4');
		$excel->getActiveSheet()->setCellValue('AR1','REMARKH5');
		$excel->getActiveSheet()->setCellValue('AS1','REMARKH6');
		$excel->getActiveSheet()->setCellValue('AT1','REMARKH7');
		$excel->getActiveSheet()->setCellValue('AU1','REMARKH8');
		$excel->getActiveSheet()->setCellValue('AV1','REMARKH9');
		$excel->getActiveSheet()->setCellValue('AW1','REMARKH10');
		$excel->getActiveSheet()->setCellValue('AX1','REMARKI1');
		$excel->getActiveSheet()->setCellValue('AY1','REMARKI2');
		$excel->getActiveSheet()->setCellValue('AZ1','REMARKI3');
		$excel->getActiveSheet()->setCellValue('BA1','REMARKI4');
		$excel->getActiveSheet()->setCellValue('BB1','REMARKI5');
		$excel->getActiveSheet()->setCellValue('BC1','REMARKI6');
		$excel->getActiveSheet()->setCellValue('BD1','REMARKI7');
		$excel->getActiveSheet()->setCellValue('BE1','REMARKI8');
		$excel->getActiveSheet()->setCellValue('BF1','REMARKI9');
		$excel->getActiveSheet()->setCellValue('BG1','REMARKI10');

		//------ End Header Row

		//---- Start
		$row = 2;  //--- start on row 2
		$qs = $order->getSoldDetails($order->id);
		if( dbNumRows($qs) > 0 )
		{
			while( $rs = dbFetchObject($qs) )
			{
				//---- SET Cell Format AS TEXT

				$excel->getActiveSheet()->getStyle('A'.$row.':J'.$row)->getNumberFormat()->setFormatCode('@');
				$excel->getActiveSheet()->getStyle('N'.$row.':O'.$row)->getNumberFormat()->setFormatCode('@');
				$excel->getActiveSheet()->getStyle('Q'.$row.':S'.$row)->getNumberFormat()->setFormatCode('@');
				$excel->getActiveSheet()->getStyle('W'.$row)->getNumberFormat()->setFormatCode('@');
				$excel->getActiveSheet()->getStyle('AB'.$row.':AD'.$row)->getNumberFormat()->setFormatCode('@');
				$excel->getActiveSheet()->getStyle('AF'.$row.':AI'.$row)->getNumberFormat()->setFormatCode('@');
				$excel->getActiveSheet()->getStyle('AL'.$row.':BG'.$row)->getNumberFormat()->setFormatCode('@');

				//--- SET Cell Format AS DATE
				$excel->getActiveSheet()->getStyle('K'.$row)->getNumberFormat()->setFormatCode('dd/mm/yy');
				$excel->getActiveSheet()->getStyle('L'.$row)->getNumberFormat()->setFormatCode('dd/mm/yy');
				$excel->getActiveSheet()->getStyle('M'.$row)->getNumberFormat()->setFormatCode('dd/mm/yy');

				//--- Set Cell Format AS Numeric
				$excel->getActiveSheet()->getStyle('P'.$row)->getNumberFormat()->setFormatCode('0'); //-- creadit term
				$excel->getActiveSheet()->getStyle('T'.$row.':V'.$row)->getNumberFormat()->setFormatCode('0.00'); //-- bill_discount
				$excel->getActiveSheet()->getStyle('Y'.$row.':Z'.$row)->getNumberFormat()->setFormatCode('0.00');
				$excel->getActiveSheet()->getStyle('X'.$row)->getNumberFormat()->setFormatCode('0.00000000');
				$excel->getActiveSheet()->getStyle('AA'.$row)->getNumberFormat()->setFormatCode('0.00');
				$excel->getActiveSheet()->getStyle('AE'.$row)->getNumberFormat()->setFormatCode('0.0000');
				$excel->getActiveSheet()->getStyle('AG'.$row)->getNumberFormat()->setFormatCode('0.0000');
				$excel->getActiveSheet()->getStyle('AH'.$row)->getNumberFormat()->setFormatCode('0.00');
				$excel->getActiveSheet()->getStyle('AJ'.$row.':AK'.$row)->getNumberFormat()->setFormatCode('0.00');

				$excel->getActiveSheet()->setCellValueExplicit('A'.$row, $ERRMSG, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('B'.$row, $REFTYPE, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('C'.$row, $QCCORP, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('D'.$row, $QCBRANCH, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('E'.$row, $QCSECT, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('F'.$row, $QCJOB, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('G'.$row, $STAT, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('H'.$row, $QCBOOK, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('I'.$row, $CODE, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('J'.$row, $REFNO, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValue('K'.$row, $DATE);
				$excel->getActiveSheet()->setCellValue('L'.$row, $RECEDATE);
				$excel->getActiveSheet()->setCellValue('M'.$row, $DUEDATE);
				$excel->getActiveSheet()->setCellValueExplicit('N'.$row, $QCCOOR, PHPExcel_Cell_DataType::TYPE_STRING);

				$excel->getActiveSheet()->setCellValueExplicit('O'.$row, $QCEMPL, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('P'.$row, $CREDTERM, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('Q'.$row, $VATISOUT, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('R'.$row, $VATTYPE, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('S'.$row, $HDISCSTR, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValue('T'.$row, $DISCAMT1);
				$excel->getActiveSheet()->setCellValue('U'.$row, $AMT);
				$excel->getActiveSheet()->setCellValue('V'.$row, $VATAMT);
				$excel->getActiveSheet()->setCellValueExplicit('W'.$row, $QCCURRENCY, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValue('X'.$row, $XRATE);
				$excel->getActiveSheet()->setCellValue('Y'.$row, $DISCAMTK);
				$excel->getActiveSheet()->setCellValue('Z'.$row, $AMTKE);
				$excel->getActiveSheet()->setCellValue('AA'.$row, $VATAMTKE);
				$excel->getActiveSheet()->setCellValueExplicit('AB'.$row, $rs->product_code, PHPExcel_Cell_DataType::TYPE_STRING );
				$excel->getActiveSheet()->setCellValueExplicit('AC'.$row, $wh->getCode($rs->id_warehouse), PHPExcel_Cell_DataType::TYPE_STRING );
				$excel->getActiveSheet()->setCellValueExplicit('AD'.$row, $LOT, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValue('AE'.$row, $rs->qty);
				$excel->getActiveSheet()->setCellValueExplicit('AF'.$row, $pd->getUnitCode($rs->id_product) , PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValue('AG'.$row, 1);
				$excel->getActiveSheet()->setCellValue('AH'.$row, $rs->price_inc );
				$excel->getActiveSheet()->setCellValueExplicit('AI'.$row, $rs->discount_label, PHPExcel_Cell_DataType::TYPE_STRING );
				$excel->getActiveSheet()->setCellValue('AJ'.$row, $rs->price_inc);
				$excel->getActiveSheet()->setCellValue('AK'.$row, $rs->discount_label, PHPExcel_Cell_DataType::TYPE_STRING );
				$excel->getActiveSheet()->setCellValueExplicit('AL'.$row, $QCSECTI, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('AM'.$row, $QCJOBI, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('AN'.$row, $REMARKH1, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('AO'.$row, $REMARKH2, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('AP'.$row, $REMARKH3, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('AQ'.$row, $REMARKH4, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('AR'.$row, $REMARKH5, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('AS'.$row, $REMARKH6, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('AT'.$row, $REMARKH7, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('AU'.$row, $REMARKH8, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('AV'.$row, $REMARKH9, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('AW'.$row, $REMARKH10, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('AX'.$row, $REMARKI1, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('AY'.$row, $REMARKI2, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('BZ'.$row, $REMARKI3, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('BA'.$row, $REMARKI4, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('BB'.$row, $REMARKI5, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('BC'.$row, $REMARKI6, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('BD'.$row, $REMARKI7, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('BE'.$row, $REMARKI8, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('BF'.$row, $REMARKI9, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('BG'.$row, $REMARKI10, PHPExcel_Cell_DataType::TYPE_STRING);
				$row++;

			}
		}

		$file_name = $path.$order->reference.'-'.date('YmdHis').".xls";
		//header('Content-Type: application/vnd.ms-excel'); /// form excel5 xls
		//header('Content-Disposition: attachment;filename="'.$file_name.'"');
		$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
		try
		{
			$writer->save($file_name);
			$sc = TRUE;
			$order->exported($order->id);
		}
		catch (Exception $e)
		{
			$sc =  "ERROR : ".$e->getMessage();
		}

		return $sc;
	}


?>
