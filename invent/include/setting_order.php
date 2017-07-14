<?php
	$allowUnderZero	= getConfig('ALLOW_UNDER_ZERO');
	$yes	= $allowUnderZero == 1 ? 'btn-success ' : '';
	$no	= $allowUnderZero == 0 ? 'btn-danger ' : '' ;

?>
<div class="tab-pane fade" id="order">
<form id="orderForm">
	<div class="row">
    
    	<div class="col-sm-3"><span class="form-control left-label">สั่งสินค้าโดยไม่มีสต็อก</span></div>
        <div class="col-sm-9">
            <div class="btn-group input-small">
                <button type="button" class="btn btn-sm <?php echo $yes; ?>width-50"  id="btn-allow" onClick="allow()">เปิด</button>
                <button type="button" class="btn btn-sm <?php echo $no; ?>width-50"  id="btn-not-allow" onClick="notAllow()">ปิด</button>
            </div>
            <span class="help-block">อนุญาติให้สั่งสินค้าได้โดยไม่ต้องมีสินค้าคงเหลือในสต็อก</span>
            <input type="hidden" name="ALLOW_UNDER_ZERO" id="allowUnderZero" value="<?php echo $allowUnderZero; ?>" />
        </div>
        <div class="divider-hidden"></div>
        
    	<div class="col-sm-3"><span class="form-control left-label">อายุของออเดอร์ ( วัน )</span></div>
        <div class="col-sm-9">
            <input type="text" class="form-control input-sm input-mini input-line" name="ORDER_EXPIRATION" id="orderAge" value="<?php echo getConfig('ORDER_EXPIRATION'); ?>" />
            <span class="help-block">กำหนดวันหมดอายุของออเดอร์ หากออเดอร์อยู่ในสถานะ รอการชำระเงิน, รอจัดสินค้า หรือ ไม่บันทึก เกินกว่าจำนวนวันที่กำหนด</span>
        </div>
        <div class="divider-hidden"></div>
        
        <div class="col-sm-9 col-sm-offset-3">
			<button type="button" class="btn btn-sm btn-success input-mini" onClick="updateConfig('orderForm')"><i class="fa fa-save"></i> บันทึก</button>
		</div>
		<div class="divider-hidden"></div>
    
    </div>
</form>
</div><!--- Tab-pane --->