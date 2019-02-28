<?php
ini_set('memory_limit', '1024M');
set_time_limit(600);
//--- รายงานสินค้าคงเหลือ ณ ปัจจุบัน แสดงเป็นรายการ
$sc = array();
$allProduct   = $_GET['allProduct'];
$allWarehouse = $_GET['allWhouse'];
$pdFrom       = $_GET['pdFrom'];
$pdTo         = $_GET['pdTo'];
$styleFrom    = $_GET['styleFrom'];
$styleTo      = $_GET['styleTo'];
$showItem     = $_GET['showItem'];
$whList       = $allWarehouse == 1 ? FALSE : $_GET['warehouse'];
$wh           = new warehouse();
$order        = new order();
$wh_in        = "";
$wh_list      = "";

if($allWarehouse != 1)
{
  $i = 1;
  foreach($whList as $id_wh)
  {
    $wh_in .= $i == 1 ? "'".$id_wh."'" : ", '".$id_wh."'";
    $wh_list .= $i == 1 ? $wh->getCode($id_wh) : ", ".$wh->getCode($id_wh);
    $i++;
  }
}


//---  Report title
$rpDate  = thaiDate(date('Y-m-d'), '/');
$rpTitle = 'รายงานสินค้าคงเหลือ ณ วันที่ '.$rpDate.' (หักยอดจอง)';
$whTitle = $allWarehouse == 1 ? 'ทั้งหมด' : $wh_list;
$pdTitle = $allProduct == 1 ? 'ทั้งหมด' : ($showItem == 1 ? '('.$pdFrom.') - ('.$pdTo.')' : '('.$styleFrom.') - ('.$styleTo.')');


$qr  = "SELECT s.id_product, p.code, p.name, p.cost, SUM(s.qty) AS qty ";
$qr .= "FROM tbl_stock AS s ";
$qr .= "JOIN tbl_zone AS z ON s.id_zone = z.id_zone ";
$qr .= "JOIN tbl_warehouse AS wh ON z.id_warehouse = wh.id ";
$qr .= "JOIN tbl_product AS p ON s.id_product = p.id ";
$qr .= "LEFT JOIN tbl_product_style AS ps ON p.id_style = ps.id ";
$qr .= "LEFT JOIN tbl_color AS co ON p.id_color = co.id ";
$qr .= "LEFT JOIN tbl_size AS si ON p.id_size = si.id ";
$qr .= "WHERE s.id_zone != '' ";
$qr .= "AND wh.role IN(1,3,4,5,6) ";

if($allProduct != 1)
{
  if($showItem == 1)
  {
    $qr .= "AND p.code >= '".$pdFrom."' ";
    $qr .= "AND p.code <= '".$pdTo."' ";
  }

  if($showItem == 0)
  {
    $qr .= "AND ps.code >= '".$styleFrom."' ";
    $qr .= "AND ps.code <= '".$styleTo."' ";
  }
}

if($allWarehouse != 1)
{
  $qr .= "AND z.id_warehouse IN(".$wh_in.") ";
}

$qr .= "GROUP BY p.id ";

$qr .= "ORDER BY ps.code ASC, co.code ASC, si.position ASC";

//echo $qr;

$qs = dbQuery($qr);

$excel = new PHPExcel();
$excel->setActiveSheetIndex(0);
$excel->getActiveSheet()->setTitle('Stock Balance');

$excel->getActiveSheet()->setCellValue('A1', $rpTitle);
$excel->getActiveSheet()->mergeCells('A1:F1');

$excel->getActiveSheet()->setCellValue('A2', 'คลัง : '.$whTitle);
$excel->getActiveSheet()->mergeCells('A2:F2');

$excel->getActiveSheet()->setCellValue('A3', 'สินค้า : '.$pdTitle);
$excel->getActiveSheet()->mergeCells('A3:F3');


$excel->getActiveSheet()->getColumnDimension('A')->setWidth(7);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);

$row = 4;

$excel->getActiveSheet()->setCellValue('A'.$row, 'ลำดับ');
$excel->getActiveSheet()->setCellValue('B'.$row, 'รหัส');
$excel->getActiveSheet()->setCellValue('C'.$row, 'สินค้า');
$excel->getActiveSheet()->setCellValue('D'.$row, 'ทุน');
$excel->getActiveSheet()->setCellValue('E'.$row, 'คงเหลือ');
$excel->getActiveSheet()->setCellValue('F'.$row, 'มูลค่า');
$excel->getActiveSheet()->getStyle('A'.$row.':F'.$row)->getAlignment()->setHorizontal('center');
$row++;

if(dbNumRows($qs) > 0)
{
  $no = 1;

  while($rs = dbFetchObject($qs))
  {
    $balance = $rs->qty - $order->getReservQty($rs->id_product);
    $excel->getActiveSheet()->setCellValue('A'.$row, $no);
    $excel->getActiveSheet()->setCellValue('B'.$row, $rs->code);
    $excel->getActiveSheet()->setCellValue('C'.$row, $rs->name);
    $excel->getActiveSheet()->setCellValue('D'.$row, $rs->cost);
    $excel->getActiveSheet()->setCellValue('E'.$row, $balance);
    $excel->getActiveSheet()->setCellValue('F'.$row, '=D'.$row.'*E'.$row);
    $no++;
    $row++;
  }

  $pRow = $row -1;
  $excel->getActiveSheet()->setCellValue('A'.$row, 'รวม');
  $excel->getActiveSheet()->mergeCells('A'.$row.':D'.$row);
  $excel->getActiveSheet()->setCellValue('E'.$row, '=SUM(E5:E'.$pRow.')');
  $excel->getActiveSheet()->setCellValue('F'.$row, '=SUM(F5:F'.$pRow.')');

  $excel->getActiveSheet()->getStyle('A5:A'.$pRow)->getAlignment()->setHorizontal('center');
  $excel->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setHorizontal('right');
  $excel->getActiveSheet()->getStyle('A5:A'.$pRow)->getNumberFormat()->setFormatCode('#,##0');
  $excel->getActiveSheet()->getStyle('D5:D'.$pRow)->getNumberFormat()->setFormatCode('#,##0.00');
  $excel->getActiveSheet()->getStyle('E5:E'.$row)->getNumberFormat()->setFormatCode('#,##0');
  $excel->getActiveSheet()->getStyle('F5:F'.$row)->getNumberFormat()->setFormatCode('#,##0.00');

}

setToken($_GET['token']);
$file_name = "Stock Balance.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
header('Content-Disposition: attachment;filename="'.$file_name.'"');
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');

 ?>
