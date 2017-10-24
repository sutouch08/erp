<div class="row top-row">
    <div class="col-sm-6 top-col">
        <h4 class="title"><i class="fa fa-shopping-basket"></i> กำลังจัดสินค้า..</h4>
    </div>
    <div class="col-sm-6">
        <p class="pull-right top-p">
          <button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i>&nbsp; กลับ</button>
        </p>
    </div>

</div>

<hr/>

<?php
$qr = "SELECT o.* FROM tbl_order AS o JOIN tbl_order_state AS s ON o.id = s.id_order AND o.state = s.id_state ";

$qr .= "WHERE state = 4 AND status = 1 ";

if( ! $supervisor )
{
  $qr .= "AND s.id_employee = ".getCookie('user_id')." ";
}

$qr .= "ORDER BY date_add ASC";

$qs = dbQuery($qr);

?>

<div class="row">
    <div class="col-sm-12">
        <table class="table table-striped border-1">
            <thead>
                <tr>
                    <th class="width-5 text-center">No.</th>
                    <th class="width-15">เลขที่เอกสาร</th>
                    <th class="width-35">ลูกค้า</th>
                    <th class="width-15 text-center">รูปแบบ</th>
                    <th class="width-15 text-center">วันที่</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="list-table">

<?php if( dbNumRows($qs) > 0) : ?>
<?php   $no = row_no(); ?>
<?php   while( $rs = dbFetchObject($qs)) : ?>

            <tr class="font-size-12" id="order-<?php echo $rs->id; ?>">
                <td class="middle text-center"><?php echo $no; ?></td>
                <td class="middle"><?php echo $rs->reference; ?></td>
                <td class="middle"><?php echo customerName($rs->id_customer); ?></td>
                <td class="middle text-center"><?php echo roleName($rs->role); ?></td>
                <td class="middle text-center"><?php echo thaiDate($rs->date_add); ?></td>
                <td class="middle text-right">
                  <?php if( $add OR $edit) : ?>
                    <button type="button" class="btn btn-sm btn-default" onclick="goPrepare(<?php echo $rs->id; ?>)">จัดสินค้า</button>
                  <?php endif; ?>
                  <?php if( $supervisor) : ?>
                    <button type="button" class="btn btn-sm btn-warning" onclick="pullBack(<?php echo $rs->id; ?>)">ดึงกลับ</button>
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
