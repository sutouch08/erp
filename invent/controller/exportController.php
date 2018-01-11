<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";
include "../function/report_helper.php";

if( isset($_GET['exportProductSelected']))
{
 	$exp 	= $_GET['style'];
	$web_id = getConfig("ITEMS_GROUP");
	$excel = new PHPExcel();
	$excel->setActiveSheetIndex(0);
	$excel->getActiveSheet()->setTitle("items");
	$excel->getActiveSheet()->setCellValue('A1', 'barcode');
	$excel->getActiveSheet()->setCellValue('B1', 'item_code');
	$excel->getActiveSheet()->setCellValue('C1', 'item_name');
	$excel->getActiveSheet()->setCellValue('D1', 'style');
	$excel->getActiveSheet()->setCellValue('E1', 'cost');
	$excel->getActiveSheet()->setCellValue('F1', 'price');
	$excel->getActiveSheet()->setCellValue('G1', 'items_group');
	$excel->getActiveSheet()->setCellValue('H1', 'category');
	$row = 2;
	foreach($exp as $id => $val)
	{
		$qr  = "SELECT bc.barcode, pd.code AS pdCode, pd.name AS pdName, st.code AS style, pd.cost, pd.price, pg.name AS groupName ";
		$qr .= "FROM tbl_product AS pd ";
		$qr .= "JOIN tbl_product_style AS st ON pd.id_style = st.id ";
		$qr .= "LEFT JOIN tbl_barcode AS bc ON pd.id = bc.id_product ";
		$qr .= "LEFT JOIN tbl_product_group AS pg ON pd.id_group = pg.id ";
		$qr .= "WHERE pd.id_style = '".$val."' ";
		$qr .= "ORDER BY pd.id_style ASC";

		$qs = dbQuery($qr);

		if( dbNumRows($qs) > 0 )
		{
			while($rs = dbFetchArray($qs) )
			{
				$excel->getActiveSheet()->setCellValue('A'.$row, $rs['barcode']);
				$excel->getActiveSheet()->setCellValue('B'.$row, $rs['pdCode']);
				$excel->getActiveSheet()->setCellValue('C'.$row, $rs['pdName']);
				$excel->getActiveSheet()->setCellValue('D'.$row, $rs['style']);
				$excel->getActiveSheet()->setCellValue('E'.$row, $rs['cost']);
				$excel->getActiveSheet()->setCellValue('F'.$row, $rs['price']);
				$excel->getActiveSheet()->setCellValue('G'.$row, $web_id);
				$excel->getActiveSheet()->setCellValue('H'.$row, $rs['groupName']);
				$excel->getActiveSheet()->getStyle('A'.$row)->getNumberFormat()->setFormatCode('0');
				$row++;
			}
		}
	}

	setToken($_GET['token']);
	$file_name = "items-".$web_id.".xlsx";
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
	header('Content-Disposition: attachment;filename="'.$file_name.'"');
	$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
	$writer->save('php://output');
}


if(isset( $_GET['exportAllProduct'] ))
{
	$web_id = getConfig("ITEMS_GROUP");
	$excel = new PHPExcel();
	$excel->setActiveSheetIndex(0);
	$excel->getActiveSheet()->setTitle("items");
	$excel->getActiveSheet()->setCellValue('A1', 'barcode');
	$excel->getActiveSheet()->setCellValue('B1', 'item_code');
	$excel->getActiveSheet()->setCellValue('C1', 'item_name');
	$excel->getActiveSheet()->setCellValue('D1', 'style');
	$excel->getActiveSheet()->setCellValue('E1', 'cost');
	$excel->getActiveSheet()->setCellValue('F1', 'price');
	$excel->getActiveSheet()->setCellValue('G1', 'items_group');
	$excel->getActiveSheet()->setCellValue('H1', 'category');

	$qr  = "SELECT bc.barcode, pd.code AS pdCode, pd.name AS pdName, st.code AS style, pd.cost, pd.price, pg.name AS groupName ";
	$qr .= "FROM tbl_product AS pd ";
	$qr .= "JOIN tbl_product_style AS st ON pd.id_style = st.id ";
	$qr .= "LEFT JOIN tbl_barcode AS bc ON pd.id = bc.id_product ";
	$qr .= "LEFT JOIN tbl_product_group AS pg ON pd.id_group = pg.id ";
	$qr .= "ORDER BY pd.id_style ASC";

	$qs = dbQuery($qr);

	if( dbNumRows($qs) > 0 )
	{
		$row = 2;
		while( $rs = dbFetchArray($qs) )
		{
			$excel->getActiveSheet()->setCellValue('A'.$row, $rs['barcode']);
			$excel->getActiveSheet()->setCellValue('B'.$row, $rs['pdCode']);
			$excel->getActiveSheet()->setCellValue('C'.$row, $rs['pdName']);
			$excel->getActiveSheet()->setCellValue('D'.$row, $rs['style']);
			$excel->getActiveSheet()->setCellValue('E'.$row, $rs['cost']);
			$excel->getActiveSheet()->setCellValue('F'.$row, $rs['price']);
			$excel->getActiveSheet()->setCellValue('G'.$row, $web_id);
			$excel->getActiveSheet()->setCellValue('H'.$row, $rs['groupName']);
			$excel->getActiveSheet()->getStyle('A'.$row)->getNumberFormat()->setFormatCode('0');
			$row++;
		}
	}

	setToken($_GET['token']);
	$file_name = "items-".$web_id.".xlsx";
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
	header('Content-Disposition: attachment;filename="'.$file_name.'"');
	$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
	$writer->save('php://output');

}

if(isset($_GET['clearFilter']))
{
	deleteCookie('pdCode');
	deleteCookie('fromDate');
	deleteCookie('toDate');
	echo 'done';
}

?>
