<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-random"></i> <?php echo $pageTitle; //--- index.php ?></h4>
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
include 'function/employee_helper.php';
include 'function/transfer_helper.php';
include 'function/warehouse_helper.php';

//--- ค้นหารหัสเอกสาร
$sCode = getFilter('sCode', 'sTransferCode', '');

//--- คลังต้นทาง
$fWh = getFilter('fWh', 'fWh', '');

//--- คลังปลายทาง
$tWh = getFilter('tWh', 'tWh', '');

//--- ค้นหาชื่อพนักงาน
$sEmp  = getFilter('sEmp', 'sEmp', '');

//--- ค้นตามวันที่เอกสาร (เริ่มต้น)
$fromDate = getFilter('fromDate', 'fromDate', '');

//--- ค้นตามวันที่เอกสาร (สิ้นสุด)
$toDate = getFilter('toDate', 'toDate','');

//--- ค้นตามสถานะเอกสาร  '' = ทั้งหมด / AS = บันทึกแล้ว / NC = ยังไม่สมบูรณ์ / NE = ยังไม่ส่งออก / CN = ยกเลิก
$sStatus = getFilter('sStatus', 'sStatus', '');

//--- ส่งออกไป IX
$ix = getFilter('ix', 'ix', 'all');

 ?>
<form id="searchForm" method="post">
<div class="row">
  <div class="col-sm-1 col-1-harf padding-5 first">
    <label>เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm text-center search-box" name="sCode" value="<?php echo $sCode; ?>" />
  </div>
  <div class="col-sm-1 col-1-harf padding-5">
    <label>คลังต้นทาง</label>
    <input type="text" class="form-control input-sm text-center search-box" name="fWh" value="<?php echo $fWh; ?>" />
  </div>
  <div class="col-sm-1 col-1-harf padding-5">
    <label>คลังปลายทาง</label>
    <input type="text" class="form-control input-sm text-center search-box" name="tWh" value="<?php echo $tWh; ?>" />
  </div>
  <!--
  <div class="col-sm-1 col-1-harf padding-5">
    <label>พนักงาน</label>
    <input type="text" class="form-control input-sm text-center search-box" name="sEmp" value="<?php echo $sEmp; ?>" />
  </div>
-->
  <div class="col-sm-1 col-1-harf padding-5">
    <label>สถานะ</label>
    <select class="form-control input-sm search-box" id="sStatus" name="sStatus">
      <option value="">ทั้งหมด</option>
      <option value="AS" <?PHP echo isSelected($sStatus, 'AS'); ?>>บันทึกแล้ว</option>
      <option value="NC" <?PHP echo isSelected($sStatus, 'NC'); ?>>ยังไม่บันทึก</option>
      <option value="NE" <?php echo isSelected($sStatus, 'NE'); ?>>ยังไม่ส่งออก</option>
      <option value="CN" <?php echo isSelected($sStatus, 'CN'); ?>>ยกเลิก</option>
    </select>
  </div>

  <div class="col-sm-1 col-1-harf padding-5">
    <label>ส่งออก IX</label>
    <select class="form-control input-sm search-box" id="ix" name="ix" onchange="getSearch()">
      <option value="all">ทั้งหมด</option>
      <option value="0" <?PHP echo isSelected($ix, '0'); ?>>ยังไม่ส่งออก</option>
      <option value="1" <?PHP echo isSelected($ix, '1'); ?>>ส่งออกแล้ว</option>
      <option value="3" <?php echo isSelected($ix, '3'); ?>>Error</option>
    </select>
  </div>
  <div class="col-sm-2 padding-5">
    <label class="display-block">วันที่</label>
    <input type="text" class="form-control input-sm input-discount text-center" id="fromDate" name="fromDate" value="<?php echo $fromDate; ?>" />
    <input type="text" class="form-control input-sm input-unit text-center" id="toDate" name="toDate" value="<?php echo $toDate; ?>" />
  </div>
  <div class="col-sm-1 padding-5">
    <label class="display-block not-show">ค้นหา</label>
    <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
  </div>
  <div class="col-sm-1 padding-5 last">
    <label class="display-block not-show">Reset</label>
    <button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
  </div>
</div><!--/ row -->
</form>

<hr class="margin-top-15"/>

