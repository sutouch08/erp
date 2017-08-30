<?php
	$id_receive_product = $_GET['id_receive_product'];
	$cs = new receive_product($id_receive_product);
?>
<div class="row">
	<div class="col-sm-1 col-1-harf padding-5 first">
    	<label>เลขที่เอกสาร</label>
        <label class="form-control input-sm text-center" disabled><?php echo $cs->reference; ?></label>
    </div>
	<div class="col-sm-1 col-1-harf padding-5">
    	<label>วันที่เอกสาร</label>
        <input type="text" class="form-control input-sm text-center input-header" id="dateAdd" placeholder="ระบุวันที่เอกสาร" value="<?php echo thaiDate($cs->date_add); ?>" disabled />
    </div>
    <div class="col-sm-1 col-1-harf padding-5">
    	<label>ใบส่งสินค้า</label>
        <input type="text" class="form-control input-sm text-center input-header" id="invoice" placeholder="อ้างอิงใบส่งสินค้า" value="<?php echo $cs->invoice; ?>" disabled />
    </div>
	<div class="col-sm-1 col-1-harf padding-5">
    	<label>ใบสั่งซื้อ</label>
        <input type="text" class="form-control input-sm text-center input-header" id="poCode" placeholder="ค้นหาใบสั่งซื้อ" value="<?php echo $cs->po; ?>" disabled />
    </div>
    <div class="col-sm-4 padding-5">
    	<label>หมายเหตุ</label>
        <input type="text" class="form-control input-sm input-header" id="remark" placeholder="ระบุหมายเหตุเอกสาร (ถ้ามี)" value="<?php echo $cs->remark; ?>" disabled />
    </div>
<?php if( $add ) : ?>    
    <div class="col-sm-1 padding-5">
    	<label class="display-block not-show">button</label>
        <button type="button" class="btn btn-sm btn-warning btn-block" id="btn-edit" onClick="getEdit()"><i class="fa fa-pencil"></i> แก้ไข</button>
        <button type="button" class="btn btn-sm btn-success btn-block hide" id="btn-update" onclick="saveEdit()"><i class="fa fa-save"></i> บันทึก</button>
    </div>
<?php endif; ?>  
	<div class="col-sm-1 padding-5 last">
    	<label class="display-block not-show">SW</label>
        <button type="button" class="btn btn-sm btn-primary btn-block" onclick="useBarcode()"><i class="fa fa-barcode"></i> ใช้บาร์โค้ด</button>
    </div>  
</div>
<hr class="margin-top-15"/>
<div class="row">
    <div class="col-sm-3 padding-5 first">
    	<label>ชื่อโซน</label>
        <input type="text" class="form-control input-sm text-center zone" id="zoneName" placeholder="ค้นหาชื่อโซน"  autofocus/>
    </div>
    <div class="col-sm-3 padding-5 first">
    	<label>สินค้า</label>
        <input type="text" class="form-control input-sm text-center" id="styleCode" placeholder="ระบุรุ่นสินค้า" disabled />
    </div>
    <div class="col-sm-1 padding-5 last">
    	<label class="display-block not-show">product</label>
        <button type="button" class="btn btn-sm btn-primary btn-block" id="btn-get-product" onClick="getProductGrid()" disabled><i class="fa fa-tags"></i> ตกลง</button>
    </div>
    <div class="col-sm-2">
    	<label class="display-block not-show">change</label>
        <button type="button" class="btn btn-sm btn-info btn-block" onClick="changeZone()"><i class="fa fa-retweet"></i> เปลี่ยนโซน</button>
    </div>
    <input type="hidden" id="id_receive_product" value="<?php echo $id_receive_product; ?>" />
    <input type="hidden" id="id_zone" />
</div>


<script src="script/receive_product/receive_product_by_keyboard.js"></script>