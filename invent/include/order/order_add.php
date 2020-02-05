<?php
$order = isset( $_GET['id_order'] ) ? new order( $_GET['id_order'] ) : new order();
$disabled = isset($_GET['id_order']) ? 'disabled' : '';
$hide = ( $order->status == 0 OR $order->hasNotSaveDetail === TRUE ) ? '' : 'hide';
$online = (isset( $_GET['online'] ) OR $order->isOnline == 1 ) ? TRUE : FALSE;
?>
<div class="row top-row">
	<div class="col-sm-6 top-col">
    	<h4 class="title"><i class="fa fa-shopping-bag"></i> <?php echo $pageTitle; ?></h4>
    </div>
    <div class="col-sm-6">
    	<p class="pull-right top-p">
        	<?php if( $order->id != "" ) : ?>
        	<button type="button" class="btn btn-sm btn-warning" onClick="goEdit(<?php echo $order->id; ?>)"><i class="fa fa-arrow-left"></i> กลับ</button>
            <?php else : ?>
							<?php if($online) : ?>
								<button type="button" class="btn btn-sm btn-warning" onClick="goBackOnline()"><i class="fa fa-arrow-left"></i> กลับ</button>
							<?php else : ?>
            		<button type="button" class="btn btn-sm btn-warning" onClick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
							<?php endif; ?>
            <?php endif; ?>
            <?php if( isset( $_GET['id_order'] ) ) : ?>
            <button type="button" class="btn btn-sm btn-success <?php echo $hide; ?>" id="btn-save-order" onclick="saveOrder(<?php echo $order->id; ?>)">
            	<i class="fa fa-save"></i> บันทึก
            </button>
            <?php endif; ?>
        </p>
    </div>
</div>
<hr class="margin-bottom-10" />
<?php if( $order->state > 3) : ?>
<?php 	include 'include/page_error.php';	?>
<?php else : ?>

<?php

if( $online )
{
	include 'include/order/order_add_online_header.php';
}
else
{
	include 'include/order/order_add_header.php';
}

?>
<hr class="margin-top-10 margin-bottom-15" />

<?php if( isset( $_GET['id_order'] ) ) : ?>
<!--  Search Product -->
<div class="row">
	<div class="col-sm-2 col-2-harf padding-5 first">
    	<input type="text" class="form-control input-sm text-center" id="pd-box" placeholder="ค้นหารุ่นสินค้า" />
    </div>
    <div class="col-sm-1 padding-5">
    	<button type="button" class="btn btn-sm btn-primary btn-block" onclick="getProductGrid()"><i class="fa fa-tags"></i> แสดงสินค้า</button>
    </div>
    <div class="col-sm-2 col-2-harf padding-5">
    	<input type="text" class="form-control input-sm text-center" id="item-code" placeholder="ค้นหารหัสสินค้า">
    </div>
		<div class="col-sm-1 padding-5">
    	<input type="number" class="form-control input-sm text-center" id="stock-qty" disabled>
    </div>
		<div class="col-sm-1 padding-5">
    	<input type="number" class="form-control input-sm text-center" id="input-qty">
    </div>
		<div class="col-sm-1 padding-5">
			<button type="button" class="btn btn-sm btn-primary btn-block" onclick="addItemToOrder()">เพิ่ม</button>
		</div>
    <div class="col-sm-2 col-sm-offset-1 padding-5 last">
    	<button type="button" class="btn btn-sm btn-info btn-block" onclick="recalDiscount()">
        	<i class="fa fa-calculator"></i> คำนวณส่วนลดใหม่</button>
        </button>
    </div>
</div>
<hr class="margin-top-15 margin-bottom-0" />
<!----------------------------------------- Category Menu ---------------------------------->
<div class='row'>
	<div class='col-sm-12'>
		<ul class='nav navbar-nav' role='tablist' style='background-color:#EEE'>
		<?php echo productTabMenu('order'); ?>
		xxxx
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

<?php include 'include/order/order_detail.php'; ?>


<form id="orderForm">
<div class="modal fade" id="orderGrid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" id="modal">
		<div class="modal-content">
  			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="modalTitle">title</h4>
                <center><span style="color: red;">ใน ( ) = ยอดคงเหลือทั้งหมด   ไม่มีวงเล็บ = สั่งได้ทันที</span></center>
                <input type="hidden" name="id_order" id="id_order" value="<?php echo $order->id; ?>" />
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

<?php
	//---- include modal for validate s_key to confirm change order date after add details
	include 'include/validate_credentials.php';
?>

<?php endif; ?>

<?php endif; ?>
<script src="script/order/order_add.js?token=<?php echo date('Ymd'); ?>"></script>
<script src="script/product_tab_menu.js?token=<?php echo date('Ymd'); ?>"></script>
<script src="script/order/order_grid.js?token=<?php echo date('Ymd'); ?>"></script>
