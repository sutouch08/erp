<?php
$sc = TRUE;
$id = $_POST['id'];
$name = $_POST['name'];

$cs = new discount_rule();

$arr = array(
  'name' => addslashes($name),
  'emp_upd' => getCookie('user_id')
);

if($cs->update($id, $arr) !== TRUE)
{
  $sc = FALSE;
  $message = 'ปรับปรุงรายการไม่สำเสร็จ : '.$cs->error;
}

echo $sc === TRUE ? 'success' : $message;
 ?>
