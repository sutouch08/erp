<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-check-square-o"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
      <button type="button" class="btn btn-sm btn-info" onclick="viewProcess()"><i class="fa fa-hourglass-half"></i> &nbsp; รายการกำลังตรวจ</button>
    </p>
  </div>
</div>

<hr/>

<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped border-1">
      <thead>
        <tr>
          <th class="width-5 text-center">ลำดับ</th>
          <th class="width-15">เลขที่เอกสาร</th>
          <th class="width-30">ลูกค้า</th>
          <th class="width-10 text-center">รูปแบบ</th>
          <th class="width-20 text-center">พนักงาน</th>
          <th class="width-10 text-center">วันที่</th>
          <th></th>
        </tr>
      </thead>
      <tbody id="qcList">
<?php $qc = new qc(); ?>
<?php $qs = $qc->getDatas();  ?>
<?php if( dbNumRows($qs) > 0) : ?>
<?php   $no = 1; ?>
<?php   while( $rs = dbFetchObject($qs) ) : ?>
        <tr class="font-size-12" id="list-<?php echo $rs->id; ?>">
          <td class="middle text-center"><?php echo $no; ?></td>
          <td class="middle"><?php echo $rs->reference; ?></td>
          <td class="middle"><?php echo customerName($rs->id_customer); ?></td>
          <td class="middle text-center"><?php echo roleName($rs->role); ?></td>
          <td class="middle text-center"><?php echo employee_name($rs->emp_upd); ?></td>
          <td class="middle text-center"><?php echo thaiDate($rs->date_add); ?></td>
          <td class="middle text-right">
          <?php if( $add || $edit ) : ?>
            <button type="button" class="btn btn-sm btn-default" onclick="goQc(<?php echo $rs->id; ?>)">ตรวจสินค้า</button>
          <?php endif; ?>
          </td>
        </tr>
<?php     $no++; ?>
<?php   endwhile; ?>
<?php else : ?>
        <tr>
          <td colspan="7"><h4>ไม่พบรายการ</h4></td>
        </tr>
<?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script src="script/qc/qc_list.js"></script>
