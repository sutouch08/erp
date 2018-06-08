<?php
$sc = TRUE;
$customer = new customer();

$allLender = $_GET['allLender'];
$lender = $_GET['lender'];

$allProduct = $_GET['allProduct'];
$pdFrom = $_GET['pdFrom'];
$pdTo = $_GET['pdTo'];

$from = fromDate($_GET['fromDate']);
$to = toDate($_GET['toDate']);

$qr  = "SELECT cus.name, od.reference, pd.code, ld.qty, ld.received, pd.cost ";
$qr .= "FROM tbl_order_lend_detail AS ld ";
$qr .= "JOIN tbl_order AS od ON ld.id_order = od.id ";
$qr .= "JOIN tbl_product AS pd ON ld.id_product = pd.id ";
$qr .= "JOIN tbl_customer AS cus ON od.id_customer = cus.id ";
$qr .= "WHERE ld.received < ld.qty ";
$qr .= "AND od.date_add >= '".$from."' ";
$qr .= "AND od.date_add <= '".$to."' ";

if($allLender == 0)
{
  $qr .= "AND cus.id = '".$lender."' ";
}

if($allProduct == 0)
{
  $qr .= "AND pd.code >= '".$pdFrom."' ";
  $qr .= "AND pd.code <= '".$pdTo."' ";
}

$qr .= "ORDER BY cus.name ASC, pd.code ASC";

$qs = dbQuery($qr);

$lendTitle = $allLender == 1 ? 'ทั้งหมด' : $customer->getName($lender);
$pdTitle = $allProduct == 1 ? 'ทั้งหมด' : '( '.$pdFrom.' ) - ( '.$pdTo.' )';
$timeTitle = 'วันที่ : '.thaiDate($from, '/').' - '.thaiDate($to, '/');

$excel = new PHPExcel();
$excel->setActiveSheetIndex(0);
$excel->getActiveSheet()->setTitle('Lend Not Return');

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);

$excel->getActiveSheet()->setCellValue('A1', 'รายงานการยืมสินค้ายังไม่ส่งคืน ณ วันที่ '.date('d/m/Y'));
$excel->getActiveSheet()->mergeCells('A1:I1');
$excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal('center');

$excel->getActiveSheet()->setCellValue('A2', 'ผู้ยืม : '.$lendTitle);
$excel->getActiveSheet()->mergeCells('A2:I2');

$excel->getActiveSheet()->setCellValue('A3', 'สินค้า : '.$pdTitle);
$excel->getActiveSheet()->mergeCells('A3:I3');

$excel->getActiveSheet()->setCellValue('A4', $timeTitle);
$excel->getActiveSheet()->mergeCells('A4:I4');

$excel->getActiveSheet()->setCellValue('A5', 'ลำดับ');
$excel->getActiveSheet()->setCellValue('B5', 'ผู้ยืม');
$excel->getActiveSheet()->setCellValue('C5', 'เลขที่เอกสาร');
$excel->getActiveSheet()->setCellValue('D5', 'รหัสสินค้า');
$excel->getActiveSheet()->setCellValue('E5', 'ยืม');
$excel->getActiveSheet()->setCellValue('F5', 'คืน');
$excel->getActiveSheet()->setCellValue('G5', 'คงเหลือ');
$excel->getActiveSheet()->setCellValue('H5', 'ต้นทุน');
$excel->getActiveSheet()->setCellValue('I5', 'มูลค่า');

$excel->getActiveSheet()->getStyle('A5:I5')->getAlignment()->setHorizontal('center');

$row = 6;

$no = 1;
while($rs = dbFetchObject($qs))
{
  $excel->getActiveSheet()->setCellValue('A'.$row, $no);
  $excel->getActiveSheet()->setCellValue('B'.$row, $rs->name);
  $excel->getActiveSheet()->setCellValue('C'.$row, $rs->reference);
  $excel->getActiveSheet()->setCellValue('D'.$row, $rs->code);
  $excel->getActiveSheet()->setCellValue('E'.$row, $rs->qty);
  $excel->getActiveSheet()->setCellValue('F'.$row, $rs->received);
  $excel->getActiveSheet()->setCellValue('G'.$row, '=E'.$row.' - F'.$row);
  $excel->getActiveSheet()->setCellValue('H'.$row, $rs->cost);
  $excel->getActiveSheet()->setCellValue('I'.$row, '=G'.$row.' * H'.$row);
  $no++;
  $row++;
}

$excel->getActiveSheet()->setCellValue('A'.$row, 'รวม');
$excel->getActiveSheet()->mergeCells('A'.$row.':D'.$row);
$excel->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setHorizontal('right');

$ro = $row - 1;
$excel->getActiveSheet()->setCellValue('E'.$row, '=SUM(E6:E'.$ro.')');
$excel->getActiveSheet()->setCellValue('F'.$row, '=SUM(F6:F'.$ro.')');
$excel->getActiveSheet()->setCellValue('G'.$row, '=SUM(G6:F'.$ro.')');
$excel->getActiveSheet()->setCellValue('I'.$row, '=SUM(I6:I'.$ro.')');

$excel->getActiveSheet()->getStyle('E6:E'.$row)->getNumberFormat()->setFormatCode('#,##0');
$excel->getActiveSheet()->getStyle('F6:F'.$row)->getNumberFormat()->setFormatCode('#,##0');
$excel->getActiveSheet()->getStyle('G6:G'.$row)->getNumberFormat()->setFormatCode('#,##0');
$excel->getActiveSheet()->getStyle('I6:I'.$row)->getNumberFormat()->setFormatCode('#,##0.00');


setToken($_GET['token']);
$file_name = "Lend_not_return.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
header('Content-Disposition: attachment;filename="'.$file_name.'"');
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');
 ?>
