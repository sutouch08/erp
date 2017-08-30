<?php
	$id_tab	= 12;
    $pm 		= checkAccess($id_profile, $id_tab);
	$view 	= $pm['view'];
	$add 		= $pm['add'];
	$edit 		= $pm['edit'];
	$delete 	= $pm['delete'];
	accessDeny($view);
  	include 'function/warehouse_helper.php';
	include 'function/zone_helper.php';
	?>

<div class="container">
<!-- page place holder -->
<div class="row top-row">
	<div class="col-sm-6 top-col">
    	<h4 class="title"><i class="fa fa-map-marker"></i> <?php echo $pageTitle; ?></h4>
    </div>
    <div class="col-sm-6">
    	<p class="pull-right top-p">
	<?php if( isset( $_GET['add'] ) OR isset( $_GET['edit'] ) OR isset( $_GET['view_detail'] ) ) : ?>
    		<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
    <?php endif; ?>
    <?php if( ! isset( $_GET['add'] ) && ! isset( $_GET['edit'] ) && ! isset( $_GET['view_detail'] ) && $add ) : ?>
    		<button type="button" class="btn btn-sm btn-success" onclick="addNew()"><i class="fa fa-plus"></i> เพิ่มใหม่</button>
    <?php endif; ?>
        </p>
    </div>
</div><!--/ row -->
<hr/>
<?php if( isset( $_GET['add'] ) ) : ?>
<div class="row">
	<div class="col-sm-3">
    	<label>คลังสินค้า</label>
        <select class="form-control input-sm" id="add-zWH">
        <?php echo selectWarehouse(); ?>
        </select>
        <span class="display-block margin-top-5 red not-show" id="add-zWH-error">โปรดเลือกคลัง</span>
    </div>
    <div class="col-sm-2">
    	<label>รหัสโซน</label>
        <input type="text" class="form-control input-sm" id="add-zCode" placeholder="* จำเป็น | ห้ามซ้ำ" />
        <span class="display-block margin-top-5 red not-show" id="add-zCode-error">รหัสซ้ำ</span>
    </div>
    <div class="col-sm-4">
    	<label>ชื่อโซน</label>
        <input type="text" class="form-control input-sm" id="add-zName" placeholder="* จำเป็น | ห้ามซ้ำ" />
        <span class="display-block margin-top-5 red not-show" id="add-zName-error">ชื่อซ้ำ</span>
    </div>
    <div class="col-sm-2">
    	<?php if( $add ) : ?>
        <label class="display-block not-show">Submit</label>
        <button type="button" class="btn btn-sm btn-success btn-block" onclick="saveAdd()"><i class="fa fa-plus"></i> เพิ่ม</button>
        <?php endif; ?>
    </div>
</div>
<hr class="margin-top-0"/>
<div class="row">
	<div class="col-sm-12">
    	<table class="table">
        	<tbody id="add-table">

            </tbody>
        </table>
    </div>
</div>

<script id="addRow-Template" type="text/x-handlebars-template">
<tr>
	<td>{{ barcode }}</td>
    <td>{{ zone_name }}</td>
    <td>{{ warehouse_name }}</td>
</tr>
</script>
<?php elseif( isset( $_GET['edit'] ) ) : ?>
	<div class="row">
	<?php $id_zone	= isset( $_GET['id_zone'] ) ? $_GET['id_zone'] : 0; ?>
    <?php $rs			= getZoneDetail($id_zone); ?>
    <?php if( $rs !== FALSE ) : ?>
    	<div class="col-sm-3">
    	<label>คลังสินค้า</label>
        <select class="form-control input-sm" id="edit-zWH">
        <?php echo selectWarehouse($rs->id_warehouse); ?>
        </select>
        <span class="display-block margin-top-5 red not-show" id="edit-zWH-error">โปรดเลือกคลัง</span>
    </div>
    <div class="col-sm-2">
    	<label>รหัสโซน</label>
        <input type="text" class="form-control input-sm" id="edit-zCode" placeholder="* จำเป็น | ห้ามซ้ำ" value="<?php echo $rs->barcode_zone; ?>" />
        <span class="display-block margin-top-5 red not-show" id="edit-zCode-error">รหัสซ้ำ</span>
    </div>
    <div class="col-sm-4">
    	<label>ชื่อโซน</label>
        <input type="text" class="form-control input-sm" id="edit-zName" placeholder="* จำเป็น | ห้ามซ้ำ" value="<?php echo $rs->zone_name; ?>" />
        <span class="display-block margin-top-5 red not-show" id="edit-zName-error">ชื่อซ้ำ</span>
    </div>
    <div class="col-sm-2">
    	<?php if( $edit ) : ?>
        <label class="display-block not-show">Submit</label>
        <button type="button" class="btn btn-sm btn-success btn-block" onclick="saveEdit()">ปรับปรุง</button>
        <?php endif; ?>
    </div>
   <input type="hidden" id="id_zone" value="<?php echo $id_zone; ?>" />
   <?php else : ?>
      <div class="col-sm-12 text-center middle">
      	<h4>ไม่พบข้อมูล</h4>
      </div>
    <?php endif; ?>
     </div> 
