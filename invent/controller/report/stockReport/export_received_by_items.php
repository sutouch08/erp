<?php
$isAll = $_GET['allItems'];
$fCode = $_GET['from_code'];
$tCode = $_GET['to_code'];

$fromCode = $fCode > $tCode ? $tCode : $fCode;
$toCode = $fCode > $tCode ? $fCode : $tCode;

$from = fromDate($_GET['fromDate']);
$to = toDate($_GET['toDate']);
$sc = array();

$date_range = thaiDate($_GET['fromDate'], '/').'  -  '.thaiDate($_GET['toDate'], '/');
$ref_range = $isAll == 1 ? 'ทั้งหมด' : $fromCode.'  -  '.$toCode;


$qr  = "SELECT rp.reference, pd.code, pd.name, z.barcode_zone, z.zone_name, rp.date_add, rd.qty AS qty, (rd.qty * rd.cost) AS amount ";
$qr .= "FROM tbl_receive_product_detail AS rd ";
$qr .= "LEFT JOIN tbl_receive_product AS rp ON rd.id_receive_product = rp.id ";
$qr .= "LEFT JOIN tbl_product AS pd ON rd.id_product = pd.id ";
$qr .= "LEFT JOIN tbl_color AS co ON pd.id_color = co.id ";
$qr .= "LEFT JOIN tbl_size AS si ON pd.id_size = si.id ";
$qr .= "LEFT JOIN tbl_zone AS z ON rd.id_zone = z.id_zone ";
$qr .= "WHERE rp.date_add >= '".$from."' AND rp.date_add <= '".$to."' ";
$qr .= "AND rp.isCancle = 0 AND rd.is_cancle = 0 ";

if($isAll == 0)
{
  $qr .= "AND pd.code >= '".$fromCode."' AND pd.code <= '".$toCode."' ";
}

$qr .= "ORDER BY rp.reference, pd.id_style, co.code, si.position ASC";

//echo $qr;

$qs = dbQuery($qr);



//---  Report title
$report_title = 'รายงานการรับสินค้าแยกตามรายการสินค้า  วันที่ '.$date_range.'      (  วันที่พิมพ์รายงาน : '.date('d/m/Y').'  เวลา : '.date('H:i:s').' )';;
$ref_title     = 'รายการสินค้า :  '. $ref_range;


$excel = new PHPExcel();
$excel->getProperties()->setCreator("Samart Invent 1.0");
$excel->getProperties()->setLastModifiedBy("Samart Invent 1.0");
$excel->getProperties()->setTitle("Report stock balance");
$excel->getProperties()->setSubject("Report stock balance");
$excel->getProperties()->setDescription("This file was generate by Smart invent web application via PHPExcel v.1.8");
$excel->getProperties()->setKeywords("Smart Invent 1.0");
$excel->getProperties()->setCategory("Stock Report");
$excel->setActiveSheetIndex(0);
$excel->getActiveSheet()->setTitle('Receive By Items');

//------- Report name Row 1
$excel->getActiveSheet()->setCellValue('A1', $report_title);
$excel->getActiveSheet()->mergeCells('A1:F1');

//-------- Report Conditions Row 2
$excel->getActiveSheet()->setCellValue('A2', $ref_title);
$excel->getActiveSheet()->mergeCells('A2:F2');


//--------- Report Table header
$excel->getActiveSheet()->setCellValue('A3', 'ลำดับ');
$excel->getActiveSheet()->setCellValue('B3', 'วันที่');
$excel->getActiveSheet()->setCellValue('C3', 'เลขที่เอกสาร');
$excel->getActiveSheet()->setCellValue('D3', 'รหัส');
$excel->getActiveSheet()->setCellValue('E3', 'สินค้า');
$excel->getActiveSheet()->setCellValue('F3', 'จำนวน');
$excel->getActiveSheet()->setCellValue('G3', 'มูลค่า');
$excel->getActiveSheet()->setCellValue('H3', 'รหัสโซน');
$excel->getActiveSheet()->setCellValue('I3', 'ชื่อโซน');


//-------------  Set Column Width
$excel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(16);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(40);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(30);

$excel->getActiveSheet()->getStyle('A3:I3')->getAlignment()->setHorizontal('center');

$row = 4;

if(dbNumRows($qs) > 0)
{
  $no = 1;

  while($rs = dbFetchObject($qs))
  {

    $excel->getActiveSheet()->setCellValue('A'.$row, $no);
    $excel->getActiveSheet()->setCellValue('B'.$row, thaiDate($rs->date_add));
    $excel->getActiveSheet()->setCellValue('C'.$row, $rs->reference);
    $excel->getActiveSheet()->setCellValue('D'.$row, $rs->code);
    $excel->getActiveSheet()->setCellValue('E'.$row, $rs->name);
    $excel->getActiveSheet()->setCellValue('F'.$row, $rs->qty);
    $excel->getActiveSheet()->setCellValue('G'.$row, $rs->amount);
    $excel->getActiveSheet()->setCellValue('H'.$row, $rs->barcode_zone);
    $excel->getActiveSheet()->setCellValue('I'.$row, $rs->zone_name);

    $no++;
    $row++;

  }

  $rx = $row - 1;
  $excel->getActiveSheet()->setCellValue('A'.$row, 'รวม');
  $excel->getActiveSheet()->mergeCells('A'.$row.':E'.$row);
  $excel->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setHorizontal('right');
  $excel->getActiveSheet()->setCellValue('F'.$row, '=SUM(F4:F'.$rx.')');
  $excel->getActiveSheet()->setCellValue('G'.$row, '=SUM(G4:G'.$rx.')');

  $excel->getActiveSheet()->getStyle('F4:F'.$row)->getNumberFormat()->setFormatCode('#,##0');
  $excel->getActiveSheet()->getStyle('G4:G'.$row)->getNumberFormat()->setFormatCode('#,##0.00');

}


setToken($_GET['token']);
$file_name = "Received_by_items.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
header('Content-Disposition: attachment;filename="'.$file_name.'"');
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');
 ?>
