<?php
//--  ไอดีของเมนู
$id_tab = 11;

//--- ตรวจสอบสิทธิ์
$pm = checkAccess($id_profile, $id_tab);

//--- สิทธิ์ในการดู
$view = $pm['view'];

//--- สิทธิ์ในการเพิ่ม
$add = $pm['add'];

//--- สิทธิ์ในการแก้ไข
$edit = $pm['edit'];

//--- สิทธิ์ในการลบ
$delete = $pm['delete'];

//--- หากไม่มีสิทธิ์ในการดู
accessDeny($view);
?>

<div class="container">
<?php

if( isset( $_GET['add']) )
{
  include 'include/adjust/adjust_add.php';
}
else
{
  include 'include/adjust/adjust_list.php';
}

 ?>
</div>
<script src="script/adjust/adjust.js"></script>
