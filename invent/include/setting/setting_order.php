<?php
//--- การใช้งานเครดิตลิมิต
$isLimit = getConfig('USE_CREDIT_LIMIT');
$btn_limit = $isLimit == 1 ? 'btn-success' : '';
$btn_no_limit = $isLimit == 0 ? 'btn-danger' : '';

//---- อนุญาติให้แก้ไขต้นทุนในออเดอร์หรือไม่
$editCost = getConfig('ALLOW_EDIT_COST');
$btn_cost_yes = $editCost == 1 ? 'btn-success' : '';
$btn_cost_no = $editCost == 0 ? 'btn-danger' : '';

//---- อนุญาติให้แก้ไขราคาในออเดอร์หรือไม่
$editPrice = getConfig('ALLOW_EDIT_PRICE');
$btn_price_yes = $editPrice == 1 ? 'btn-success' : '';
$btn_price_no = $editPrice == 0 ? 'btn-danger' : '';

//--- อนุญาติให้แก้ไขส่วนลดในออเดอร์หรือไม่
$editDisc = getConfig('ALLOW_EDIT_DISCOUNT');
$btn_disc_yes = $editDisc == 1 ? 'btn-success' : '';
$btn_disc_no = $editDisc == 0 ? 'btn-danger' : '';

?>
<div class="tab-pane fade" id="order">
<form id="orderForm">
	<div class="row">
		<div class="col-sm-3"><span class="form-control left-label">อายุของออเดอร์ ( วัน )</span></div>
    <div class="col-sm-9">
      <input type="text" class="form-control input-sm input-mini input-line" name="ORDER_EXPIRATION" id="orderAge" value="<?php echo getConfig('ORDER_EXPIRATION'); ?>" />
      <span class="help-block">กำหนดวันหมดอายุของออเดอร์ หากออเดอร์อยู่ในสถานะ รอการชำระเงิน, รอจัดสินค้า หรือ ไม่บันทึก เกินกว่าจำนวนวันที่กำหนด</span>
    </div>
    <div class="divider-hidden"></div>

		<div class="col-sm-3"><span class="form-control left-label">การจำกัดการแสดงผลสต็อก</span></div>
		<div class="col-sm-9">
			<input type="text" class="form-control input-sm input-mini input-line" name="MAX_SHOW_STOCK" id="stockFilter" value="<?php echo getConfig('MAX_SHOW_STOCK'); ?>" />
			<span class="help-block">กำหนดจำนวนสินค้าคงเหลือสูงสุดที่จะแสดงใหเห็น ถ้าไม่ต้องการใช้กำหนดเป็น 0 </span>
		</div>
		<div class="divider-hidden"></div>

		<div class="col-sm-3"><span class="form-control left-label">การจำกัดวงเงินเครติด</span></div>
		<div class="col-sm-9">
			<div class="btn-group input-small">
				<button type="button" class="btn btn-sm <?php echo $btn_limit; ?>" style="width:50%;" id="btn-credit-yes" onClick="toggleCreditLimit(1)">เปิด</button>
				<button type="button" class="btn btn-sm <?php echo $btn_no_limit; ?>" style="width:50%;" id="btn-credit-no" onClick="toggleCreditLimit(0)">ปิด</button>
			</div>
			<span class="help-block">กรณีปิดจะไม่มีการตรวจสอบวงเงินเครดิตคงเหลือ โปรดใช้ความระมัดระวังในการกำหนดค่านี้</span>
			<input type="hidden" name="USE_CREDIT_LIMIT" id="creditLimit" value="<?php echo $isLimit; ?>" />
		</div>
		<div class="divider-hidden"></div>

		<div class="col-sm-3"><span class="form-control left-label">การแก้ไขส่วนลดในออเดอร์</span></div>
		<div class="col-sm-9">
			<div class="btn-group input-small">
				<button type="button" class="btn btn-sm <?php echo $btn_disc_yes; ?>" style="width:50%;" id="btn-disc-yes" onClick="toggleEditDiscount(1)">เปิด</button>
				<button type="button" class="btn btn-sm <?php echo $btn_disc_no; ?>" style="width:50%;" id="btn-disc-no" onClick="toggleEditDiscount(0)">ปิด</button>
			</div>
			<span class="help-block">กรณีปิดจะไม่สามารแก้ไขส่วนลดในออเดอร์ได้ ส่วนลดจะถูกคำนวณโดยระบบเท่านั้น</span>
			<input type="hidden" name="ALLOW_EDIT_DISCOUNT" id="allow-edit-discount" value="<?php echo $editDisc; ?>" />
		</div>
		<div class="divider-hidden"></div>

		<div class="col-sm-3"><span class="form-control left-label">การแก้ไขราคาในออเดอร์</span></div>
		<div class="col-sm-9">
			<div class="btn-group input-small">
				<button type="button" class="btn btn-sm <?php echo $btn_price_yes; ?>" style="width:50%;" id="btn-price-yes" onClick="toggleEditPrice(1)">เปิด</button>
				<button type="button" class="btn btn-sm <?php echo $btn_price_no; ?>" style="width:50%;" id="btn-price-no" onClick="toggleEditPrice(0)">ปิด</button>
			</div>
			<span class="help-block">กรณีปิดจะไม่สามารแก้ไขราคาขายสินค้าในออเดอร์ได้ จะใช้ราคาขายในระบบเท่านั้น</span>
			<input type="hidden" name="ALLOW_EDIT_PRICE" id="allow-edit-price" value="<?php echo $editPrice; ?>" />
		</div>
		<div class="divider-hidden"></div>

		<div class="col-sm-3"><span class="form-control left-label">การแก้ไขต้นทุนในออเดอร์</span></div>
		<div class="col-sm-9">
			<div class="btn-group input-small">
				<button type="button" class="btn btn-sm <?php echo $btn_cost_yes; ?>" style="width:50%;" id="btn-cost-yes" onClick="toggleEditCost(1)">เปิด</button>
				<button type="button" class="btn btn-sm <?php echo $btn_cost_no; ?>" style="width:50%;" id="btn-cost-no" onClick="toggleEditCost(0)">ปิด</button>
			</div>
			<span class="help-block">กรณีปิดจะไม่สามารแก้ไขต้นทุนสินค้าในออเดอร์ได้ จะใช้ต้นทุนในระบบเท่านั้น</span>
			<input type="hidden" name="ALLOW_EDIT_COST" id="allow-edit-cost" value="<?php echo $editCost; ?>" />
		</div>
		<div class="divider-hidden"></div>


    <div class="col-sm-9 col-sm-offset-3">
			<button type="button" class="btn btn-sm btn-success input-mini" onClick="updateConfig('orderForm')"><i class="fa fa-save"></i> บันทึก</button>
		</div>
		<div class="divider-hidden"></div>

  </div>
</form>
</div><!--- Tab-pane --->
