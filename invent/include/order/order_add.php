<?php 
$order = isset( $_GET['id_order'] ) ? new order( $_GET['id_order'] ) : new order();
$disabled = isset($_GET['id_order']) ? 'disabled' : '';
?>
<div class="row top-row">
	<div class="col-sm-6 top-row">
    	<h4 class="title"><i class="fa fa-shopping-bag"></i> <?php echo $pageTitle; ?></h4>
    </div>
    <div class="col-sm-6">
    	<p class="pull-right top-p">
        	<button type="button" class="btn btn-sm btn-warning" onClick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
        </p>
    </div>
</div>
<hr class="margin-bottom-10" />
<div class="row">
	<div class="col-sm-2">
    	<label>เลขที่เอกสาร</label>
        <label class="form-control input-sm text-center" <?php echo $disabled; ?>><?php echo $order->reference; ?></label>
    </div>
    <div class="col-sm-2">
    	<label>วันที่</label>
        <input type="text" class="form-control input-sm text-center" id="dateAdd" value="<?php echo thaiDate($order->date_add); ?>" <?php echo $disabled; ?> />
    </div>
    <div class="col-sm-4">
    	<label>ลูกค้า</label>
        <input type="text" class="form-control input-sm text-center" id="customer" value="<?php echo customerName($order->id_customer); ?>"  <?php echo $disabled; ?>/>
    </div>
    <div class="col-sm-2">
    	<label>ช่องทาง</label>
        <select class="form-control input-sm" id="channels" <?php echo $disabled; ?>>
        <?php echo selectChannels($order->id_channels); ?>
        </select>
    </div>
    <div class="col-sm-2 margin-bottom-5">
    	<label>การชำระเงิน</label>
        <select class="form-control input-sm" id="paymentMethod" <?php echo $disabled; ?>>
        <?php echo selectPaymentMethod($order->id_payment); ?>
        </select>
    </div>
    <div class="col-sm-10">
    	<label>หมายเหตุ</label>
        <input type="text" class="form-control input-sm" id="remark" value="<?php echo $order->remark; ?>" <?php echo $disabled; ?> />
    </div>
    <div class="col-sm-2">
    <label class="display-block not-show">btn</label>
    <?php if( isset( $_GET['id_order'] ) ): ?>	
    	<button type="button" class="btn btn-sm btn-warning btn-block" id="btn-edit-order" onclick="getEdit(<?php echo $order->id; ?>)">แก้ไข</button>
        <button type="button" class="btn btn-sm btn-success btn-block hide" id="btn-update-order" onclick="updateOrder(<?php echo $order->id; ?>)">บันทึก</button>
    <?php else : ?>
    	<button type="button" class="btn btn-sm btn-success btn-block" onclick="addNew()">สร้างออเดอร์</button>
    <?php endif; ?>
    </div>
</div>
	<input type="hidden" id="id_customer" />
	<input type="hidden" id="id_order" value="<?php $order->id; ?>" />
<hr class="margin-top-10 margin-bottom-15" />

<?php if( isset( $_GET['id_order'] ) ) : ?>
<!--  Search Product -->
<div class="row">
	<div class="col-sm-3">
    	<input type="text" class="form-control input-sm text-center" id="pd-box" placeholder="ค้นรหัสสินค้า" />
    </div>
    <div class="col-sm-2">
    	<button type="button" class="btn btn-sm btn-primary btn-block" onclick="getProductGrid()"><i class="fa fa-tags"></i> แสดงสินค้า</button>
    </div>
</div>
<hr class="margin-top-15 margin-bottom-0" />
<!----------------------------------------- Category Menu ---------------------------------->
<div class='row'>
	<div class='col-sm-12'>
		<ul class='nav navbar-nav' role='tablist' style='background-color:#EEE'>
		<?php echo productTabMenu('order'); ?>
		</ul>
	</div><!---/ col-sm-12 ---->
</div><!---/ row -->
<hr style='border-color:#CCC; margin-top: 0px; margin-bottom:0px;' />
<div class='row'>
	<div class='col-sm-12'>		
		<div class='tab-content' style="min-height:1px; padding:0px;">
		<?php echo getProductTabs(); ?>
		</div>
	</div>
</div>
<!------------------------------------ End Category Menu ------------------------------------>	

<!--------------------------------- Order Detail ----------------->
<div class="row">
	<div class="col-sm-12">
    	<table class="table table-striped bordered-1">
        <thead>
        	<tr class="font-size-12">
            	<th class="width-5 text-center">No.</th>
                <th class="width-10 text-center"></th>
                <th class="width-15">รหัสสินค้า</th>
                <th class="width-20">ชื่อสินค้า</th>
                <th class="width-10 text-center">ราคา</th>
                <th class="width-10 text-center">จำนวน</th>
                <th class="width-10 text-center">ส่วนลด</th>
                <th class="width-10 text-center">มูลค่า</th>
                <th class="width-10 text-center"></th>
            </tr>
        </thead>
        <tbody id="order-table">
<?php $detail = $order->getDetail($order->id); ?>
<?php if( dbNumRows($detail) > 0 ) : ?>

<?php else : ?>
			<tr>
            	<td colspan="9" class="text-center"><h4>ไม่พบรายการ</h4></td>
            </tr>
<?php endif; ?>
		
        </tbody>
        </table>
    </div>
</div>



<!--------------------------------  End Order Detail ----------------->

<form id="orderForm">
<div class="modal fade" id="orderGrid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" id="modal">
		<div class="modal-content">
  			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="modalTitle">title</h4>
                <center><span style="color: red;">ใน ( ) = ยอดคงเหลือทั้งหมด   ไม่มีวงเล็บ = สั่งได้ทันที</span></center>
                <input type="hidden" name="id_order" value="<?php echo $order->id; ?>" />
			 </div>
			 <div class="modal-body" id="modalBody"></div>
			 <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
				<button type="button" class="btn btn-primary" onClick="addToOrder()" >เพิ่มในรายการ</button>
			 </div>
		</div>
	</div>
</div>
</form>
<?php endif; ?>
<script src="script/order/order_add.js"></script>
<script src="script/order/order_grid.js"></script>