<?php
include 'function/consign_helper.php';
include 'function/zone_helper.php';
include 'function/shop_helper.php';
include 'function/event_helper.php';
 ?>
<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-check-square-o"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
<?php if( $add ) : ?>
      <button type="button" class="btn btn-sm btn-success" onclick="goAdd()">
        <i class="fa fa-plus"></i> เพิ่มใหม่
      </button>
<?php endif; ?>
    </p>
  </div>
</div>

<hr/>

<?php
$sCode = getFilter('sCode', 'sConsignCode', '');
$sCus  = getFilter('sCus', 'sConsignCus', '');
$sZone = getFilter('sZone', 'sConsignZone', '');
$sShop = getFilter('sShop', 'sShop', '');
$sEvent = getFilter('sEvent', 'sEvent');
$fromDate = getFilter('fromDate', 'fromDate', '');
$toDate = getFilter('toDate', 'toDate', '');

?>
<form id="searchForm" method="post">
<div class="row">
  <div class="col-sm-1 col-1-harf  padding-5 first">
    <label>เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm search-box text-center" name="sCode" id="sCode" value="<?php echo $sCode; ?>" autofocus />
  </div>

  <div class="col-sm-2  padding-5">
    <label>ลูกค้า</label>
    <input type="text" class="form-control input-sm search-box text-center" name="sCus" id="sCus" value="<?php echo $sCus; ?>" />
  </div>

  <div class="col-sm-3 padding-5">
    <label>โซน</label>
    <input type="text" class="form-control input-sm search-box text-center" name="sZone" id="sZone" value="<?php echo $sZone; ?>" />
  </div>

  <div class="col-sm-2 padding-5">
    <label class="display-block">วันที่</label>
    <input type="text" class="form-control input-sm input-discount text-center" name="fromDate" id="fromDate" value="<?php echo $fromDate; ?>" />
    <input type="text" class="form-control input-sm input-unit text-center" name="toDate" id="toDate" value="<?php echo $toDate; ?>" />
  </div>

  <div class="col-sm-1 padding-5">
    <label class="display-block not-show">search</label>
    <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
  </div>

  <div class="col-sm-1 padding-5 last">
    <label class="display-block not-show">Reset</label>
    <button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
  </div>
</div>
</form>
<hr class="margin-top-15"/>

<?php
createCookie('sConsignCode', $sCode);
createCookie('sConsignCus', $sCus);
createCookie('sConsignZone', $sZone);
createCookie('fromDate', $fromDate);
createCookie('toDate', $toDate);

$where = "WHERE id != 0 ";

if( $sCode != '')
{
  $where .= "AND reference LIKE '%".$sCode."%' ";
}

if( $sCus != '')
{
  $where .= "AND id_customer IN(".getCustomerIn($sCus).") ";
}

if( $sZone != '')
{
  $where .= "AND id_zone IN(".getZoneIn($sZone).") ";
}

if( $fromDate != '' && $toDate != '')
{
  $where .= "AND date_add >= '".fromDate($fromDate)."' ";
  $where .= "AND date_add <= '".toDate($toDate)."' ";
}

$where .= "ORDER BY date_add DESC";

$paginator = new paginator();
$get_rows = get_rows();
$paginator->Per_Page('tbl_consign', $where, $get_rows);


$qs = dbQuery("SELECT * FROM tbl_consign ".$where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);

 ?>

 <div class="row">
   <div class="col-sm-7">
     <?php $paginator->display($get_rows, 'index.php?content=consign'); ?>
   </div>
   <div class="col-sm-5" style="padding-top:25px;">
     <p class="pull-right top-p">
       <span>ว่าง</span><span class="margin-right-15"> = ปกติ</span>
       <span class="red">NC</span><span class="margin-right-15"> = ยังไม่บันทึก</span>
       <span class="red">NE</span><span class="margin-right-15"> = ยังไม่ส่งออก</span>
       <span class="red">CN</span><span class=""> = ยกเลิก</span>
     </p>
   </div>
 </div>
 <div class="row">
   <div class="col-sm-12">
     <table class="table table-striped table-bordered">
       <thead>
         <tr class="font-size-12">
           <th class="width-5 text-center">ลำดับ</th>
           <th class="width-10 text-center">วันที่</th>
           <th class="width-10 text-center">เลขที่เอกสาร</th>
           <th class="width-30">ลูกค้า</th>
           <th class="width-30">โซน</th>
           <th class="width-5 text-center">สถานะ</th>
           <th></th>
         </tr>
       </thead>
       <tbody>
<?php if( dbNumRows($qs) > 0 ) : ?>
<?php   $zone = new zone(); ?>
<?php   $no = row_no(); ?>
<?php   while($rs = dbFetchObject($qs)) : ?>
<?php    $pos = $rs->id_shop != 0 ? shopName($rs->id_shop) : ($rs->id_event != 0 ? eventName($rs->id_event) : ''); ?>
        <tr class="font-size-12" id="row-<?php echo $rs->id; ?>">
          <td class="middle text-center">
            <?php echo $no; ?>
          </td>
          <td class="middle text-center">
            <?php echo thaiDate($rs->date_add); ?>
          </td>
          <td class="middle text-center">
            <?php echo $rs->reference; ?>
          </td>
          <td class="middle">
            <?php echo customerName($rs->id_customer); ?>
          </td>
          <td class="middle">
            <?php echo $zone->getName($rs->id_zone); ?>
          </td>
          <td class="middle text-center" id="xLabel-<?php echo $rs->id; ?>">
            <?php echo consignStatusLabel($rs->is_so, $rs->isExport, $rs->isSaved, $rs->isCancle); ?>
          </td>
          <td class="middle text-right">
        <?php if($rs->isCancle == 0) : ?>
            <button type="button" class="btn btn-xs btn-info" onclick="goDetail(<?php echo $rs->id;?>)"><i class="fa fa-eye"></i></button>

          <?php if($edit && $rs->isSaved == 0 ): ?>
            <button type="button" class="btn btn-xs btn-warning" id="btn-edit-<?php echo $rs->id; ?>" onclick="goAdd(<?php echo $rs->id;?>)"><i class="fa fa-pencil"></i></button>
          <?php endif; ?>

          <?php if( $delete && $rs->isSaved == 0 ): ?>
            <button type="button" class="btn btn-xs btn-danger" id="btn-delete-<?php echo $rs->id; ?>" onclick="goDelete(<?php echo $rs->id;?>, '<?php echo $rs->reference; ?>')">
              <i class="fa fa-trash"></i>
            </button>
          <?php endif; ?>
        <?php endif; ?>
          </td>
        </tr>
<?php    $no++; ?>
<?php   endwhile; ?>

<?php else : ?>

<?php endif; ?>
       </tbody>

     </table>
   </div>
 </div>


<script src="script/consign/consign_list.js"></script>
