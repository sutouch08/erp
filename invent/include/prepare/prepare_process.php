<?php if( ! isset($_GET['id_order']) || $_GET['id_order'] < 1) : ?>

<?php   include 'include/page_error.php'; ?>

<?php else : ?>

<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-shopping-basket"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
      <button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
    </p>
  </div>

</div>

<hr class="margin-top-10 margin-bottom-10" />

<?php
$order = new order($_GET['id_order']);
if( $order->state == 3)
{
  $order->stateChange($order->id, 4);
}
else if($order->state != 4)
{
  include 'include/prepare/invalid_state.php';
}
?>


<div class="row">
  <div class="col-sm-2">
    <label>เลขที่ : <?php echo $order->reference; ?></label>
  </div>
  <div class="col-sm-4">
    <label>ลูกค้า : <?php echo customerName($order->id_customer); ?></label>
  </div>
  <div class="col-sm-3">
    <label>วันที่ : <?php echo thaiDate($order->date_add); ?></label>
  </div>
  <input type="hidden" id="id_order" value="<?php echo $order->id; ?>" />
</div>


<hr class="margin-top-10 margin-bottom-10"/>

<?php include 'include/prepare/prepare_control.php'; ?>

<hr class="margin-top-10 margin-bottom-10"/>

<?php include 'include/prepare/prepare_incomplete_list.php'; ?>

<?php include 'include/prepare/prepare_completed_list.php'; ?>

<script src="script/prepare/prepare_process.js"></script>

<?php endif; ?>
