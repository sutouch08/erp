<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";
include '../../library/class/PHPExcel.php';

function writeErrorLogs($name, $error)
{
  $content  = date('Y / m / d  H : i : s').'  |  '. $name;
  $content .= ' | '.$error;
  $content .= PHP_EOL;

  $path = getConfig('IMPORT_LOG_PATH');
  $fileName = $path.'ErrorLogs-'.date('Ymd').'.LOG';
  file_put_contents($fileName, $content, FILE_APPEND);
}

if( isset( $_GET['importStockZone'] ) )
{
	$sc = TRUE;
  $stockImported = 0;
  $stockImportSuccess = 0;
  $stockImportError = 0;
  $movementImported = 0;
  $movementImportSuccess = 0;
  $movementImportError = 0;
  $noZone = 0;
  $noProduct = 0;
  $import = 0;

	//$skr = array();

	$file = isset( $_FILES['uploadFile'] ) ? $_FILES['uploadFile'] : FALSE;
	$file_path 	= "../../upload/";
  $upload	= new upload($file);
  if($upload->uploaded)
  {
  	$upload->file_new_name_body = 'importItem-'.date('YmdHis');
  	$upload->file_overwrite     = TRUE;
  	$upload->auto_create_dir    = FALSE;

  	$upload->process($file_path);

  	if( ! $upload->processed)
  	{
      $sc = FALSE;
      $message = $upload->error;
    }
    else
    {
      $reader = new PHPExcel_Reader_Excel2007();
      $reader->setReadDataOnly(TRUE);
      $excel = $reader->load($upload->file_dst_pathname);
      $collection	= $excel->getActiveSheet()->toArray(NULL, TRUE, TRUE, TRUE);

      $pd = new product();
      $mv = new movement();
      $st = new stock();
      $zone = new zone();
      $wh = new warehouse();

      $reference = 'บันทึกยอดยกมา';
      $date_upd = date('Y-m-d H:i:s');

      $i = 1;
      foreach($collection as $rs)
      {
        if($i > 1)
        {
          $import++;
          $id_zone = $zone->getId($rs['A']);
          $id_wh = $id_zone === FALSE ? FALSE : $zone->getWarehouseId($id_zone);
          $id_pd = $pd->getId($rs['B']);
          $qty = $rs['C'];

          if( $id_zone === FALSE OR $id_wh === FALSE)
          {
            $noZone++;
            writeErrorLogs('Zone Error' ,$rs['A'].' : '.$rs['B'].' : '.$qty);
          }
          else if($id_pd === FALSE)
          {
            $noProduct++;
            writeErrorLogs('Product Error' ,$rs['A'].' : '. $rs['B'].' : '.$qty);
          }
          else
          {

            //--- import stock
            $stockImported++;
            if($st->updateStockZone($id_zone, $id_pd, $qty) !== TRUE)
            {
              $stockImportError++;
              writeErrorLogs('Import Stock Error', $rs['A'].' : '.$rs['B'] .' : '. $qty.' : '.$st->error);
            }
            else
            {
              $stockImportSuccess++;

              //--- import movement
              $movementImported++;
              if($mv->move_in($reference, $id_wh, $id_zone, $id_pd, $qty, $date_upd) !== TRUE)
              {
                $sc = FALSE;
                $movementImportError++;
                writeErrorLogs('Import Movement Error', $rs['A'].' : '.$rs['B'] .' : '. $mv->error);
              }
              else
              {
                $movementImportSuccess++;
              }

            }

          }

        }

        $i++;
      }

    }
  }

  $upload->clean();

  $res  = 'นำเข้าทั้งหมด ' .$import.' รายการ <br/>';
  $res .= 'ไม่พบโซน '.$noZone.' รายการ <br/>';
  $res .= 'ไม่พบสินคา '.$noProduct.' รายการ </br/>';
  $res .= '======================== <br/>';
  $res .= 'นำเข้าสต็อก '.$stockImported.' รายการ <br/>';
  $res .= 'สำเร็จ '.$stockImportSuccess.' รายการ <br/>';
  $res .= 'ผิดพลาด '.$stockImportError.' รายการ <br/>';
  $res .= '======================== <br/>';
  $res .= 'บันทึก movement '.$movementImported.' รายการ <br/>';
  $res .= 'สำเร็จ '.$movementImportSuccess.' รายการ <br/>';
  $res .= 'ไม่สำเร็จ '.$movementImportError.' รายการ <br/>';

  echo $sc === TRUE ? $res : $message;
}




?>
