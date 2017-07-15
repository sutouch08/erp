<?php $cg = new customer_group($customer->group_id); ?>
<?php $ca = new customer_area($customer->area_id); ?>

<div class="tab-pane fade" id="page2">
<form id="orderForm">
	<div class="row">
    	<div class="col-sm-3"><span class="form-control left-label">เลขประจำตัวประชาชน</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-small input-line"><?php echo $customer->m_id; ?></label>
            <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
        <div class="divider-hidden"></div>
        
        <div class="col-sm-3"><span class="form-control left-label">เลขประจำตัวผู้เสียภาษี</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-medium input-line"><?php echo $customer->tax_id; ?></label>
            <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
        <div class="divider-hidden"></div>
        
        <div class="col-sm-3"><span class="form-control left-label">ชื่อผู้ติดต่อ</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-400 input-line"><?php echo $customer->contact; ?></label>
            <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
        <div class="divider-hidden"></div>
      
              
        <div class="col-sm-3"><span class="form-control left-label">กลุ่มลูกค้า</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-medium input-line"><?php echo $cg->name; ?></label>
            <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
        <div class="divider-hidden"></div>
        
        <div class="col-sm-3"><span class="form-control left-label">เขตลูกค้า</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-medium input-line"><?php echo $ca->name; ?></label>
            <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
        <div class="divider-hidden"></div>
       
        
        <div class="col-sm-3"><span class="form-control left-label">พนักงานขาย</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-large input-line"><?php echo $customer->email; ?></label>
            <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
        <div class="divider-hidden"></div>
        
        <div class="col-sm-3"><span class="form-control left-label">วงเงินเครดิต</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-large input-line"><?php echo $customer->email; ?></label>
            <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
        <div class="divider-hidden"></div>
        
        <div class="col-sm-3"><span class="form-control left-label">เครดิตเทอม</span></div>
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