<?php
  createCookie('sTransferCode', $sCode);
  createCookie('fWh', $fWh);
  createCookie('tWh', $tWh);
  createCookie('sEmp', $sEmp);
  createCookie('sStatus', $sStatus);
  createCookie('fromDate', $fromDate);
  createCookie('toDate', $toDate);
  createCookie('ix', $ix);
  //--- สร้าง query ตาม filter ที่กรองมา
  $where = "WHERE id != 0 ";

  //--- กรองตามเลขที่เอกสาร

  if( $sCode != '')
  {
    $where .= "AND reference LIKE '%".$sCode."%' ";
  }

  if($fWh != '')
  {
    $fWh_in = getWarehouseIn($fWh);
    if($fWh_in != '1234567890')
    {
      $where .= "AND from_warehouse IN($fWh_in) ";
    }
  }


  if($tWh != '')
  {
    $tWh_in = getWarehouseIn($tWh);
    if($tWh_in != '1234567890')
    {
      $where .= "AND to_warehouse IN($tWh_in) ";
    }
  }


  //--- กรองตามพนักงาน

  if( $sEmp != '')
  {
    $where .= "AND id_employee IN(".getEmployeeIn($sEmp).") ";
  }


  //--- กรองตามสถานะ

  if( $sStatus != '')
  {
    switch($sStatus)
    {
      //--- เฉพาะที่บันทึกแล้ว
      case 'AS' :
        $where .= "AND isSaved = 1 ";
      break;

      //--- เฉพาะที่ยังไม่บันทึก
      case 'NC' :
        $where .= "AND isSaved = 0 AND isCancle = 0 ";
      break;

      //--- เฉพาะที่ยังไม่ส่งออก
      case 'NE' :
        $where .= "AND isExport = 0 AND isCancle = 0 ";
      break;

      //--- เฉพาะที่ยกเลิก
      case 'CN' :
        $where .= "AND isCancle = 1 ";
      break;
    }
  }

  //--- กรองตามวันที่เอกสาร

  if( $fromDate != '' && $toDate != '')
  {
    $where .= "AND date_add >= '".fromDate($fromDate)."' ";
    $where .= "AND date_add <= '".toDate($toDate)."' ";
  }

  if($ix !== 'all')
  {
    $where .= "AND ix = {$ix} ";
  }

  $where .= "ORDER BY reference DESC";

  $paginator = new paginator();
  $get_rows  = get_rows();
  $paginator->Per_Page("tbl_transfer", $where, $get_rows);

  $qs = dbQuery("SELECT * FROM tbl_transfer ". $where . " LIMIT " . $paginator->Page_Start .", " . $paginator->Per_Page);
 ?>

<div class="row">
  <div class="col-sm-7" style="margin-top:-5px;">
   	<?php $paginator->display($get_rows, "index.php?content=transfer"); ?>
  </div>
  <div class="col-sm-5 margin-top-15">
    <p class="pull-right">
      <span class="red">NC</span><span> = ยังไม่สมบูรณ์, </span>
     	<span class="red">CN</span><span> = ยกเลิก, </span>
      <span class="red">NE</span><span> = ยังไม่ส่งออกไป FORMULA</span>
    </p>
  </div>
</div>

<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped border-1">
      <thead>
        <tr>
          <th class="width-5 text-center">ลำดับ</th>
          <th class="width-15">เลขที่เอกสาร</th>
          <th class="width-15">ต้นทาง</th>
          <th class="width-15">ปลายทาง</th>
          <th class="width-20">พนักงาน</th>
          <th class="width-10 text-center">วันที่</th>
          <th class="width-5 text-center">สถานะ</th>
          <th class="width-15"></th>
        </tr>
      </thead>
      <tbody>
<?php if( dbNumRows($qs) > 0 ) :  ?>
<?php   $no = row_no();           ?>
<?php   $wh = new warehouse();    ?>
<?php   while($rs = dbFetchObject($qs)) : ?>
<?php     $status = array(
                      'isSaved' => $rs->isSaved,
                      'isExport' => $rs->isExport,
                      'isCancle' => $rs->isCancle);
?>

        <tr class="font-size-12" id="row_<?php echo $rs->id; ?>">
          <td class="middle text-center"><?php echo $no; ?></td>
          <td class="middle"><?php echo $rs->reference; ?></td>
          <td class="middle"><?php echo $wh->getName($rs->from_warehouse); ?></td>
          <td class="middle"><?php echo $wh->getName($rs->to_warehouse); ?></td>
          <td class="middle"><?php echo employee_name($rs->id_employee); ?></td>
          <td class="middle text-center"><?php echo thaiDate($rs->date_add); ?></td>
          <td class="middle text-center"><?php echo showTransferStatus($status); ?></td>
          <td class="middle text-right">
          <?php if( $rs->isCancle == 0) : ?>
            <?php if($rs->isSaved == 1 && ($add OR $edit) && $rs->date_add >= '2019-10-01 00:00:00') : ?>
              <button type="button" class="btn btn-xs btn-primary" onclick="sendToIX(<?php echo $rs->id; ?>)">
                <i class="fa fa-send"></i> Send to IX
              </button>
            <?php endif; ?>
            <button type="button" class="btn btn-xs btn-info" onclick="goDetail(<?php echo $rs->id; ?>)">
              <i class="fa fa-eye"></i>
            </button>

            <?php if( $edit && $rs->isSaved == 0 && $rs->isExport == 0) : ?>
              <button type="button" class="btn btn-xs btn-warning" onclick="goAdd(<?php echo $rs->id; ?>)">
                <i class="fa fa-pencil"></i>
              </button>
            <?php endif; ?>

            <?php if( $delete ) : ?>
              <button type="button" class="btn btn-xs btn-danger" onclick="goDelete(<?php echo $rs->id; ?>,'<?php echo $rs->reference; ?>')">
                <i class="fa fa-trash"></i>
              </button>
            <?php endif; ?>
          <?php endif; ?>
          </td>
        </tr>
<?php    $no++; ?>
<?php   endwhile; ?>
<?php else : ?>
        <tr>
          <td colspan="8" class="text-center">
            <h4>ไม่พบรายการ</h4>
          </td>
        </tr>
<?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
  function sendToIX(id){
    load_in();
    $.ajax({
      url:'controller/IXController.php?export_ix_transfer',
      type:'POST',
      cache: false,
      data:{
        'id' : id
      },
      success:function(rs){
        load_out();
        var rs = $.trim(rs);
        if(rs === 'success'){
          swal({
            title:'Success',
            text:'success',
            type:'success',
            timer:1000
          });
          $('#row_'+id).remove();
        }else{
          swal({
            title:'Error',
            text:rs,
            type:'error'
          })
        }
      }
    })
  }
</script>
<script src="script/transfer/transfer_list.js?token=<?php echo date('Ymd'); ?>"></script>
