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
        	<?php if( $order->id != "" ) : ?>
        	<button type="button" class="btn btn-sm btn-warning" onClick="goEdit(<?php echo $order->id; ?>)"><i class="fa fa-arrow-left"></i> กลับ</button>
            <?php else : ?>
            <button type="button" class="btn btn-sm btn-warning" onClick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
            <?php endif; ?>
            <?php if( $order->status == 0 ) : ?>
            <button type="button" class="btn btn-sm btn-success" onclick="saveOrder(<?php echo $order->id; ?>)">
            <i class="fa fa-save"></i> บันทึก</button>
            <?php endif; ?>
        </p>
    </div>
</div>
<hr class="margin-bottom-10" />
<?php include 'include/order/order_add_header.php';	?>
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
    	<table class="table table-striped border-1">
        <thead>
        	<tr class="font-size-12">
            	<th class="width-5 text-center">No.</th>
                <th class="width-5 text-center"></th>
                <th class="width-15">รหัสสินค้า</th>
                <th class="width-25">ชื่อสินค้า</th>
                <th class="width-10 text-center">ราคา</th>
                <th class="width-10 text-center">จำนวน</th>
                <th class="width-10 text-center">ส่วนลด</th>
                <th class="width-10 text-center">มูลค่า</th>
                <th class="width-10 text-center"></th>
            </tr>
        </thead>
        <tbody id="detail-table">
<?php $detail = $order->getDetails($order->id); ?>
<?php if( dbNumRows($detail) > 0 ) : ?>
<?php 	$no = 1; 							?>
<?php 	$totalQty = 0;		?>
<?php	$image = new image(); ?>
<?php	while( $rs = dbFetchObject($detail) ) : ?>
			<tr class="font-size-10" id="row_<?php echo $rs->id; ?>">
            	<td class="middle text-center"><?php echo $no; ?></td>
                <td class="middle text-center padding-0">
                	<img src="<?php echo $image->getProductImage($rs->id_product, 1); ?>" width="40px" height="40px"  />
                </td>
                <td class="middle"><?php echo $rs->product_code; ?></td>
                <td class="middle"><?php echo $rs->product_name; ?></td>
                <td class="middle text-center"><?php echo number_format($rs->price, 2); ?></td>
                <td class="middle text-center"><?php echo number_format($rs->qty); ?></td>
                <td class="middle text-center"><?php echo $rs->discount; ?></td>
                <td class="middle text-center"><?php echo number_format($rs->total_amount, 2); ?></td>
                <td class="middle text-right">
                <?php if( $edit OR $add ) : ?>
                	<button type="button" class="btn btn-xs btn-danger" onclick="removeDetail(<?php echo $rs->id; ?>, '<?php echo $rs->product_code; ?>')"><i class="fa fa-trash"></i></button>
                <?php endif; ?>
                </td>
                
            </tr>
<?php	$totalQty += $rs->qty;	?>            
<?php		$no++; ?>            
<?php 	endwhile; ?>
			<tr>
            	<td colspan="7" class="text-right"><h4>Total : </h4></td>
                <td class="text-center"><h4><?php echo number_format($totalQty); ?></h4></td>
                <td class="text-center"><h4>Pcs.</h4></td>
            </tr>
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


<!------ order detail template ------>
<script id="detail-table-template" type="text/x-handlebars-template">
{{#each this}}
	{{#if @last}}
        <tr>
            <td colspan="7" class="text-right"><h4>Total : </h4></td>
            <td class="text-center"><h4>{{ total }}</h4></td>
            <td class="text-center"><h4>Pcs.</h4></td>
        </tr>
	{{else}}
        <tr class="font-size-10" id="row_{{ id }}">
            <td class="middle text-center">{{ no }}</td>
            <td class="middle text-center padding-0">
            	<img src="{{ imageLink }}" width="40px" height="40px"  />
            </td>
            <td class="middle">{{ productCode }}</td>
            <td class="middle">{{ productName }}</td>
            <td class="middle text-center">{{ price }}</td>
            <td class="middle text-center">{{ qty }}</td>
            <td class="middle text-center">{{ discount }}</td>
            <td class="middle text-center">{{ amount }}</td>
            <td class="middle text-right">
            <?php if( $edit OR $add ) : ?>
            	<button type="button" class="btn btn-xs btn-danger" onclick="removeDetail({{ id }}, '{{ productCode }}')"><i class="fa fa-trash"></i></button>
            <?php endif; ?>
            </td>              
        </tr>
	{{/if}}
{{/each}}
</script>

<script id="nodata-template" type="text/x-handlebars-template">
	<tr>
          <td colspan="9" class="text-center"><h4>ไม่พบรายการ</h4></td>
    </tr>
</script>

<?php endif; ?>
<script src="script/order/order_add.js"></script>
<script src="script/product_tab_menu.js"></script>
<script src="script/order/order_grid.js"></script>