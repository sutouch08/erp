<?php
$id = $_POST['id_consign_check'];
$cs = new consign_check($id);
$sc = TRUE;
$message = "";

if($cs->valid == 1)
{
  if( ! $cs->unvalid($id))
  {
    $sc = FALSE;
    $message = "ย้อนสถานะไม่สำเร็จ";
  }
}

echo $sc === TRUE ? 'success' : $message;

 ?>
