<div class="tab-pane fade" id="document">
            	<form id="documentForm">
                <div class="row">
                	<div class="col-sm-3"><span class="form-control left-label">ขายสินค้า</span></div>
                    <div class="col-sm-9"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_ORDER" id="preOrder" value="<?php echo getConfig('PREFIX_ORDER'); ?>" /></div>
                    <div class="divider-hidden"></div>
                    
                    <div class="col-sm-3"><span class="form-control left-label">ฝากขาย</span></div>
                    <div class="col-sm-9"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_CONSIGNMENT" id="pConsignment" value="<?php echo getConfig('PREFIX_CONSIGNMENT'); ?>" /></div>
                    <div class="divider-hidden"></div>
                    
                    <div class="col-sm-3"><span class="form-control left-label">ตัดยอดฝากขาย</span></div>
                    <div class="col-sm-9"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_CONSIGN" id="preConsign" value="<?php echo getConfig('PREFIX_CONSIGN'); ?>" /></div>
                    <div class="divider-hidden"></div>
                    
                    <div class="col-sm-3"><span class="form-control left-label">ใบสั่งซื้อ</span></div>
                    <div class="col-sm-9"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_PO" id="prePO" value="<?php echo getConfig('PREFIX_PO'); ?>" /></div>
                    <div class="divider-hidden"></div>
                    
                    <div class="col-sm-3"><span class="form-control left-label">รับสินคาเข้าจากการซื้อ</span></div>
                    <div class="col-sm-9"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_RECIEVE" id="preReceiveFromPO" value="<?php echo getConfig('PREFIX_RECIEVE'); ?>" /></div>
                    <div class="divider-hidden"></div>
                    
                    <div class="col-sm-3"><span class="form-control left-label">รับสินค้าเข้าจากการแปรสภาพ</span></div>
                    <div class="col-sm-9">
                    	<input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_RECEIVE_TRANFORM" id="preReceiveFromTransform" value="<?php echo getConfig('PREFIX_RECEIVE_TRANFORM'); ?>" />
                    </div>
                    <div class="divider-hidden"></div>
                    
                    <div class="col-sm-3"><span class="form-control left-label">เบิกแปรสภาพ</span></div>
                    <div class="col-sm-9"><input type="text" class="form-control input-sm input-mini input-line" name="PREFIX_REQUISITION" id="preTransform" value="<?php echo getConfig('PREFIX_REQUISITION'); ?>" /></div>
                    <div class="divider-hidden"></div>
                    
                    <div class="col-sm-3"><span class="form-control left-label">ยืมสินค้า</span></div>
                    <div class="col-sm-9"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_LEND" id="preLend" value="<?php echo getConfig('PREFIX_LEND'); ?>" /></div>
                    <div class="divider-hidden"></div>
                    
                    <div class="col-sm-3"><span class="form-control left-label">เบิกสปอนเซอร์</span></div>
                    <div class="col-sm-9"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_SPONSOR" id="preSponsor" value="<?php echo getConfig('PREFIX_SPONSOR'); ?>" /></div>
                    <div class="divider-hidden"></div>
                    
                    <div class="col-sm-3"><span class="form-control left-label">เบิกอภินันท์</span></div>
                    <div class="col-sm-9"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_SUPPORT" id="preSupport" value="<?php echo getConfig('PREFIX_SUPPORT'); ?>" /></div>
                    <div class="divider-hidden"></div>
                    
                    <div class="col-sm-3"><span class="form-control left-label">คืนสินค้าจากการขาย</span></div>
                    <div class="col-sm-9"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_RETURN" value="<?php echo getConfig('PREFIX_RETURN'); ?>" /></div>
                    <div class="divider-hidden"></div>
                    
                    <div class="col-sm-3"><span class="form-control left-label">ค้นสินค้าจากการสปอนเซอร์</span></div>
                    <div class="col-sm-9"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_RETURN_SPONSOR" value="<?php echo getConfig('PREFIX_RETURN_SPONSOR'); ?>" /></div>
                    <div class="divider-hidden"></div>
                    
                    <div class="col-sm-3"><span class="form-control left-label">คืนสินค้าจากอภินันท์</span></div>
                    <div class="col-sm-9"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_RETURN_SUPPORT" value="<?php echo getConfig('PREFIX_RETURN_SUPPORT'); ?>" /></div>
                    <div class="divider-hidden"></div>
                    
                    <div class="col-sm-3"><span class="form-control left-label">ร้องขอสินค้า</span></div>
                    <div class="col-sm-9"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_REQUEST_ORDER" value="<?php echo getConfig('PREFIX_REQUEST_ORDER'); ?>" /></div>
                    <div class="divider-hidden"></div>
                    
                    <div class="col-sm-3"><span class="form-control left-label">กระทบยอด</span></div>
                    <div class="col-sm-9"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_CONSIGN_CHECK" value="<?php echo getConfig('PREFIX_CONSIGN_CHECK'); ?>" /></div>
                    <div class="divider-hidden"></div>
                    
                    <div class="col-sm-3"><span class="form-control left-label">โอนสินค้าระหว่างคลัง</span></div>
                    <div class="col-sm-9"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_TRANFER" id="preTransfer" value="<?php echo getConfig('PREFIX_TRANFER'); ?>" /></div>
                    <div class="divider-hidden"></div>
                    
                    <div class="col-sm-3"><span class="form-control left-label">ปรับยอดสต็อก</span></div>
                    <div class="col-sm-9"><input type="text" class="form-control input-sm input-mini input-line prefix" name="PREFIX_ADJUST" id="preAdjust" value="<?php echo getConfig('PREFIX_ADJUST'); ?>" /></div>
                    <div class="divider-hidden"></div>
                    
                    <div class="col-sm-9 col-sm-offset-3">
                    	<button type="button" class="btn btn-sm btn-success input-mini" onClick="updateConfig('documentForm')"><i class="fa fa-save"></i> บันทึก</button>
                    </div>
                    <div class="divider-hidden"></div>
                      
                </div><!--/ row -->
                </form>
            </div>