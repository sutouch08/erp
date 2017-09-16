<?php 
$order = isset( $_GET['id_order'] ) ? new order( $_GET['id_order'] ) : new order();
?>
<div class="row top-row">
	<div class="col-sm-6 top-col"><h4 class="title"><i class="fa fa-shopping-bag"></i> <?php echo $pageTitle; ?></h4></div>
    <div class="col-sm-6">
    	<p class="pull-right top-p">
        	<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i>  กลับ</button>
<?php if( ($add && $order->status == 0 && $order->id_employee == getCookie('user_id') ) OR ($edit && $order->status == 1 && $order->state < 4 ) ) : ?>
            <button type="button" class="btn btn-sm btn-info" onclick="goAddDetail(<?php echo $order->id; ?>)"><i class="fa fa-pencil"></i> แก้ไขรายการ</button>
<?php endif; ?>    
        </p>
    </div>
</div>
<hr class="margin-bottom-10" />
<?php if( $order->id < 1 ) : ?>
<?php 	include 'include/page_error.php'; ?>
<?php else : //--- isset $_GET['id_order'] ?>
<?php $pm = new payment_method(); ?>
<div class="row">
	<div class="col-sm-2 padding-5 first">
    	<label>เลขที่เอกสาร</label>
        <label class="form-control input-sm text-center" disabled><?php echo $order->reference; ?></label>
    </div>
    <div class="col-sm-1 padding-5">
    	<label>วันที่</label>
        <label class="form-control input-sm text-center" disabled><?php echo thaiDate($order->date_add); ?></label>
    </div>
    <div class="col-sm-4 padding-5">
    	<label>ลูกค้า</label>
        <label class="form-control input-sm" disabled><?php echo customerName($order->id_customer); ?></label>
    </div>
    <div class="col-sm-3 padding-5">
    	<label>พนักงาน</label>
        <label class="form-control input-sm" disabled><?php echo employee_name($order->id_employee); ?></label>
    </div>
    <div class="col-sm-2">
    	<label>การชำระเงิน</label>
        <label class="form-control input-sm text-center" disabled><?php echo $pm->getName($order->id_payment); ?></label>
    </div>
    <div class="col-sm-12 margin-top-10">
    	<label>หมายเหตุ : </label>
        <label  style="font-weight:normal;"><?php echo $order->remark; ?></label>
    </div>
</div>
<hr/>
<?php include 'include/order/order_state.php'; ?>

<hr/>
<?php include 'include/order/order_detail.php'; ?>

<script src="script/order/order_edit.js"></script>
<?php endif; //--- isset $_GET['id_order']  ?>

