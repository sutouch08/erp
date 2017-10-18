<div class="row top-row">
    <div class="col-sm-6 top-col">
        <h4 class="title"><i class="fa fa-shopping-basket"></i> <?php echo $pageTitle; ?></h4>
    </div>
    <div class="col-sm-6">
        <p class="pull-right top-p">
          <button type="button" class="btn btn-sm btn-primary" onclick="viewProcess()"><i class="fa fa-play"></i>&nbsp; กำลังจัด</button>
        </p>
    </div>

</div>

<hr/>
<?php

$sCode      = getFilter('sCode', 'sOrderCode','');
$sCus       = getFilter('sCus', 'sOrderCus', '');
$sEmp       = getFilter('sEmp', 'sOrderEmp', '');
$fromDate   = getFilter('fromDate', 'fromDate', '');
$toDate     = getFilter('toDate', 'toDate', '');

?>

<form id="searchForm" method="post">
<div class="row">
    <div class="col-sm-2 padding-5 first">
        <label>เลขที่เอกสาร</label>
        <input type="text" class="form-control input-sm text-center search-box" id="sCode" name="sCode" value="<?php echo $sCode; ?>"/>
    </div>

    <div class="col-sm-2 padding-5">
        <label>ลูกค้า/ผู้รับสปอนเซอร์</label>
        <input type="text" class="form-control input-sm text-center search-box" id="sCus" name="sCus" value="<?php echo $sCus; ?>"/>
    </div>

    <div class="col-sm-2 padding-5">
        <label>พนักงาน/ผู้เบิก/ผู้ยืม</label>
        <input type="text" class="form-control input-sm text-center search-box" id="sEmp" name="sEmp" value="<?php echo $sEmp; ?>"/>
    </div>

    <div class="col-sm-2 padding-5">
        <label class="display-block">วันที่</label>
        <input type="text" class="form-control input-sm input-discount text-center" id="fromDate" name="fromDate" value="<?php echo $fromDate; ?>"/>
        <input type="text" class="form-control input-sm input-unit text-center" id="toDate" name="toDate" value="<?php echo $toDate; ?>"/>
    </div>
    <div class="col-sm-1 padding-5">
        <label class="display-block not-show">search</label>
        <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
    </div>
    <div class="col-sm-1 padding-5">
      <label class="display-block not-show">reset</label>
      <button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>

    </div>
</div>
</form>

<hr class="margin-top-10"/>

<?php
$where = "WHERE state = 3 ";
if( $sCode != "")
{
    createCookie('sOrderCode', $sCode);
    $where .= "AND reference LIKE'%".$sCode."%' ";
}



if( $sCus != "")
{
    createCookie('sOrderCus', $sCus);
    $where .= "AND id_customer IN(".getCustomerIn($sCus).") ";
}


if( $sEmp != '')
{
  createCookie('sOrderEmp', $sEmp);
  $where .= "AND id_employee IN(".getEmployeeIn($sEmp).") ";
}



if( $fromDate != "" && $toDate != "" )
{
    createCookie('fromDate', $fromDate);
    createCookie('toDate', $toDate);
    $where .= "AND date_add >= '".fromDate($fromDate)."' AND date_add <= '". toDate($toDate)."' ";
}

$where .= "ORDER BY date_add ASC";


$paginator	= new paginator();
$get_rows	= get_rows();
$paginator->Per_Page('tbl_order', $where, $get_rows);
$paginator->display($get_rows, 'index.php?content=prepare');
$qs = dbQuery("SELECT * FROM tbl_order " . $where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);

?>

<div class="row">
    <div class="col-sm-12">
        <table class="table table-striped border-1">
            <thead>
                <tr>
                    <th class="width-5 text-center">No.</th>
                    <th class="width-15">เลขที่เอกสาร</th>
                    <th class="width-35">ลูกค้า/ผู้รับ/ผู้เบิก</th>
                    <th class="width-15 text-center">รูปแบบ</th>
                    <th class="width-15 text-center">วันที่</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="list-table">
<?php if( dbNumRows($qs) > 0) : ?>
<?php   $no = row_no(); ?>
<?php   while( $rs = dbFetchObject($qs)) : ?>
            <tr class="font-size-12">
                <td class="middle text-center"><?php echo $no; ?></td>
                <td class="middle"><?php echo $rs->reference; ?></td>
                <td class="middle">
                  <?php

                    if( $rs->role == 1 OR $rs->role == 2 OR $rs->role == 4)
                    {
                      //--- ขาย / ฝากขาย / สปอนเซอร์
                      echo customerName($rs->id_customer);
                    }
                    else
                    {
                      //--- อภินันท์ / แปรสภาพ / เบิก / ยืม
                      echo employee_name($rs->id_employee);
                    }
                  ?>
                </td>
                <td class="middle text-center"><?php echo roleName($rs->role); ?></td>
                <td class="middle text-center"><?php echo thaiDate($rs->date_add); ?></td>
                <td class="middle text-right">
                  <?php if( $add OR $edit) : ?>
                    <button type="button" class="btn btn-sm btn-default" onclick="goPrepare(<?php echo $rs->id; ?>)">จัดสินค้า</button>
                  <?php endif; ?>
                </td>
            </tr>
<?php       $no++; ?>
<?php   endwhile; ?>

<?php else : ?>

            <tr>
                <td colspan="6" class="text-center">
                    <h4>ไม่พบรายการ</h4>
                </td>
            </tr>
<?php endif; ?>

            </tbody>
        </table>
    </div>
</div>


<script src="script/prepare/prepare_list.js"></script>
