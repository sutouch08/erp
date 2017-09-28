<?php if( ! isset( $_GET['id_order'] ) OR $_GET['id_order'] < 1 ) : ?>
<?php   include 'include/page_error.php';   ?>
<?php else : ?>

<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-check-square-o"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
      <button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
    </p>
  </div>
</div>

<hr/>

<?php
$order = new order($_GET['id_order']);

//--- ถ้าสถานะเลยกำลังตรวจไปแล้ว ให้ disabled ปุ่มต่าง ไม่ให้กดได้
$active = $order->state == 6 ? '' : 'disabled';

$qc = new qc();

$prepare = new prepare();

if( $order->state == 5 OR $order->state == 6)
{
  if( $order->state == 5 )
  {
    //--- เปลี่ยนสถานะออเดอร์เป็น กำลังตรวจ
    $order->stateChange($order->id, 6);

  }
}


//--- แสดงผลข้อมูลเอกสาร
include 'include/qc/qc_header.php';

//--- แสดงผลกล่อง
 include 'include/qc/qc_box.php';

//--- กล่องควบคุมการตรวจนับ
include 'include/qc/qc_control.php';

//--- รายการสินค้าที่ยังตรวจไม่ครบ
include 'include/qc/qc_incomplete_list.php';

//--- รายการสินค้าที่ตรวจครบแล้ว
include 'include/qc/qc_complete_list.php';

?>

<script src="script/qc/qc_process.js"></script>
<script src="script/qc/qc_control.js"></script>
<?php endif; ?>
