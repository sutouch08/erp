<?php
$id = isset($_GET['id_policy']) ? $_GET['id_policy'] : FALSE;
$policy =  new discount_policy($id);
$reference = $id === FALSE ? $policy->getNewReference() : $policy->reference;
//--- ถ้ามีไอดีแล้ว
$disabled = $id === FALSE ? '' : 'disabled';
$startDate = $id === FALSE ? '' : thaiDate($policy->date_start);
$endDate = $id === FALSE ? '' : thaiDate($policy->date_end);
?>


<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-exclamation-triangle"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
  <?php echo goBackButton(); ?>
  <?php if( $add ) : ?>
    <button type="button" class="btn btn-sm btn-info" onclick="newRule()"><i class="fa fa-plus"></i> เพิ่มกฏ</button>
    <button type="button" class="btn btn-sm btn-success hide" id="btn-save-rule" onclick="saveRule()"><i class="fa fa-save"></i> บันทึก</button>
  <?php endif; ?>
    </p>
  </div>
</div>
<hr/>

<div class="row">
  <div class="col-sm-2 padding-5 first">
    <label>เลขที่นโยบาย</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $reference; ?>" disabled />
  </div>
  <div class="col-sm-5 padding-5">
    <label>ชื่อนโยบาย</label>
    <input type="text" maxlength="150" class="form-control input-sm header-box" id="policyName" placeholder="กำหนดชื่อนโยบาย (ไม่เกิน 150 ตัวอักษร)" value="<?php echo $policy->name; ?>" <?php echo $disabled; ?> autofocus />
  </div>
  <div class="col-sm-1 col-1-harf padding-5">
    <label>เริ่มต้น</label>
    <input type="text" class="form-control input-sm text-center header-box" id="fromDate" placeholder="วันที่เริ่มต้น" value="<?php echo $startDate; ?>" <?php echo $disabled; ?> />
  </div>
  <div class="col-sm-1 col-1-harf padding-5">
    <label>เริ่มต้น</label>
    <input type="text" class="form-control input-sm text-center header-box" id="toDate" placeholder="วันที่สิ้นสุด" value="<?php echo $endDate; ?>" <?php echo $disabled; ?> />
  </div>
  <?php if( $add && $id === FALSE ) : ?>
  <div class="col-sm-1 col-1-harf padding-5">
    <label class="display-block not-show">button</label>
    <button type="button" class="btn btn-sm btn-success btn-block" onclick="addNew()"><i class="fa fa-save"></i> บันทึก</button>
  </div>
  <?php endif; ?>
  <?php if( $id !== FALSE && $edit ) : ?>
    <div class="col-sm-2 padding-5 last">
      <label class="display-block not-show">button</label>
      <button type="button" class="btn btn-sm btn-warning btn-block" id="btn-edit" onclick="getEdit()"><i class="fa fa-pencil"></i> แก้ไข</button>
      <button type="button" class="btn btn-sm btn-success btn-block hide" id="btn-update" onclick="update()"><i class="fa fa-save"></i> บันทึก</button>
    </div>
  <?php endif; ?>
  <input type="hidden" id="id_policy" value="<?php echo $policy->id; ?>" />
  <input type="hidden" id="countNewRule" value="0" />
</div>

<hr class="margin-top-12 margin-bottom-15" />

<?php include 'include/policy/rule_list.php'; ?>

<script src="script/policy/policy_add.js"></script>
<script src="script/rule/rule_add.js"></script>
