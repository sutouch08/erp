<?php $cg = new customer_group($customer->group_id); ?>
<?php $ca = new customer_area($customer->area_id); ?>

<div class="tab-pane fade" id="page2">
<form id="orderForm">
	<div class="row">
    	<div class="col-sm-3"><span class="form-control left-label text-right">เลขประจำตัวประชาชน</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-medium"><?php echo $customer->m_id; ?></label>
            <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
       
        
        <div class="col-sm-3"><span class="form-control left-label text-right">เลขประจำตัวผู้เสียภาษี</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-medium"><?php echo $customer->tax_id; ?></label>
            <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
       
        
        <div class="col-sm-3"><span class="form-control left-label text-right">ชื่อผู้ติดต่อ</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-400"><?php echo $customer->contact; ?></label>
            <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
       
      
              
        <div class="col-sm-3"><span class="form-control left-label text-right">กลุ่มลูกค้า</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-medium"><?php echo $cg->name; ?></label>
            <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
        
        
        <div class="col-sm-3"><span class="form-control left-label text-right">เขตลูกค้า</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-medium"><?php echo $ca->name; ?></label>
            <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
        
       
        
        <div class="col-sm-3"><span class="form-control left-label text-right">พนักงานขาย</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-large"><?php echo $customer->email; ?></label>
            <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
       
        
        <div class="col-sm-3"><span class="form-control left-label text-right">วงเงินเครดิต</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-large"><?php echo $customer->email; ?></label>
            <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
       
        
        <div class="col-sm-3"><span class="form-control left-label text-right">เครดิตเทอม</span></div>
        <div class="col-sm-9">
          	<label class="form-control input-sm input-large"><?php echo $customer->email; ?></label>
            <span class="help-block">ไม่สามารถแก้ไขจากตรงนี้ได้ หากต้องการแก้ไขให้ทำการแก้ไขที่ Formula แล้วทำการ Sync ข้อมูลใหม่</span>
        </div>
  
    </div>
</form>
</div><!--- Tab-pane --->