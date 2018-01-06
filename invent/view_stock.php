<?php
	//include 'function/order_helper.php';
	//include 'function/customer_helper.php';
	//include 'function/employee_helper.php';
	//include 'function/channels_helper.php';
	//include 'function/payment_method_helper.php';
	include 'function/productTab_helper.php';
	//include 'function/bank_helper.php';
	//include 'function/payment_helper.php';
	//include 'function/shipping_helper.php';
	//include 'function/location_helper.php';
 ?>

<div class="container">
<div class="row top-row">
	<div class="col-sm-6 top-row">
    	<h4 class="title"><i class="fa fa-shopping-bag"></i> <?php echo $pageTitle; ?></h4>
    </div>
</div>
<hr class="margin-bottom-10" />

<!----------------------------------------- Category Menu ---------------------------------->
<div class='row'>
	<div class='col-sm-12'>
		<ul class='nav navbar-nav' role='tablist' style='background-color:#EEE'>
		<?php echo productTabMenu('stock'); ?>
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
</div>

<form id="orderForm">
<div class="modal fade" id="orderGrid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" id="modal">
		<div class="modal-content">
  			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="modalTitle">title</h4>
                <center><span style="color: red;">ใน ( ) = ยอดคงเหลือทั้งหมด   ไม่มีวงเล็บ = สั่งได้ทันที</span></center>
			 </div>
			 <div class="modal-body" id="modalBody"></div>
			 <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
			 </div>
		</div>
	</div>
</div>
</form>

<script src="script/order/order_grid.js"></script>
<script src="script/product_tab_menu.js"></script>
