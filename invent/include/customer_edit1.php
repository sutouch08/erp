
<div class="tab-pane fade active in" id="page1">
<form id="orderForm">
	<div class="row">
    	<div class="col-sm-3"><span class="form-control left-label">รหัส</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-small input-line"><?php echo $customer->code; ?></label>
            <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
        <div class="divider-hidden"></div>
        
        <div class="col-sm-3"><span class="form-control left-label">คำนำหน้า</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-medium input-line"><?php echo $customer->pre_name; ?></label>
            <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
        <div class="divider-hidden"></div>
        
        <div class="col-sm-3"><span class="form-control left-label">ชื่อ - สกุล ลูกค้า</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-400 input-line"><?php echo $customer->name; ?></label>
            <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
        <div class="divider-hidden"></div>
      
              
        <div class="col-sm-3"><span class="form-control left-label">โทรศัพท์</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-medium input-line"><?php echo $customer->tel; ?></label>
            <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
        <div class="divider-hidden"></div>
        
        <div class="col-sm-3"><span class="form-control left-label">แฟกซ์</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-medium input-line"><?php echo $customer->fax; ?></label>
            <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
        <div class="divider-hidden"></div>
       
        
        <div class="col-sm-3"><span class="form-control left-label">อีเมล์</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-large input-line"><?php echo $customer->email; ?></label>
            <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
        <div class="divider-hidden"></div>
        
        <div class="col-sm-3"><span class="form-control left-label">สถานะ</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-small input-line"><?php echo isActived($customer->active); ?></label>
            <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
        <div class="divider-hidden"></div>
       
    
    </div>
</form>
</div><!--- Tab-pane --->