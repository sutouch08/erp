<?php
$allProduct = $_GET['allProduct'];
$allZone    = $_GET['allZone'];
$pdFrom     = $_GET['pdFrom'];
$pdTo       = $_GET['pdTo'];
$id_wh      = $_GET['id_warehouse'];
$id_zone    = $_GET['id_zone'];
$wh         = new warehouse();

$qr  = "SELECT z.zone_name, p.code, p.name, p.cost, s.qty ";
$qr .= "FROM tbl_stock AS s ";
$qr .= "LEFT JOIN tbl_zone AS z ON s.id_zone = z.id_zone ";
$qr .= "LEFT JOIN tbl_product AS p ON s.id_product = p.id ";
$qr .= "LEFT JOIN tbl_product_style AS ps ON p.id_style = ps.id ";
$qr .= "WHERE s.id_zone != '' ";

if($allProduct != 1)
{
  $qr .= "AND ps.code >= '".$pdFrom."' ";
  $qr .= "AND ps.code <= '".$pdTo."' ";
}

if($allZone != 1 && $id_zone != '')
{
  $qr .= "AND s.id_zone = '".$id_zone."' ";
}

if($id_wh != '' && $allZone == 1)
{
  $qr .= "AND id_warehouse = '".$id_wh."' ";
}

$qr .= "ORDER BY z.zone_name ASC, ps.code ASC";

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
$excel->getProperties()->setCategory("Stock Report");
$excel->setActiveSheetIndex(0);
$excel->getActiveSheet()->setTitle('สินค้าคงเหลือแยกตามโซน');

//------- Report name Row 1
$excel->getActiveSheet()->setCellValue('A1', 'รายงานสินค้าคงเหลือแยกตามโซน ณ วันที่ ' . date('d/m/Y'));
$excel->getActiveSheet()->mergeCells('A1:G1');

//-------- Report Conditions Row 2
$excel->getActiveSheet()->setCellValue('A2', 'สินค้า : '.($allProduct == 1 ? 'ทั้งหมด' : $pdFrom.' - '.$pdTo));
$excel->getActiveSheet()->mergeCells('A2:G2');

$excel->getActiveSheet()->setCellValue('A3', 'คลังสินค้า : '. ($id_wh == '' ? 'ทั้งหมด' : $wh->getName($id_wh)));
$excel->getActiveSheet()->mergeCells('A3:G3');

//--------- Report Table header
$excel->getActiveSheet()->setCellValue('A4', 'ลำดับ');
$excel->getActiveSheet()->setCellValue('B4', 'ชื่อโซน');
$excel->getActiveSheet()->setCellValue('C4', 'รหัสสินค้า');
$excel->getActiveSheet()->setCellValue('D4', 'ชื่อสินค้า');
$excel->getActiveSheet()->setCellValue('E4', 'ทุนมาตรฐาน');
$excel->getActiveSheet()->setCellValue('F4', 'คงเหลือ');
$excel->getActiveSheet()->setCellValue('G4', 'มูลค่า');

//-------------  Set Column Width
$excel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(16);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);

$excel->getActiveSheet()->getStyle('A4:G4')->getAlignment()->setHorizontal('center');


$row = 5;

if(dbNumRows($qs) > 0)
{
  $no = 1;
  while($rs = dbFetchObject($qs))
  {
    $excel->getActiveSheet()->setCellValue('A'.$row, $no);
    $excel->getActiveSheet()->setCellValue('B'.$row, $rs->zone_name);
    $excel->getActiveSheet()->setCellValue('C'.$row, $rs->code);
    $excel->getActiveSheet()->setCellValue('D'.$row, $rs->name);
    $excel->getActiveSheet()->setCellValue('E'.$row, $rs->cost);
    $excel->getActiveSheet()->setCellValue('F'.$row, $rs->qty);
    $excel->getActiveSheet()->setCellValue('G'.$row, '=E'.$row.'*F'.$row);
    $no++;
    $row++;
  }

  $excel->getActiveSheet()->setCellValue('A'.$row, 'รวม');
  $excel->getActiveSheet()->mergeCells('A'.$row.':E'.$row);
  $excel->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setHorizontal('right');
  $excel->getActiveSheet()->setCellValue('F'.$row, '=SUM(F5:F'.($row-1).')');
  $excel->getActiveSheet()->getStyle('F5:F'.$row)->getAlignment()->setHorizontal('right');
  $excel->getActiveSheet()->setCellValue('G'.$row, '=SUM(G5:G'.($row-1).')');
  $excel->getActiveSheet()->getStyle('G5:G'.$row)->getAlignment()->setHorizontal('right');

}

setToken($_GET['token']);
$file_name = "Stock Balance.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
header('Content-Disposition: attachment;filename="'.$file_name.'"');
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');



 ?>
