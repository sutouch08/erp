
<?php include 'function/discount_helper.php'; ?>
<?php $qs = dbQuery("SELECT * FROM tbl_product_group"); ?>
<div class="tab-pane fade" id="discount">
<form id="discountForm">
	<input type="hidden" name="id_customer" id="id_customer" value="<?php echo $customer->id; ?>" />
	<div class="row">
    <div class="col-sm-6 top-col">
    	<h4 class="title">ส่วนลดลูกค้า</h4>
    </div>
    <div class="col-sm-6">
    	<p class="pull-right top-p">
        	<button type="button" class="btn btn-sm btn-success" onclick="updateDiscount()"><i class="fa fa-save"></i> บันทึกส่วนลด</button>
        </p>
    </div>
    <div class="divider"></div>
<?php	if( dbNumRows($qs) > 0 ) : ?>
<?php		while( $rs = dbFetchObject($qs) ) : ?>      
<?php			$discount = showDiscountByProductGroup($customer->id, $rs->id);	?>
        <div class="col-sm-3"><span class="form-control left-label text-right"><?php echo $rs->name; ?></span></div>
        <div class="col-sm-9">
          	<div class="input-group width-25">
            	<input type="text" class="form-control input-sm discount-box" id="group_<?php echo $rs->id; ?>" name="group[<?php echo $rs->id; ?>]" value="<?php echo $discount; ?>" />
                <span class="input-group-addon">%</span>
            </div>
        </div>
       <div class="divider-hidden"></div>
<?php 	endwhile; ?>
<?php endif; ?>       

  
    </div>
</form>
</div><!--- Tab-pane --->