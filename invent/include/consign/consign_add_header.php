
<div class="row">
  <div class="col-sm-1 col-1-harf padding-5 first">
    <label>เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $cs->reference; ?>" disabled />
  </div>

  <div class="col-sm-1 col-1-harf padding-5">
    <label>วันที่</label>
    <input type="text" class="form-control input-sm text-center header-box" id="date_add" value="<?php echo thaiDate($cs->date_add); ?>" <?php echo $disabled; ?> />
  </div>

  <div class="col-sm-3 padding-5">
    <label>ลูกค้า</label>
    <input type="text" class="form-control input-sm header-box" id="customerName" value="<?php echo $customer->getName($cs->id_customer); ?>" <?php echo $disabled; ?> />
  </div>

  <div class="col-sm-3 padding-5">
    <label>โซน</label>
    <input type="text" class="form-control input-sm header-box" id="zoneName" value="<?php echo $zone->name; ?>" <?php echo $disabled; ?> />
  </div>

  <div class="col-sm-3 padding-5 last">
    <label>จุดขาย [shop]</label>
    <input type="text" class="form-control input-sm header-box" id="shopName" value="<?php echo $shop->name; ?>" <?php echo $disabled; ?> />
  </div>

  <div class="col-sm-2 padding-5 first">
    <label class="display-block">ใบกำกับ</label>
    <select id="isSo" class="form-control input-sm header-box" <?php echo $disabled; ?>>
      <option value="1" <?php echo isSelected(1, $cs->is_so); ?>>เปิดใบกำกับ</option>
      <option value="0" <?php echo isSelected(0, $cs->is_so); ?>>ไม่เปิด</option>
    </select>
  </div>

  <div class="col-sm-4 padding-5">
    <label>งานขาย [Event]</label>
    <input type="text" class="form-control input-sm header-box" id="eventName" value="<?php echo $event->name; ?>" <?php echo $disabled; ?> />
  </div>

  <div class="col-sm-5 padding-5">
    <label>หมายเหตุ</label>
    <input type="text" class="form-control input-sm header-box" id="remark" maxlength="100" value="<?php echo $cs->remark; ?>" <?php echo $disabled; ?> />
  </div>

  <div class="col-sm-1 padding-5 last">
    <label class="display-block not-show">add</label>
<?php if( $id === FALSE ) : ?>
  <?php if( $add ) : ?>
    <button type="button" class="btn btn-sm btn-success btn-block" onclick="addNew()" ><i class="fa fa-plus"></i> เพิ่มเอกสาร</button>
  <?php endif; ?>
<?php else : ?>
  <?php if( $edit && $cs->isSaved == 0 && $cs->isExport == 0 && $cs->isCancle == 0 ) : ?>
    <button type="button" class="btn btn-sm btn-warning btn-block" id="btn-edit" onclick="getEdit()"><i class="fa fa-pencil"></i> แก้ไข</button>
    <button type="button" class="btn btn-sm btn-success btn-block hide" id="btn-update" onclick="checkUpdate()"><i class="fa fa-save"></i> อัพเดต</button>
  <?php endif; ?>
<?php endif; ?>
  </div>

  <input type="hidden" id="id_consign" value="<?php echo $cs->id; ?>" />
  <input type="hidden" id="id_customer" value="<?php echo $cs->id_customer; ?>" />
  <input type="hidden" id="id_zone" value="<?php echo $cs->id_zone; ?>" />
  <input type="hidden" id="id_shop" value="<?php echo $cs->id_shop; ?>" />
  <input type="hidden" id="id_event" value="<?php echo $cs->id_event; ?>" />
  <input type="hidden" id="id_zone_shop" value="<?php echo $cs->id_zone; ?>" />
  <input type="hidden" id="id_customer_shop" value="<?php echo $cs->id_customer; ?>" />
  <input type="hidden" id="allowUnderZero" value="<?php echo $allowUnderZero; ?>" />
</div><!--- /row --->

<hr class="margin-top-10 margin-bottom-15" />
