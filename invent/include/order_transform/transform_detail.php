
<div class="row">
	<div class="col-sm-12">
    	<table class="table table-striped border-1">
        <thead>
        	<tr class="font-size-12">
          	<th class="width-5 text-center">No.</th>
            <th class="width-5 text-center"></th>
            <th class="width-15">รหัสสินค้า</th>
						<th class="width-20">ชื่อสินค้า</th>
						<th class="width-10 text-center">จำนวน</th>
						<th class="width-25">สินค้าแปรสภาพ</th>
						<th class="width-10 text-center"></th>
            <th class="width-10 text-center"></th>
          </tr>
        </thead>
        <tbody id="detail-table">
<?php $detail = $order->getDetails($order->id); ?>
<?php if( dbNumRows($detail) > 0 ) : ?>
<?php 	$no = 1; 							?>
<?php 	$total_qty = 0;		?>
<?php	$image = new image(); ?>
<?php	while( $rs = dbFetchObject($detail) ) : ?>
			<tr class="font-size-10" id="row_<?php echo $rs->id; ?>">
      	<td class="middle text-center">
					<?php echo $no; ?>
				</td>

        <td class="middle text-center padding-0">
        	<img src="<?php echo $image->getProductImage($rs->id_product, 1); ?>" width="40px" height="40px"  />
        </td>

        <td class="middle">
					<?php echo $rs->product_code; ?>
				</td>

				<td class="middle">
					<?php echo $rs->product_name; ?>
				</td>

				<td class="middle text-center qty" id="qty-<?php echo $rs->id; ?>">
					<?php echo number($rs->qty); ?>
				</td>

        <td class="middle" id="transform-box-<?php echo $rs->id; ?>">
					<?php
					//---	รายการสินค้าที่เชื่อมโยงแล้ว
					echo getTransformProducts($rs->id);
					 ?>
					<!--- ยอดรวมของสินค้าที่เชื่อมโยงแล้ว -->
					<input type="hidden" id="transform-qty-<?php echo $rs->id; ?>" value="<?php echo $transform->getSumTransformProductQty($rs->id); ?>" />
				</td>

        <td class="text-center">
					<button type="button" class="btn btn-xs btn-success btn-block" onclick="addTransformProduct(<?php echo $rs->id; ?>)"><i class="fa fa-plus"></i> เชื่อมโยง</button>
        </td>

        <td class="middle text-right">
        <?php if(  $edit OR $add ) : ?>
        	<button type="button" class="btn btn-xs btn-danger" onclick="removeDetail(<?php echo $rs->id; ?>, '<?php echo $rs->product_code; ?>')"><i class="fa fa-trash"></i></button>
        <?php endif; ?>
        </td>
			</tr>

<?php	$total_qty += $rs->qty;	?>
<?php		$no++; ?>
<?php 	endwhile; ?>
			<tr class="font-size-12">
        <td colspan="6" class="text-right"><b>จำนวนรวม</b></td>
        <td class="text-right"><b><?php echo number_format($total_qty); ?></b></td>
        <td class="text-center"><b>Pcs.</b></td>
      </tr>
<?php else : ?>
			<tr>
        <td colspan="8" class="text-center"><h4>ไม่พบรายการ</h4></td>
      </tr>
<?php endif; ?>

        </tbody>
        </table>
    </div>
</div>
<!---  End Order Detail --->

<div class="modal fade" id="transform-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" id="modal" style="width:500px;">
		<div class="modal-content">
  		<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="modal_title">เพิ่มการเชื่อมโยง</h4>
        <input type="hidden" id="id_order_detail" value="" />
				<input type="hidden" id="detail-qty" value="" />
				<input type="hidden" id="id_product" value="" />
			 </div>
			 <div class="modal-body" id="modal_body">
				 <div class="row">
				 	<div class="col-sm-3">
				 		<label>จำนวน</label>
						<input type="text" class="form-control input-sm text-center" id="trans-qty" value="" />
						<span class="help-block red not-show" id="qty-error">error</span>
				 	</div>
					<div class="col-sm-9">
						<label>สินค้าแปรสภาพ</label>
						<input type="text" class="form-control input-sm" id="trans-product" value="" />
						<span class="help-block red not-show" id="product-error">error</span>
					</div>
					<div class="col-sm-12">

					</div>
				 </div>

			 </div>
			 <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
				<button type="button" class="btn btn-primary" onClick="addToTransform()" >เชื่อมโยง</button>
			 </div>
		</div>
	</div>
</div>


<!--- order detail template ------>
<script id="detail-table-template" type="text/x-handlebars-template">
{{#each this}}
	{{#if @last}}
        <tr>
        	<td colspan="6" class="text-right" ><b>จำนวนรวม</b></td>
          <td class="text-right"><b>{{ total_qty }}</b></td>
          <td class="text-center"><b>Pcs.</b></td>
        </tr>
	{{else}}
        <tr class="font-size-10" id="row_{{ id }}">
            <td class="middle text-center">{{ no }}</td>
            <td class="middle text-center padding-0">
            	<img src="{{ imageLink }}" width="40px" height="40px"  />
            </td>
            <td class="middle">{{ productCode }}</td>

            <td class="middle">{{ productName }}</td>

            <td class="middle text-center qty" id="qty-{{ id }}">{{ qty }}</td>

            <td class="middle" id="transform-box-{{ id }}">
							{{ trasProduct }}
							<input type="hidden" id="transform-qty-{{ id }}" value="{{ trans_qty }}" />
						</td>

            <td class="middle text-right">
							<button type="button" class="btn btn-xs btn-success btn-block" onclick="addTransformProduct({{ id }})"><i class="fa fa-plus"></i> เชื่อมโยง</button>
						</td>

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
          <td colspan="8" class="text-center"><h4>ไม่พบรายการ</h4></td>
    </tr>
</script>

<script src="script/order_transform/transform_detail.js"></script>
