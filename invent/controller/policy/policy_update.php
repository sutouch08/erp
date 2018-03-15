<?php
$sc = TRUE;
$id = $_POST['id'];
$name = $_POST['name'];
$from = dbDate($_POST['date_start']);
$to = dbDate($_POST['date_end']);

$cs = new discount_policy();

$arr = array(
  'name' => $name,
  'date_start' => $from,
  'date_end' => $to,
  'emp_upd' => getCookie('user_id')
);

if($cs->update($id, $arr) !== TRUE)
{
  $sc = FALSE;
  $message = 'ปรับปรุงรายการไม่สำเสร็จ : '.$cs->error;
}

echo $sc === TRUE ? 'success' : $message;
 ?>
