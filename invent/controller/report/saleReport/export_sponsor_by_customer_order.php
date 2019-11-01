<?php
ini_set('memory_limit', '1024M');
set_time_limit(600);

$sc = TRUE;
$role = 4; //--- Sponsor
$allCustomer = $_GET['allCustomer'];
$fromCode = $_GET['fromCode']; //--- รหัสลูกค้า
$toCode = $_GET['toCode']; //-- รหัสลูกค้า

$fromDate = fromDate($_GET['fromDate']);
$toDate = toDate($_GET['toDate']);
$year = $_GET['year'];
$ds = array();


$qr  = "SELECT o.reference, o.customer_code, o.customer_name, SUM(o.qty) AS qty, SUM(o.total_amount_inc) AS amount, o.date_add ";
$qr .= "FROM tbl_order_sold AS o ";
$qr .= "LEFT JOIN tbl_sponsor_budget AS b ON o.id_budget = b.id ";
$qr .= "WHERE o.id_role IN(".$role.") ";
if($year != 0)
{
  $qr .= "AND b.year = '{$year}' ";
}

if($allCustomer == 0)
{
  $qr .= "AND customer_code >= '".$fromCode."' ";
  $qr .= "AND customer_code <= '".$toCode."' ";
}


$qr .= "AND date_add >= '".$fromDate."' ";
$qr .= "AND date_add <= '".$toDate."' ";

$qr .= "GROUP BY reference ";

$qr .= "ORDER BY customer_code ASC, reference ASC";



$qs = dbQuery($qr);

//-------
$excel = new PHPExcel();
$excel->getProperties()->setCreator("Samart Invent 2.0");
$excel->getProperties()->setDescription("This file was generate by Smart invent web application via PHPExcel v.1.8");
$excel->setActiveSheetIndex(0);
$excel->getActiveSheet()->setTitle('Sale By Customer');


//------- Report name Row 1
$excel->getActiveSheet()->setCellValue('A1', 'รายงานสรุปยอดสปอนเซอร์แยกตามผู้รับ แสดงเลขที่เอกสาร ณ วันที่ ' . thaiDate($fromDate,'/') .' ถึง '.thaiDate($toDate, '/'));
$excel->getActiveSheet()->mergeCells('A1:G1');

$excel->getActiveSheet()->setCellValue('A2', 'ผู้รับ : '. ($allCustomer == 1 ? 'ทั้งหมด' : '('.$fromCode .') - ('.$toCode.')'));
$excel->getActiveSheet()->mergeCells('A2:G2');

$excel->getActiveSheet()->setCellValue('A3', 'วันที่เอกสาร : '. '('.thaiDate($fromDate,'/') .') - ('.thaiDate($toDate,'/').')');
$excel->getActiveSheet()->mergeCells('A3:G3');

$excel->getActiveSheet()->setCellValue('A4', 'ปีงบประมาณ : '. ($year == 0 ? 'ทั้งหมด' : $year));
$excel->getActiveSheet()->mergeCells('A4:G4');


//--------- Report Table header
$excel->getActiveSheet()->setCellValue('A5', 'ลำดับ');
$excel->getActiveSheet()->setCellValue('B5', 'วันที่');
$excel->getActiveSheet()->setCellValue('C5', 'เอกสาร');
$excel->getActiveSheet()->setCellValue('D5', 'รหัส');
$excel->getActiveSheet()->setCellValue('E5', 'ผู้รับ');
$excel->getActiveSheet()->setCellValue('F5', 'จำนวน');
$excel->getActiveSheet()->setCellValue('G5', 'มูลค่า(รวม VAT)');


$row = 6;

