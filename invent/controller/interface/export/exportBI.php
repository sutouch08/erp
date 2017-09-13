<?php
	function exportBI($id_receive_product)
	{
		$sc 	= FALSE;
		$cs 	= new receive_product($id_receive_product);
		$sp	= new supplier();
		$po	= new po($cs->po);
		$pd	= new product();
		$wh	= new warehouse();
		$emp	= new employee();
		$excel = new PHPExcel();
		
		$path	= getConfig('EXPORT_BI_PATH');
		$ERRMSG		= "";
		$REFTYPE		= 'BI';
		$QCCORP		= getConfig('COMPANY_CODE'); 	//-- รหัสบริษัท
		$QCBRANCH	= getConfig('BRANCH_CODE'); 		//-- รหัสสาขา
		$QCSECT		= $emp->getDivisionCode($cs->id_employee); 	//-- รหัสแผนก
		$QCJOB		= ""; 	//--  รหัสโครงการ (ถ้ามี)
		$STAT		= "";	//-- 	สถานะของเอกสาร ปกติว่างไว้,  C = CANCEL
		$QCBOOK 	= $cs->bookcode;		//--	รหัสเล่มเอกสาร
		$CODE			= "";	//--	เลขที่เอกสาร ถ้าว่างไว้ระบบจะ gen ให้เอง
		$REFNO	 	= $cs->reference;		//--	เลขที่อ้างอิง (เลขที่เอกสารใน Smart Invent)
		$DATE			= thaiDate($cs->date_add, '/');	//--	วันที่ของเอกสาร
		$RECEDATE	= "";	//--	วันที่ยื่นภาษี
		$DUEDATE	= "";	//--	วันที่ครบกำหนด
		$QCCOOR		= $sp->getCode($cs->id_supplier);	//--	รหัสผู้จำหน่าย
		$CREDTERM	= $po->credit_term;	//--	เครดิตเทอม (จำนวนวัน) 
		$HASRET		= "Y"; 	//--	เป็นภาษีขอคืนได้ [ Y = ขอคืนได้,  N = ขอคืนไม่ได้ ]
		$DETAIL		= "";	//--	รายละเอียดที่พิมพ์ในรายงานภาษีซื้อ - ขาย
		$VATISOUT	= $po->vat_is_out;	//--	การคิดภาษี  Y = ภาษีแยกนอก,  N = ภาษีรวมใน
		$VATTYPE	= $po->vat_type;		//--	ประเภทภาษี  1 = 7%, 2 = 1.5%, 3 = 0%, 4 = ไม่มี VAT,  5 = 10%
		$HDISCSTR	= "";	//--	ข้อความ ส่วนลดท้ายบิลเป็น % หรือยอดเงิน เช่น 10%+5% , 200+3%
		$DISCAMT1	= 	$po->bill_discount;		//--	มูลค่าส่วนลดท้ายบิล เป็นจำนวนเงินบาท
		$AMT			= $VATISOUT == 'Y' ? round( $cs->getTotalAmount($cs->id, $cs->po), 2) : round( removeVat( $cs->getTotalAmount($cs->id, $cs->po), getVatRate($VATTYPE) ), 2 );	//--	มูลค่าสินค้าไม่รวม VAT (เป็นยอดรวมทั้งบิล) decmal 15,2
		$VATAMT		= $VATISOUT == 'Y' ? round(getVatAmount($AMT, getVatRate( $VATTYPE ), TRUE ), 2) : round(getVatAmount($AMT, getVatRate( $VATTYPE ), FALSE ), 2);	//--	มูลค่าภาษี (เป็นยอดรวมทั้งบิล) decmal 14,2
		$QCCURRENCY	= "";	//--	รหัสหน่วยเงิน
		$XRATE		= "";	//--	อัตราแลกเปลี่ยนหน่วยเงิน
		$DISCAMTK	= "";	//--	มูลค่าส่วนลดท้ายบิล เป็นจำนวนเงิน ตามหน่วยเงินที่คีย์
		$AMTKE		= "";	//--	มูลค่าสินค้าไม่รวม VAT (เป็นยอดรวมทั้งบิล) ตามหน่วยเงินที่คีย์
		$VATAMTKE	= "";	//-- 	มูลค่าภาษี (เป็นยอดรวมทั้งบิล) ตามหน่วยเงินที่คีย์
		$LOT			= "";
		$Priceke		= "";	//--	ราคาสินค้า ตามหน่วยเงินที่คีย์  กรณีมี Option Currency
		$DiscAmtIk	= "";	//--	ราคาสินค้า ตามหน่วยเงินที่คีย์
		$QCSECTI		= "";	//--	รหัสแผนก ที่รายการสินค้า (กรณีมี Feature แผนกที่ Item)
		$QCJOBI		= "";	//--	รหัส JOB  ที่รายการสินค้า (กรณีมี Feature Job ที่ Item)
		$REMARKH1	= $cs->remark;
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
		$excel->getProperties()->setTitle("BI");
		$excel->getProperties()->setSubject("BI");
		$excel->getProperties()->setDescription("This file was generate by Smart invent web application via PHPExcel v.1.8");
		$excel->getProperties()->setKeywords("BI");
		$excel->getProperties()->setCategory("BI");	
		$excel->setActiveSheetIndex(0);
		$excel->getActiveSheet()->setTitle('BI');
		
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
		$excel->getActiveSheet()->setCellValue('O1','CREDTERM');
		$excel->getActiveSheet()->setCellValue('P1','HASRET');
		$excel->getActiveSheet()->setCellValue('Q1','DETAIL');
		$excel->getActiveSheet()->setCellValue('R1','VATISOUT');
		$excel->getActiveSheet()->setCellValue('S1','VATTYPE');
		$excel->getActiveSheet()->setCellValue('T1','HDISCSTR');
		$excel->getActiveSheet()->setCellValue('U1','DISCAMT1');
		$excel->getActiveSheet()->setCellValue('V1','AMT');
		$excel->getActiveSheet()->setCellValue('W1','VATAMT');
		$excel->getActiveSheet()->setCellValue('X1','QCCURRENCY');
		$excel->getActiveSheet()->setCellValue('Y1','XRATE');
		$excel->getActiveSheet()->setCellValue('Z1','DISCAMTK');
		$excel->getActiveSheet()->setCellValue('AA1','AMTKE');
		$excel->getActiveSheet()->setCellValue('AB1','VATAMTKE');
		$excel->getActiveSheet()->setCellValue('AC1','QCPROD');
		$excel->getActiveSheet()->setCellValue('AD1','QCWHOUSE');
		$excel->getActiveSheet()->setCellValue('AE1','LOT');
		$excel->getActiveSheet()->setCellValue('AF1','QTY');
		$excel->getActiveSheet()->setCellValue('AG1','QCUM');
		$excel->getActiveSheet()->setCellValue('AH1','UMQTY      ');
		$excel->getActiveSheet()->setCellValue('AI1','PRICE');
		$excel->getActiveSheet()->setCellValue('AJ1','DISCSTR');
		$excel->getActiveSheet()->setCellValue('AK1','Priceke');
		$excel->getActiveSheet()->setCellValue('AL1','DiscAmtIk');
		$excel->getActiveSheet()->setCellValue('AM1','QCSECTI');
		$excel->getActiveSheet()->setCellValue('AN1','QCJOBI');
		$excel->getActiveSheet()->setCellValue('AO1','REMARKH1');
		$excel->getActiveSheet()->setCellValue('AP1','REMARKH2');
		$excel->getActiveSheet()->setCellValue('AQ1','REMARKH3');
		$excel->getActiveSheet()->setCellValue('AR1','REMARKH4');
		$excel->getActiveSheet()->setCellValue('AS1','REMARKH5');
		$excel->getActiveSheet()->setCellValue('AT1','REMARKH6');
		$excel->getActiveSheet()->setCellValue('AU1','REMARKH7');
		$excel->getActiveSheet()->setCellValue('AV1','REMARKH8');
		$excel->getActiveSheet()->setCellValue('AW1','REMARKH9');
		$excel->getActiveSheet()->setCellValue('AX1','REMARKH10');
		$excel->getActiveSheet()->setCellValue('AY1','REMARKI1');
		$excel->getActiveSheet()->setCellValue('AZ1','REMARKI2');
		$excel->getActiveSheet()->setCellValue('BA1','REMARKI3');
		$excel->getActiveSheet()->setCellValue('BB1','REMARKI4');
		$excel->getActiveSheet()->setCellValue('BC1','REMARKI5');
		$excel->getActiveSheet()->setCellValue('BD1','REMARKI6');
		$excel->getActiveSheet()->setCellValue('BE1','REMARKI7');
		$excel->getActiveSheet()->setCellValue('BF1','REMARKI8');
		$excel->getActiveSheet()->setCellValue('BG1','REMARKI9');
		$excel->getActiveSheet()->setCellValue('BH1','REMARKI10');
		//------ End Header Row
		
		//---- Start 
		$row = 2;  //--- start on row 2
		$qs = $cs->getDetail($cs->id);
		if( dbNumRows($qs) > 0 )
		{
			while( $rs = dbFetchObject($qs) )
			{
				//---- SET Cell Format AS TEXT
				
				$excel->getActiveSheet()->getStyle('A'.$row.':J'.$row)->getNumberFormat()->setFormatCode('@');
				$excel->getActiveSheet()->getStyle('N'.$row)->getNumberFormat()->setFormatCode('@');
				$excel->getActiveSheet()->getStyle('P'.$row.':T'.$row)->getNumberFormat()->setFormatCode('@');
				$excel->getActiveSheet()->getStyle('X'.$row)->getNumberFormat()->setFormatCode('@');
				$excel->getActiveSheet()->getStyle('AG'.$row)->getNumberFormat()->setFormatCode('@');
				$excel->getActiveSheet()->getStyle('AJ'.$row)->getNumberFormat()->setFormatCode('@');
				$excel->getActiveSheet()->getStyle('AC'.$row)->getNumberFormat()->setFormatCode('@');
				$excel->getActiveSheet()->getStyle('AD'.$row.':AE'.$row)->getNumberFormat()->setFormatCode('@');
				$excel->getActiveSheet()->getStyle('AM'.$row.':BH'.$row)->getNumberFormat()->setFormatCode('@');
				
				//--- SET Cell Format AS DATE
				$excel->getActiveSheet()->getStyle('K'.$row)->getNumberFormat()->setFormatCode('dd/mm/yy');
				$excel->getActiveSheet()->getStyle('L'.$row)->getNumberFormat()->setFormatCode('dd/mm/yy');
				$excel->getActiveSheet()->getStyle('M'.$row)->getNumberFormat()->setFormatCode('dd/mm/yy');
				
				//--- Set Cell Format AS Numeric
				$excel->getActiveSheet()->getStyle('O'.$row)->getNumberFormat()->setFormatCode('0'); //-- creadit term
				$excel->getActiveSheet()->getStyle('U'.$row)->getNumberFormat()->setFormatCode('0.00'); //-- bill_discount
				$excel->getActiveSheet()->getStyle('V'.$row)->getNumberFormat()->setFormatCode('0.00');
				$excel->getActiveSheet()->getStyle('W'.$row)->getNumberFormat()->setFormatCode('0.00');
				$excel->getActiveSheet()->getStyle('AF'.$row)->getNumberFormat()->setFormatCode('0.0000');
				$excel->getActiveSheet()->getStyle('AH'.$row)->getNumberFormat()->setFormatCode('0.0000');
				$excel->getActiveSheet()->getStyle('AI'.$row)->getNumberFormat()->setFormatCode('0.0000');
				
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
				$excel->getActiveSheet()->setCellValue('O'.$row, $CREDTERM);
				$excel->getActiveSheet()->setCellValueExplicit('P'.$row, $HASRET, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('Q'.$row, $DETAIL, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('R'.$row, $VATISOUT, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('S'.$row, $VATTYPE, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('T'.$row, $HDISCSTR, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValue('U'.$row, $DISCAMT1);
				$excel->getActiveSheet()->setCellValue('V'.$row, $AMT);
				$excel->getActiveSheet()->setCellValue('W'.$row, $VATAMT);
				$excel->getActiveSheet()->setCellValueExplicit('X'.$row, $QCCURRENCY, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValue('Y'.$row, $XRATE);
				$excel->getActiveSheet()->setCellValue('Z'.$row, $DISCAMTK);
				$excel->getActiveSheet()->setCellValue('AA'.$row, $AMTKE);
				$excel->getActiveSheet()->setCellValue('AB'.$row, $VATAMTKE);
				$excel->getActiveSheet()->setCellValueExplicit('AC'.$row, $pd->getCode($rs->id_product), PHPExcel_Cell_DataType::TYPE_STRING );
				$excel->getActiveSheet()->setCellValueExplicit('AD'.$row, $wh->getCode($rs->id_warehouse), PHPExcel_Cell_DataType::TYPE_STRING );
				$excel->getActiveSheet()->setCellValueExplicit('AE'.$row, $LOT, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValue('AF'.$row, $rs->qty);
				$excel->getActiveSheet()->setCellValueExplicit('AG'.$row, $pd->getUnitCode($rs->id_product) , PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValue('AH'.$row, 1);
				$excel->getActiveSheet()->setCellValue('AI'.$row, $po->getPrice($cs->po, $rs->id_product) );
				$excel->getActiveSheet()->setCellValueExplicit('AJ'.$row, $po->getDiscount($cs->po, $rs->id_product), PHPExcel_Cell_DataType::TYPE_STRING );
				$excel->getActiveSheet()->setCellValue('AK'.$row, $Priceke);
				$excel->getActiveSheet()->setCellValue('AL'.$row, $DiscAmtIk);
				$excel->getActiveSheet()->setCellValueExplicit('AM'.$row, $QCSECTI, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('AN'.$row, $QCJOBI, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('AO'.$row, $REMARKH1, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('AP'.$row, $REMARKH2, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('AQ'.$row, $REMARKH3, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('AR'.$row, $REMARKH4, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('AS'.$row, $REMARKH5, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('AT'.$row, $REMARKH6, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('AU'.$row, $REMARKH7, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('AV'.$row, $REMARKH8, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('AW'.$row, $REMARKH9, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('AX'.$row, $REMARKH10, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('AY'.$row, $REMARKI1, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('AZ'.$row, $REMARKI2, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('BA'.$row, $REMARKI3, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('BB'.$row, $REMARKI4, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('BC'.$row, $REMARKI5, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('BD'.$row, $REMARKI6, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('BE'.$row, $REMARKI7, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('BF'.$row, $REMARKI8, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('BG'.$row, $REMARKI9, PHPExcel_Cell_DataType::TYPE_STRING);
				$excel->getActiveSheet()->setCellValueExplicit('BH'.$row, $REMARKI10, PHPExcel_Cell_DataType::TYPE_STRING);		
				$row++;
				
				
	
			}		
		}
		
		$file_name = $path."BI-".date('YmdHis').".xls";
		//header('Content-Type: application/vnd.ms-excel'); /// form excel5 xls
		//header('Content-Disposition: attachment;filename="'.$file_name.'"');
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