<?php else : ?>
	<?php
		$zCode 	= isset( $_POST['zCode'] ) ? $_POST['zCode'] : ( getCookie('zCode') ? getCookie('zCode') : '' );
		$zName	= isset( $_POST['zName'] ) ? $_POST['zName'] : ( getCookie('zName') ? getCookie('zName') : '' );
		$zWH		= isset( $_POST['zWH'] ) ? $_POST['zWH'] : ( getCookie('zWH') ? getCookie('zWH') : 0 );
	?>
<form id="searchForm" method="post" action="index.php?content=zone">
<div class="row">
	<div class="col-sm-2">
    	<label>รหัสโซน</label>
        <input type="text" class="form-control input-sm" name="zCode" id="zCode" value="<?php echo $zCode; ?>" autofocus />
    </div>
    <div class="col-sm-3">
    	<label>ชื่อโซน</label>
        <input type="text" class="form-control input-sm" name="zName" id="zName" value="<?php echo $zName; ?>" />
    </div>

    <div class="col-sm-3">
    	<label>คลัง</label>
        <select class="form-control input-sm" name="zWH" id="zWH">
        	<?php echo selectWarehouse($zWH); ?>
        </select>
    </div>
    <div class="col-sm-1 col-1-harf">
    	<label class="display-block not-show">Search</label>
        <button type="button" class="btn btn-sm btn-primary btn-block" id="btn-seareh" onclick="getSearch()"><i class="fa fa-search"></i> ใช้ตัวกรอง</button>
    </div>
    <div class="col-sm-1 col-1-harf">
    	<label class="display-block not-show">Reset</label>
        <button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> เคลียร์ตัวกรอง</button>
    </div>
</div><!--/ row -->
</form>
<hr class="margin-top-15"/>

	<?php
	$where 	= "WHERE id_zone != 0 ";
	if( $zCode != '' )
	{
		createCookie('zCode', $zCode);
		$where .= "AND barcode_zone LIKE '%".$zCode."%' ";
	}
	else
	{
		deleteCookie('zCode');
	}

	if( $zName != '' )
	{
		createCookie('zName', $zName);
		$where .= "AND zone_name LIKE '%".$zName."%' ";
	}
	else
	{
		deleteCookie('zName');
	}

	if( $zWH != 0 )
	{
		createCookie('zWH', $zWH);
		$where .= "AND id_warehouse = ".$zWH." ";
	}
	else
	{
		deleteCookie('zWH');
	}

	$where .= "ORDER BY id_zone DESC";

	$paginator	= new paginator();
	$get_rows	= get_rows();
	$page		= get_page();
	$paginator->Per_Page('tbl_zone', $where, $get_rows);
	$paginator->display($get_rows, 'index.php?content=zone');


	$qs = dbQuery("SELECT * FROM tbl_zone ".$where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);
	?>
<div class="row">
	<div class="col-sm-12">
    	<table class="table table-striped" style="border:solid 1px #ccc;">
        	<thead>
            	<tr>
                	<th class="width-5 text-center">ลำดับ</th>
                    <th class="width-20">รหัสโซน</th>
                    <th class="width-35">ชื่อโซน</th>
                    <th class="width-25">คลังสินค้า</th>
                    <th class="width-15 text-center">การกระทำ</th>
                </tr>
            </thead>
            <tbody>
	<?php if( dbNumRows($qs) > 0 ) : ?>
    <?php	$no	= row_no();	?>
    <?php	$wh	= new warehouse(); 	?>
    <?php	while( $rs = dbFetchObject($qs) ) : ?>
            	<tr id="row_<?php echo $rs->id_zone; ?>" style="font-size:12px;">
                	<td align="center"><?php echo number_format($no); ?></td>
                    <td><?php echo $rs->barcode_zone; ?></td>
                    <td><?php echo $rs->zone_name; ?></td>
                    <td><?php echo $wh->getName($rs->id_warehouse); ?></td>
                    <td align="center">
                    <?php if( $edit ) : ?>
                    	<button type="button" class="btn btn-sm btn-warning" onclick="editZone(<?php echo $rs->id_zone; ?>)"><i class="fa fa-pencil"></i></button>
                    <?php endif; ?>
                    <?php if( $delete ) : ?>
                    	<button type="button" class="btn btn-sm btn-danger" onclick="deleteZone(<?php echo $rs->id_zone; ?>, '<?php echo $rs->zone_name; ?>')"><i class="fa fa-trash"></i></button>
                    <?php endif; ?>
                    </td>
                </tr>
	<?php	$no++; ?>
	<?php	endwhile; ?>
	<?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>
</div><!--/ Container -->
<script src="script/zone.js"></script>