if(dbNumRows($qs) > 0)
{
  $no = 1;
  while($rs = dbFetchObject($qs))
  {
    $y		= date('Y', strtotime($rs->date_add));
    $m		= date('m', strtotime($rs->date_add));
    $d		= date('d', strtotime($rs->date_add));
    $date = PHPExcel_Shared_Date::FormattedPHPToExcel($y, $m, $d);

    $excel->getActiveSheet()->setCellValue('A'.$row, $no);
    $excel->getActiveSheet()->setCellValue('B'.$row, $date);
    $excel->getActiveSheet()->setCellValue('C'.$row, $rs->reference);
    $excel->getActiveSheet()->setCellValue('D'.$row, $rs->customer_code);
    $excel->getActiveSheet()->setCellValue('E'.$row, $rs->customer_name);
    $excel->getActiveSheet()->setCellValue('F'.$row, $rs->qty);
    $excel->getActiveSheet()->setCellValue('G'.$row, $rs->amount);
    $row++;
    $no++;
  }

  $ro = $row -1;
  $excel->getActiveSheet()->setCellValue('A'.$row, 'รวม');
  $excel->getActiveSheet()->mergeCells('A'.$row.':E'.$row);
  $excel->getActiveSheet()->setCellValue('F'.$row, '=SUM(F5:F'.$ro.')');
  $excel->getActiveSheet()->setCellValue('G'.$row, '=SUM(G5:G'.$ro.')');


  $excel->getActiveSheet()->getStyle('B5:B'.$ro)->getNumberFormat()->setFormatCode('dd/mm/yyyy');
  $excel->getActiveSheet()->getStyle('F5:F'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
  $excel->getActiveSheet()->getStyle('G5:G'.$row)->getNumberFormat()->setFormatCode('#,##0');

}

//--- New Sheet
$excel->createSheet(1);
$excel->setActiveSheetIndex(1);
$excel->getActiveSheet()->setTitle('ยังไม่เปิดบิล');

$qr  = "SELECT o.reference, c.code, c.name, SUM(od.qty) AS qty, SUM(od.total_amount) AS amount, o.date_add ";
$qr .= "FROM tbl_order AS o ";
$qr .= "LEFT JOIN tbl_customer AS c ON o.id_customer = c.id ";
$qr .= "LEFT JOIN tbl_sponsor_budget AS b ON o.id_budget = b.id ";
$qr .= "LEFT JOIN tbl_order_detail AS od ON o.id = od.id_order ";
$qr .= "WHERE o.role = {$role} ";
$qr .= "AND o.state < 8 ";
$qr .= "AND o.isExpire = 0 ";
$qr .= "AND o.status = 1 ";
$qr .= "AND o.isCancle = 0 ";

if($year != 0)
{
  $qr .= "AND b.year = '{$year}' ";
}

if($allCustomer == 0)
{
  $qr .= "AND c.code >= '".$fromCode."' ";
  $qr .= "AND c.code <= '".$toCode."' ";
}


$qr .= "AND o.date_add >= '".$fromDate."' ";
$qr .= "AND o.date_add <= '".$toDate."' ";

$qr .= "GROUP BY o.reference ";

$qr .= "ORDER BY c.code ASC, o.reference ASC";

$qs = dbQuery($qr);

//------- Report name Row 1
$excel->getActiveSheet()->setCellValue('A1', 'รายงานสรุปยอดสปอนเซอร์แยกตามผู้รับ(ยังไม่เปิดบิล) แสดงเลขที่เอกสาร ณ วันที่ ' . thaiDate($fromDate,'/') .' ถึง '.thaiDate($toDate, '/'));
$excel->getActiveSheet()->mergeCells('A1:G1');

$excel->getActiveSheet()->setCellValue('A2', 'ผู้รับ : '. ($allCustomer == 1 ? 'ทั้งหมด' : '('.$fromCode .') - ('.$toCode.')'));
$excel->getActiveSheet()->mergeCells('A2:G2');

$excel->getActiveSheet()->setCellValue('A3', 'วันที่เอกสาร : '. '('.thaiDate($fromDate,'/') .') - ('.thaiDate($toDate,'/').')');
$excel->getActiveSheet()->mergeCells('A3:G3');

$excel->getActiveSheet()->setCellValue('A4', 'ปีงบประมาณ : '. ($year == 0 ? 'ทั้งหมด' : $year));
$excel->getActiveSheet()->mergeCells('A4:G4');


//--------- Report Table header
$excel->getActiveSheet()->setCellValue('A5', 'ลำดับ');
$excel->getActiveSheet()->setCellValue('B5', 'วันที่');
$excel->getActiveSheet()->setCellValue('C5', 'เอกสาร');
$excel->getActiveSheet()->setCellValue('D5', 'รหัส');
$excel->getActiveSheet()->setCellValue('E5', 'ผู้รับ');
$excel->getActiveSheet()->setCellValue('F5', 'จำนวน');
$excel->getActiveSheet()->setCellValue('G5', 'มูลค่า(รวม VAT)');


$row = 6;

if(dbNumRows($qs) > 0)
{
  $no = 1;
  while($rs = dbFetchObject($qs))
  {
    $y		= date('Y', strtotime($rs->date_add));
    $m		= date('m', strtotime($rs->date_add));
    $d		= date('d', strtotime($rs->date_add));
    $date = PHPExcel_Shared_Date::FormattedPHPToExcel($y, $m, $d);

    $excel->getActiveSheet()->setCellValue('A'.$row, $no);
    $excel->getActiveSheet()->setCellValue('B'.$row, $date);
    $excel->getActiveSheet()->setCellValue('C'.$row, $rs->reference);
    $excel->getActiveSheet()->setCellValue('D'.$row, $rs->code);
    $excel->getActiveSheet()->setCellValue('E'.$row, $rs->name);
    $excel->getActiveSheet()->setCellValue('F'.$row, $rs->qty);
    $excel->getActiveSheet()->setCellValue('G'.$row, $rs->amount);
    $row++;
    $no++;
  }

  $ro = $row -1;
  $excel->getActiveSheet()->setCellValue('A'.$row, 'รวม');
  $excel->getActiveSheet()->mergeCells('A'.$row.':E'.$row);
  $excel->getActiveSheet()->setCellValue('F'.$row, '=SUM(F5:F'.$ro.')');
  $excel->getActiveSheet()->setCellValue('G'.$row, '=SUM(G5:G'.$ro.')');


  $excel->getActiveSheet()->getStyle('B5:B'.$ro)->getNumberFormat()->setFormatCode('dd/mm/yyyy');
  $excel->getActiveSheet()->getStyle('F5:F'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
  $excel->getActiveSheet()->getStyle('G5:G'.$row)->getNumberFormat()->setFormatCode('#,##0');

}

$excel->setActiveSheetIndex(0);

setToken($_GET['token']);
$file_name = "SponsorByReference.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
header('Content-Disposition: attachment;filename="'.$file_name.'"');
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');


 ?>
