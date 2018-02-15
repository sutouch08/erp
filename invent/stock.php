<?php
	$id_tab = 90;
  $pm = checkAccess($id_profile, $id_tab);
	$view = $pm['view'];
	$add = $pm['add'];
	$edit = $pm['edit'];
	$delete = $pm['delete'];
  accessDeny($view);
	?>

<div class="container">
  <div class="row top-row">
    <div class="col-sm-6 top-col">
      <h4 class="title"><i class="fa fa-tasks"></i> <?php echo $pageTitle; ?></h4>
    </div>
    <div class="col-sm-6">
      <p class="pull-right top-p">

      </p>
    </div>
  </div>
  <hr/>

<?php
$pdCode = getFilter('pdCode', 'pdCode', '');
$zoneCode = getFilter('zoneCode', 'zoneCode', '');
 ?>
<form id="searchForm" method="post">
<div class="row">
  <div class="col-sm-2 padding-5 first">
    <label>สินค้า</label>
    <input type="text" class="form-control input-sm search-box" name="pdCode" value="<?php echo $pdCode; ?>" />
  </div>
  <div class="col-sm-2 padding-5">
    <label>โซน</label>
    <input type="text" class="form-control input-sm search-box" name="zoneCode" value="<?php echo $zoneCode; ?>" />
  </div>

  <div class="col-sm-1 padding-5">
    <label class="display-block not-show">search</label>
    <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()">ค้นหา</button>
  </div>
  <div class="col-sm-1 padding-5">
    <label class="display-block not-show">reset</label>
    <button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()">เคลียร์ตัวกรอง</button>
  </div>
</div>
</form>
<hr class="margin-top-15 margin-bottom-15" />
<?php

$length = 0;
$where = "WHERE s.id_product != '' ";

if($pdCode != '')
{
  createCookie('pdCode', $pdCode);
  $where .= "AND p.code LIKE '%".$pdCode."%' ";
	$length++;
}

if($zoneCode != '')
{
  createCookie('zoneCode', $zoneCode);
  $where .= "AND (z.barcode_zone LIKE '%".$zoneCode."%' OR z.zone_name LIKE '%".$zoneCode."%') ";
	$length++;
}

$where .= "ORDER BY p.date_upd DESC";

if($length == 0)
{
	$where = "WHERE s.id_product = ''";
}



$table = "tbl_stock AS s JOIN tbl_zone AS z ON s.id_zone = z.id_zone ";
$table .= "JOIN tbl_product AS p ON s.id_product = p.id ";
$qr  = "SELECT s.id_stock, z.zone_name, p.code, s.qty, s.date_upd FROM ";
$qr .= $table;

$paginator	= new paginator();
$get_rows	= get_rows();
$paginator->Per_Page($table, $where, $get_rows);
$paginator->display($get_rows, 'index.php?content=stock');
$qs = dbQuery($qr. $where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);

?>

<table class="table table-striped table-bordered">
  <tr>
    <th class="width-5 text-center">ลำดับ</th>
    <th class="width-20 text-center">สินค้า</th>
		<th class="width-40">โซน</th>
    <th class="width-8 text-center">คงเหลือ</th>
    <th class="width-15 text-center">ปรับปรุง</th>
		<th class="width-10 text-center"></th>
  </tr>
  <tbody>
<?php if( dbNumRows($qs) > 0) : ?>
<?php  $no = row_no(); ?>
<?php  while($rs = dbFetchObject($qs)) : ?>
  <tr class="font-size-12" id="row-<?php echo $rs->id_stock; ?>">
    <td class="text-center"><?php echo $no; ?></td>
    <td><?php echo $rs->code; ?></td>
		<td class=""><?php echo $rs->zone_name; ?></td>
    <td class="text-center">
			<input type="number" class="form-control input-xs text-center edit-qty" id="qty-<?php echo $rs->id_stock; ?>" value="<?php echo $rs->qty; ?>" disabled />
		</td>
		<td class="text-center"><?php echo thaiDateTime($rs->date_upd); ?></td>
		<td class="text-center">
			<button type="button" class="btn btn-xs btn-warning" id="btn-edit-<?php echo $rs->id_stock; ?>" onclick="editStock(<?php echo $rs->id_stock; ?>)"><i class="fa fa-pencil"></i></button>
			<button type="button" class="btn btn-xs btn-success hide" id="btn-update-<?php echo $rs->id_stock; ?>" onclick="updateStock(<?php echo $rs->id_stock; ?>)"><i class="fa fa-save"></i></button>
			<button type="button" class="btn btn-xs btn-danger" onclick="deleteStock(<?php echo $rs->id_stock; ?>)"><i class="fa fa-trash"></i></button>
		</td>
  </tr>
<?php  $no++; ?>
<?php endwhile; ?>
<?php else : ?>
  <tr>
    <td colspan="5" class="text-center">--- ไม่พบข้อมูล ---</td>
  </tr>
<?php endif; ?>
  </tbody>
</table>

</div><!--- container --->

<script src="script/stock.js"></script>
