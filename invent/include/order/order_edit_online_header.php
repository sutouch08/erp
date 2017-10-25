
<div class="row">
	<div class="col-sm-1 col-1-harf padding-5 first">
    	<label>เลขที่เอกสาร</label>
        <label class="form-control input-sm text-center" disabled><?php echo $order->reference; ?></label>
    </div>
    <div class="col-sm-1 padding-5">
    	<label>วันที่</label>
        <label class="form-control input-sm text-center" disabled><?php echo thaiDate($order->date_add); ?></label>
    </div>
    <div class="col-sm-3 col-3-harf padding-5">
    	<label>ลูกค้า</label>
        <label class="form-control input-sm" disabled>
		<?php echo customerName($order->id_customer); ?>
        -  [  <?php echo $order->online_code; ?>  ]
        </label>
    </div>
    <div class="col-sm-3 padding-5">
    	<label>พนักงาน</label>
        <label class="form-control input-sm" disabled><?php echo employee_name($order->id_employee); ?></label>
    </div>
    <div class="col-sm-1 col-1-harf padding-5">
    	<label>ช่องทางขาย</label>
        <label class="form-control input-sm text-center" disabled><?php echo $channels->name; ?></label>
    </div>
    <div class="col-sm-1 col-1-harf padding-5 last">
    	<label>การชำระเงิน</label>
        <label class="form-control input-sm text-center" disabled><?php echo $payment->name; ?></label>
    </div>
<?php if( $order->remark != "" ) : ?>
    <div class="col-sm-12 margin-top-10">
    	<label>หมายเหตุ : </label>
        <label  style="font-weight:normal;"><?php echo $order->remark; ?></label>
    </div>
<?php endif; ?>
    <input type="hidden" name="id_order" id="id_order" value="<?php echo $order->id; ?>" />
</div>

<hr/>
