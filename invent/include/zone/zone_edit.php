<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-map-marker"></i> &nbsp; <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
      <?php echo goBackButton(); ?>
    </p>
  </div>
</div>
<hr class="margin-bottom-15"/>

<div class="row">

<?php $id_zone	= isset( $_GET['id_zone'] ) ? $_GET['id_zone'] : 0; ?>

<?php $rs			  = getZoneDetail($id_zone); ?>

<?php if( $rs !== FALSE ) : ?>

  <div class="col-sm-2">
    <label>คลังสินค้า</label>
    <select class="form-control input-sm" id="edit-zWH">
    <?php echo selectWarehouse($rs->id_warehouse); ?>
    </select>
    <span class="display-block margin-top-5 red not-show" id="edit-zWH-error">โปรดเลือกคลัง</span>
  </div>

  <div class="col-sm-2">
    <label>รหัสโซน</label>
    <input type="text" class="form-control input-sm" id="edit-zCode" placeholder="* จำเป็น | ห้ามซ้ำ" value="<?php echo $rs->barcode_zone; ?>" />
    <span class="display-block margin-top-5 red not-show" id="edit-zCode-error">รหัสซ้ำ</span>
  </div>

  <div class="col-sm-4">
    <label>ชื่อโซน</label>
    <input type="text" class="form-control input-sm" id="edit-zName" placeholder="* จำเป็น | ห้ามซ้ำ" value="<?php echo $rs->zone_name; ?>" />
    <span class="display-block margin-top-5 red not-show" id="edit-zName-error">ชื่อซ้ำ</span>
  </div>

  <div class="col-sm-3">
    <label>ลูกค้า[กรณีฝากขาย]</label>
    <input type="text" class="form-control input-sm" id="customer" placeholder="ลูกค้า" value="<?php echo customerName($rs->id_customer); ?>" />
    <span class="display-block margin-top-5 red not-show" id="customer-error">ชื่อซ้ำ</span>
  </div>

  <div class="col-sm-1">
  <?php if( $edit ) : ?>
    <label class="display-block not-show">Submit</label>
    <button type="button" class="btn btn-sm btn-success btn-block" onclick="saveEdit()">ปรับปรุง</button>
  <?php endif; ?>
  </div>

 <input type="hidden" id="id_zone" value="<?php echo $id_zone; ?>" />

 <input type="hidden" id="id_customer" value="<?php echo $rs->id_customer; ?>" />

 <?php else : ?>
    <div class="col-sm-12 text-center middle">
      <h4>ไม่พบข้อมูล</h4>
    </div>
  <?php endif; ?>
</div>

<script src="script/zone/zone_edit.js"></script>
