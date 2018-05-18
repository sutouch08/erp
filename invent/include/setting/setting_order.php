<div class="tab-pane fade" id="order">
<form id="orderForm">
	<div class="row">    
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
