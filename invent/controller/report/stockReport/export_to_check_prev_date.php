<?php

$id_zone    = $_GET['id_zone'];
$prevDate   = $_GET['prevDate'];
$selectDate = $_GET['selectDate'];

$qr  = "SELECT b.barcode, p.code, (SUM(s.move_in) - SUM(s.move_out)) AS qty ";
$qr .= "FROM tbl_stock_movement AS s ";
$qr .= "LEFT JOIN tbl_product AS p ON s.id_product = p.id ";
$qr .= "LEFT JOIN tbl_barcode AS b ON s.id_product = b.id_product ";
$qr .= "WHERE s.id_zone = '".$id_zone."' ";
$qr .= "AND s.date_upd <= '".toDate($selectDate)."' ";
$qr .= "GROUP BY p.id";

//echo $qr;

$qs = dbQuery($qr);

$excel = new PHPExcel();
$excel->getProperties()->setCreator("Samart Invent 1.0");
$excel->getProperties()->setLastModifiedBy("Samart Invent 1.0");
$excel->getProperties()->setTitle("Report stock balance");
$excel->getProperties()->setSubject("Report stock balance");
$excel->getProperties()->setDescription("This file was generate by Smart invent web application via PHPExcel v.1.8");
$excel->getProperties()->setKeywords("Smart Invent 1.0");
$excel->getProperties()->setCategory("Stock Report");
$excel->setActiveSheetIndex(0);
$excel->getActiveSheet()->setTitle('stock');

$row = 1;

$excel->getActiveSheet()->setCellValue('A'.$row, 'barcode');
$excel->getActiveSheet()->setCellValue('B'.$row, 'item_code');
$excel->getActiveSheet()->setCellValue('C'.$row, 'qty');

$row++;

//-------------  Set Column Width
$excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);


if(dbNumRows($qs) > 0)
{
  while($rs = dbFetchObject($qs))
  {
    if($rs->qty != 0)
    {
      $excel->getActiveSheet()->setCellValue('A'.$row, $rs->barcode);
      $excel->getActiveSheet()->setCellValue('B'.$row, $rs->code);
      $excel->getActiveSheet()->setCellValue('C'.$row, $rs->qty);
      $row++;
    }

  }

}

//print_r($excel);

setToken($_GET['token']);
$file_name = "Stock Balance.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
header('Content-Disposition: attachment;filename="'.$file_name.'"');
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');


 ?>
