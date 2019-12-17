<?php
$cusFrom = $_GET['cusFrom'];
$cusTo   = $_GET['cusTo'];
$docFrom   = $_GET['docFrom'];
$docTo     = $_GET['docTo'];
$allDocument = $_GET['allDocument'];
$fromDate = fromDate($_GET['fromDate']);
$toDate   = toDate($_GET['toDate']);
$limit    = 1000;
$sc = TRUE;

$qr  = "SELECT customer_code, customer_name, reference, ";
$qr .= "SUM(qty) AS qty, SUM(total_amount_inc) AS amount ";
$qr .= "FROM tbl_order_sold ";
$qr .= "WHERE id_role = 2 ";
$qr .= "AND customer_code >= '".$cusFrom."' ";
$qr .= "AND customer_code <= '".$cusTo."' ";

if($allDocument == 0)
{
  $qr .= "AND reference >= '{$docFrom}' AND reference <= '{$docTo}'";
}

$qr .= "AND date_add >= '".$fromDate."' ";
$qr .= "AND date_add <= '".$toDate."' ";
$qr .= "GROUP BY reference";

//echo $qr;

$qs = dbQuery($qr);

//-------
$excel = new PHPExcel();
$excel->getProperties()->setCreator("Samart Invent 1.0");
$excel->getProperties()->setLastModifiedBy("Samart Invent 1.0");
$excel->getProperties()->setTitle("Report stock balance");
$excel->getProperties()->setSubject("Report stock balance");
$excel->getProperties()->setDescription("This file was generate by Smart invent web application via PHPExcel v.1.8");
$excel->getProperties()->setKeywords("Smart Invent 1.0");
$excel->getProperties()->setCategory("Sales Report");
$excel->setActiveSheetIndex(0);
$excel->getActiveSheet()->setTitle('รายงานฝากขายแยกตามลูกค้า');


//------- Report name Row 1
$excel->getActiveSheet()->setCellValue('A1', 'รายงานเอกสารฝากขายแยกตามลูกค้า(ยอดส่ง) ณ วันที่ ' . thaiDate($fromDate,'/') .' ถึง '.thaiDate($toDate, '/'));
$excel->getActiveSheet()->mergeCells('A1:G1');

//-------- Report Conditions Row 2
$excel->getActiveSheet()->setCellValue('A2', 'ลูกค้า : ('.$cusFrom.' - '.$cusTo.')');
$excel->getActiveSheet()->mergeCells('A2:G2');

$excel->getActiveSheet()->setCellValue('A3', 'เลขที่เอกสาร : '. ($allDocument == 1 ? 'ทั้งหมด' : '('.$docFrom .') - ('.$docTo.')'));
$excel->getActiveSheet()->mergeCells('A3:G3');


//--------- Report Table header
$excel->getActiveSheet()->setCellValue('A5', 'ลำดับ');
$excel->getActiveSheet()->setCellValue('B5', 'ลูกค้า');
$excel->getActiveSheet()->setCellValue('C5', 'เลขที่เอกสาร');
$excel->getActiveSheet()->setCellValue('D5', 'จำนวน');
$excel->getActiveSheet()->setCellValue('E5', 'มูลค่า');

$row = 6;

if(dbNumRows($qs) > 0)
{
  $no = 1;
  while($rs = dbFetchObject($qs))
  {
    $excel->getActiveSheet()->setCellValue('A'.$row, $no);
    $excel->getActiveSheet()->setCellValue('B'.$row, $rs->customer_code.' : '.$rs->customer_name);
    $excel->getActiveSheet()->setCellValue('C'.$row, $rs->reference);
    $excel->getActiveSheet()->setCellValue('D'.$row, $rs->qty);
    $excel->getActiveSheet()->setCellValue('E'.$row, $rs->amount);

    $no++;
    $row++;
  }

  $rd = $row - 1;
  $excel->getActiveSheet()->setCellValue('A'.$row, 'รวม');
  $excel->getActiveSheet()->setCellValue('D'.$row, '=SUM(D6:D'.$rd.')');
  $excel->getActiveSheet()->setCellValue('E'.$row, '=SUM(E6:E'.$rd.')');
  $excel->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setHorizontal('right');
  $excel->getActiveSheet()->mergeCells('A'.$row.':D'.$row);

  $excel->getActiveSheet()->getStyle('D6:D'.$row)->getNumberFormat()->setFormatCode('#,##0');
  $excel->getActiveSheet()->getStyle('E6:E'.$row)->getNumberFormat()->setFormatCode('#,##0.00');



}

setToken($_GET['token']);
$file_name = "รายงานเอกสารฝากขายแยกตามลูกค้า(ยอดส่ง).xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
header('Content-Disposition: attachment;filename="'.$file_name.'"');
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');


 ?>
