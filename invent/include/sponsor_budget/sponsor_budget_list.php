<div class="container">
<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-credit-card"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
    <?php if( $add ) : ?>
      <button type="button" class="btn btn-sm btn-success" onclick="goAdd()"><i class="fa fa-plus"></i> เพิ่มใหม่</button>
    <?php endif; ?>
    </p>
  </div>
</div>

<hr/>

<?php
//--  ค้นหาชื่อผู้รับ
$sName = getFilter('sName', 'sSponsorName', '');

//--- ค้นหาเลขที่เอกสาร/สัญญา
$sCode = getFilter('sCode', 'sSponsorCode', '');

//--- ค้นหาตามปีงบประมาณ
$sYear = getFilter('sYear', 'sYear', '');

//--- ค้นหาตามสถานะ
$sActive = getFilter('sActive', 'sActive', 2);

$btn_all      = $sActive == 2 ? 'btn-primary' : '';
$btn_active   = $sActive == 1 ? 'btn-primary' : '';
$btn_dactive  = $sActive == 0 ? 'btn-primary' : '';
?>

<form id="searchForm" method="post">
<div class="row">
  <div class="col-sm-3 padding-5 first">
    <label>ผู้รับ</label>
    <input type="text" class="form-control input-sm text-center search-box" id="sName" name="sName" value="<?php echo $sName; ?>" placeholder="กรองตามชื่อผู้รับ" autofocus />
  </div>

  <div class="col-sm-2 padding-5">
    <label>เลขที่เอกสาร/เลขที่สัญญา</label>
    <input type="text" class="form-control input-sm text-center search-box" id="sCode" name="sCode" value="<?php echo $sCode; ?>" placeholder="กรองตามเอกสาร/สัญญา" />
  </div>

  <div class="col-sm-2 padding-5">
    <label>ปีงบประมาณ</label>
    <input type="text" class="form-control input-sm text-center search-box" id="sYear" name="sYear" value="<?php echo $sYear; ?>" placeholder="กรองตามปีงบประมาณ" />
  </div>

  <div class="col-sm-3 padding-5">
    <label class="display-block not-show">active</label>
    <div class="btn-group width-100">
      <button type="button" class="btn btn-sm <?php echo $btn_all; ?> width-33" id="btn_all" onclick="toggleActive(2)">ทั้งหมด</button>
      <button type="button" class="btn btn-sm <?php echo $btn_active; ?> width-33" id="btn_active" onclick="toggleActive(1)">ใช้งานอยู่</button>
      <button type="button" class="btn btn-sm <?php echo $btn_dactive; ?> width-34" id="btn_dactive" onclick="toggleActive(0)">ไม่ได้ใช้งาน</button>
    </div>
  </div>

<div class="col-sm-1 padding-5">
  <label class="display-block not-show">apply</label>
  <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
</div>

<div class="col-sm-1 padding-5 last">
  <label class="display-block not-show">reset</label>
  <button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
</div>

<input type="hidden" name="sActive" id="sActive" value="<?php echo $sActive; ?>" />

</div>

</form>
<hr class="margin-top-10 margin-bottom-10"/>

<?php
  $where = "WHERE id != 0 ";

  if( $sName != '')
  {
    createCookie('sSponsorName', $sName);
    $where .= "AND name LIKE '%".$sName."' ";
  }

  if( $sCode != '')
  {
    createCookie('sSponsorCode', $sCode);
    $where .= "AND reference LIKE '%".$sCode."' ";
  }

  if( $sYear != '')
  {
    createCookie('sYear', $sYear);

    $where .= "AND year = '".dbYear($sYear)."' ";
  }

  if( $sActive != 2)
  {
    createCookie('sActive', $sActive);
    $where .= "AND active = ".$sActive." ";
  }

 ?>

</div><!-- Container -->
