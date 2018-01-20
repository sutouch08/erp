<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-check"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
      <?php if($add) : ?>
        <button type="button" class="btn btn-sm btn-success" onclick="goAdd()"><i class="fa fa-plus"></i> เพิ่มใหม่</button>
      <?php endif; ?>
    </p>
  </div>
</div>
<hr/>

<?php
//--- reference
$sCode = getFilter('sCode', 'sCheckCode', '');

//--- customer name
$sCus  = getFilter('sCus', 'sCheckCus', '');

//--- zone name
$sZone = getFilter('sZone', 'sCheckZone', '');

//--- document date from
$fromDate = getFilter('fromDate', 'fromDate', '');

//--- document date end
$toDate = getFilter('toDate', 'toDate', '');

//--- is document saved ?
$sStatus = getFilter('sStatus', 'sCheckStatus', '');

//--- is document already link to consign order or not
$sValid  = getFilter('sValid', 'sCheckValid', '');

//----  document has save and not save add class to btn
$btn_s_all = $sStatus == '' ? 'btn-primary' : '';

//--- document has saved only add class to btn
$btn_s_saved = $sStatus == 1 ? 'btn-primary' : '';

//--- document has not saved only add class to btn
$btn_s_not_saved = $sStatus == 0 ? 'btn-primary' : '';

//--- add class to btn  if selected option (all)
$btn_v_all = $sValid == '' ? 'btn-primary' : '';

//--- add class to btn if selected option (valid)
$btn_v_valid = $sValid == '' ? 'btn-primary' : '';

//--- add class to btn if selected option (not valid)
$btn_v_not_valid = $sValid == '' ? 'btn-primary' : '';

 ?>
<div class="row">
  <div class="col-sm-2 padding-5 first">
    <label class="display-block">เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm text-center search-box" name="sCode" id="sCode" value="<?php echo $sCode; ?>" />
  </div>

  <div class="col-sm-2 padding-5">
    <label class="display-block">ลูกค้า</label>
    <input type="text" class="form-control input-sm text-center search-box" name="sCus" id="sCus" value="<?php echo $sCus; ?>" />
  </div>

  <div class="col-sm-2 padding-5">
    <label class="display-block">โซน</label>
    <input type="text" class="form-control input-sm text-center search-box" name="sZone" id="sZone" value="<?php echo $sZone; ?>" />
  </div>

  <div class="col-sm-2 padding-5">
    <label class="display-block">วันที่</label>
    <input type="text" class="form-control input-sm input-discount text-center" name="fromDate" id="fromDate" value="<?php echo $fromDate; ?>" />
    <input type="text" class="form-control input-sm input-unit text-center" name="toDate" id="toDate" value="<?php echo $toDate; ?>" />
  </div>
</div>
