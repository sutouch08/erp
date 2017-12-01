<?php

function writeImportLogs($name, $result = 'Success', $import = 0, $update = 0, $error = 0, $last = 0)
{
  $content  = date('Y / m / d  H : i : s').'  :  '. 'นำเข้า '. $name;
  $content .= ' จาก  FORMULA';
  $content .= '  >> นำเข้า  :  '.$import.'  รายการ';
  $content .= '  >> ปรับปรุง  :  '.$update.'  รายการ';
  $content .= '  >> ผิดพลาด  :  '.$error. '  รายการ';
  $content .= PHP_EOL;
  if( $last != 0)
  {
    $content .= '###################################################################################################'. PHP_EOL;
  }

  $fileName = '../../logs/import_logs/'.date('Ymd').'.txt';
  file_put_contents($fileName, $content, FILE_APPEND);
}

?>
