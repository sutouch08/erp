<div class="row">
	<div class="col-sm-2">
    	<label>วันที่เอกสาร</label>
        <input type="text" class="form-control input-sm text-center" id="dateAdd" placeholder="ระบุวันที่เอกสาร" value="<?php echo date('d-m-Y'); ?>" />
    </div>
    <div class="col-sm-2">
    	<label>ใบส่งสินค้า</label>
        <input type="text" class="form-control input-sm text-center" id="invoice" placeholder="อ้างอิงใบส่งสินค้า" />
    </div>
	<div class="col-sm-2">
    	<label>ใบสั่งซื้อ</label>
        <input type="text" class="form-control input-sm text-center" id="poCode" placeholder="ค้นหาใบสั่งซื้อ" />
    </div>
    <div class="col-sm-5">
    	<label>หมายเหตุ</label>
        <input type="text" class="form-control input-sm" id="remark" placeholder="ระบุหมายเหตุเอกสาร (ถ้ามี)" />
    </div>
<?php if( $add ) : ?>    
    <div class="col-sm-1">
    	<label class="display-block not-show">button</label>
        <button type="button" class="btn btn-sm btn-success btn-block" onClick="saveAdd()"><i class="fa fa-save"></i> บันทึก</button>
    </div>
<?php endif; ?>    
</div>
<hr class="margin-top-15"/>
<script src="script/receive_product/receive_product_add.js"></script>