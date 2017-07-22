
<div class="tab-pane fade active in" id="page1">
<form id="orderForm">
	<div class="row">
    	<div class="col-sm-3"><span class="form-control left-label text-right">รหัส</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-small"><?php echo $customer->code; ?></label>
            <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
        
        
        <div class="col-sm-3"><span class="form-control left-label text-right">คำนำหน้า</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-medium"><?php echo $customer->pre_name; ?></label>
            <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
        
        
        <div class="col-sm-3"><span class="form-control left-label text-right">ชื่อ - สกุล ลูกค้า</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-400"><?php echo $customer->name; ?></label>
            <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
       
      
              
        <div class="col-sm-3"><span class="form-control left-label text-right">โทรศัพท์</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-medium"><?php echo $customer->tel; ?></label>
            <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
        
        
        <div class="col-sm-3"><span class="form-control left-label text-right">แฟกซ์</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-medium"><?php echo $customer->fax; ?></label>
            <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
        
       
        
        <div class="col-sm-3"><span class="form-control left-label text-right">อีเมล์</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-large"><?php echo $customer->email; ?></label>
            <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
       
        
        <div class="col-sm-3"><span class="form-control left-label text-right">สถานะ</span></div>
        <div class="col-sm-9">
          	<label class=""><?php echo isActived($customer->active); ?></label>
            <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
      
       
    
    </div>
</form>
</div><!--- Tab-pane --->