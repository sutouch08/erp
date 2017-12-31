<?php
$pd = new product();
$txt = $_REQUEST['term'];
$field = 'code';
$limit = 50; //---- limit result

$sc = array();

$qs = $pd->search($txt, $field, $limit);

if(dbNumRows($qs) > 0)
{
  while($rs = dbFetchObject($qs))
  {
    $sc[] = $rs->code;
  }
}
else
{
  $sc[] = 'ไม่พบข้อมูล';
}

echo json_encode($sc);



 ?>
