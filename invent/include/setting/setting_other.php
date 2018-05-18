   <div class="tab-pane fade" id="other">
            <?php $closed 	= getConfig('CLOSED');  ?>
            <?php $open		= $closed == 0 ? 'btn-success' : ''; 	?>
            <?php $close		= $closed == 1 ? 'btn-danger' : '';		?>
            <?php $shop		= getConfig('SHOP_OPEN'); 	?>
			<?php $sOpen		= $shop == 1 ? 'btn-success' : ''; ?>
            <?php $sClose		= $shop == 0 ? 'btn-danger' : ''; ?>
            	<form id="otherForm">
                <div class="row">
                	<?php if( $cando === TRUE ): //---- ถ้ามีสิทธิ์ปิดระบบ ---//	?>
                	<div class="col-sm-3"><span class="form-control left-label">ปิดระบบ</span></div>
                    <div class="col-sm-9">
                    	<div class="btn-group input-small">
                        	<button type="button" class="btn btn-sm <?php echo $open; ?>" style="width:50%;" id="btn-open" onClick="openSystem()">เปิด</button>
                            <button type="button" class="btn btn-sm <?php echo $close; ?>" style="width:50%;" id="btn-close" onClick="closeSystem()">ปิด</button>
                        </div>
                        <span class="help-block">กรณีปิดระบบจะไม่สามารถเข้าใช้งานระบบได้ในทุกส่วน โปรดใช้ความระมัดระวังในการกำหนดค่านี้</span>
                    	<input type="hidden" name="CLOSED" id="closed" value="<?php echo $closed; ?>" />
                    </div>
                    <div class="divider-hidden"></div>
                    <?php endif; ?>
                    
                    <div class="col-sm-3"><span class="form-control left-label">ข้อความแจ้งปิดระบบ</span></div>
                    <div class="col-sm-9">
                    	<textarea id="content" class="form-control input-sm input-500 input-line" rows="4" name="MAINTENANCE_MESSAGE" ><?php echo getConfig('MAINTENANCE_MESSAGE'); ?></textarea>
                        <span class="help-block">กำหนดข้อความที่จะแสดงบนหน้าเว็บเมื่อมีการปิดระบบ ( รองรับ HTML Code )</span>
					</div>                        
                    <div class="divider-hidden"></div>
                    
                    <div class="col-sm-3"><span class="form-control left-label">ID ของเว็บ</span></div>
                    <div class="col-sm-9">
                    	<input type="text" class="form-control input-sm input-mini input-line" name="ITEMS_GROUP" id="itemGroup" value="<?php echo getConfig('ITEMS_GROUP'); ?>" />
                        <span class="help-block">กำหนดตัวเลข ID ของเว็บเพื่อใช้ในการระบุสินค้าว่ามาจากเว็บไหน ใช้ในกรณีที่มีการส่งออกรายการสินค้าไปนำเข้า POS (กรณีมีหลายเว็บ แต่ละเว็บห้ามซ้ำกัน)</span>
                    </div>
                    <div class="divider-hidden"></div>
                    
                    <div class="col-sm-3"><span class="form-control left-label">อนุญาติให้ลูกค้าสั่งสินค้าเอง</span></div>
                    <div class="col-sm-9">
                    	<div class="btn-group input-small">
                        	<button type="button" class="btn btn-sm <?php echo $sOpen; ?>" style="width:50%;" id="btn-sopen" onClick="openShop()">เปิด</button>
                            <button type="button" class="btn btn-sm <?php echo $sClose; ?>" style="width:50%;" id="btn-sclose" onClick="closeShop()">ปิด</button>
                        </div>
                        <span class="help-block">เปิดหรือปิดการใช้งานหน้าเว็บสำหรับให้ลูกค้าสั่งเอง</span>
                    	<input type="hidden" name="SHOP_OPEN" id="shopOpen" value="<?php echo $shop; ?>" />
                    </div>
                    <div class="divider-hidden"></div>
                    
                    <div class="col-sm-3"><span class="form-control left-label">รับสินค้าเกินใบสั่งซื้อ ( % )</span></div>
                    <div class="col-sm-9">
                    	<input type="text" class="form-control input-sm input-mini input-line" name="RECEIVE_OVER_PO" id="overPO" value="<?php echo getConfig('RECEIVE_OVER_PO'); ?>" />
                        <span class="help-block">จำกัดการรับสินค้าเข้าเกินกว่ายอดในใบสั่งซื้อได้ไม่เกินกี่เปอร์เซ็น</span>
                    </div>
                    <div class="divider-hidden"></div>
                     
                     <div class="col-sm-9 col-sm-offset-3">
                    	<button type="button" class="btn btn-sm btn-success input-mini" onClick="updateConfig('otherForm')"><i class="fa fa-save"></i> บันทึก</button>
                    </div>
                    <div class="divider-hidden"></div>
                    
                    
                </div><!--/row-->
                </form>
            </div> 