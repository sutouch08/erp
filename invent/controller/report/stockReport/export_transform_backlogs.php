<?php
$sc = TRUE;

$qr  = "SELECT od.id_employee AS id, CONCAT(emp.first_name, ' ', emp.last_name) AS name, od.reference, pd.code, td.sold_qty, td.received ";
$qr .= "FROM tbl_order_transform_detail AS td ";
$qr .= "LEFT JOIN tbl_product AS pd ON td.id_product = pd.id ";
$qr .= "LEFT JOIN tbl_order AS od ON td.id_order = od.id ";
$qr .= "LEFT JOIN tbl_employee AS emp ON od.id_employee = emp.id_employee ";
$qr .= "WHERE ";
$qr .= "td.received < td.sold_qty ";
$qr .= "ORDER BY od.id_employee ASC";

$qs = dbQuery($qr);

$excel = new PHPExcel();
$excel->setActiveSheetIndex(0);
$excel->getActiveSheet()->setTitle('WQ backlogs');

$excel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);


$excel->getActiveSheet()->setCellValue('A1', 'รายงานการสินค้าแปรสภาพค้างรับ ณ วันที่ '.date('d/m/Y'));
$excel->getActiveSheet()->mergeCells('A1:K1');
$excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal('center');

$excel->getActiveSheet()->setCellValue('A2', 'ลำดับ');
$excel->getActiveSheet()->setCellValue('B2', 'ผู้เบิก');
$excel->getActiveSheet()->setCellValue('C2', 'เลขที่เอกสาร');
$excel->getActiveSheet()->setCellValue('D2', 'รหัสสินค้า');
$excel->getActiveSheet()->setCellValue('E2', 'จำนวนเบิก');
$excel->getActiveSheet()->setCellValue('F2', 'จำนวนรับ');
$excel->getActiveSheet()->setCellValue('G2', 'ค้างรับ');

$excel->getActiveSheet()->getStyle('A2:G2')->getAlignment()->setHorizontal('center');

$row = 3;

$no = 1;
$emp = array();
while($rs = dbFetchObject($qs))
{
  $excel->getActiveSheet()->setCellValue('A'.$row, $no);
  $excel->getActiveSheet()->setCellValue('B'.$row, $rs->name);
  $excel->getActiveSheet()->setCellValue('C'.$row, $rs->reference);
  $excel->getActiveSheet()->setCellValue('D'.$row, $rs->code);
  $excel->getActiveSheet()->setCellValue('E'.$row, $rs->sold_qty);
  $excel->getActiveSheet()->setCellValue('F'.$row, $rs->received);
  $excel->getActiveSheet()->setCellValue('G'.$row, '=E'.$row.'-F'.$row);

  if(isset($emp[$rs->id]))
  {
    $emp[$rs->id]['qty'] += ($rs->sold_qty - $rs->received);
  }
  else
  {
    $emp[$rs->id] = array('name' => $rs->name, 'qty' =>($rs->sold_qty - $rs->received));
  }

  $no++;
  $row++;
}

$rw = 3;
$excel->getActiveSheet()->setCellValue('I2', 'ผู้เบิก');
$excel->getActiveSheet()->setCellValue('J2', 'ยอดค้างรับ');

if(!empty($emp))
{
  foreach($emp as $em)
  {
    $excel->getActiveSheet()->setCellValue('I'.$rw, $em['name']);
    $excel->getActiveSheet()->setCellValue('J'.$rw, $em['qty']);
    $rw++;
  }
}


setToken($_GET['token']);
$file_name = "สินค้าแปรสภาพค้างรับ ณ ".date('d-m-Y').".xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
header('Content-Disposition: attachment;filename="'.$file_name.'"');
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');
 ?>
