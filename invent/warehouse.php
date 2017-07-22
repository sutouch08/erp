<?php 
	$pageName	= 'เพิ่ม/แก้ไข คลังสินค้า';
	$id_tab 	= 13;
    $pm 		= checkAccess($id_profile, $id_tab);
	$view 	= $pm['view'];
	$add 		= $pm['add'];
	$edit 		= $pm['edit'];
	$delete 	= $pm['delete'];
	accessDeny($view);
  	include 'function/warehouse_helper.php';
	?>
<div class="container">
<div class="row top-row">
	<div class="col-sm-6 top-col"><h4 class="title"><i class="fa fa-home"></i>&nbsp;<?php echo $pageName; ?></h4></div>
    <div class="col-sm-6">
      <p class="pull-right top-p">
		<?php if( isset( $_GET['add'] ) || isset( $_GET['edit'] ) || isset( $_GET['view_detail'] ) ) : ?>
        	<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
        <?php endif; ?>
        <?php if( ! isset( $_GET['add'] ) && ! isset( $_GET['edit'] ) && ! isset( $_GET['view_detail'] ) && $add ) : ?>
        	<button type="button" class="btn btn-sm btn-success" onclick="syncWarehouse()"><i class="fa fa-refresh"></i> อัพเดตข้อมูล</button>
        <?php endif; ?>
        <?php if( isset( $_GET['edit'] ) && isset( $_GET['id_warehouse'] ) && $edit ) : ?>
        	<button type="button" class="btn btn-sm btn-success" onclick="saveEdit()"><i class="fa fa-save"></i> บันทึก</button>
        <?php endif; ?>
       </p>
    </div>
</div>
<hr class="margin-bottom-15" />
<?php if( isset( $_GET['edit'] ) && isset( $_GET['id_warehouse'] ) ) : ?>
	<?php 	$qs = getWarehouseDetail($_GET['id_warehouse']); ?>
    <?php if( dbNumRows($qs) == 1 ) : ?>
    <?php 	$rs = dbFetchObject($qs); ?>

        <div class="row">
            <div class="col-sm-4">
                <label class="form-control label-left">รหัสคลัง : </label>
            </div>
            <div class="col-sm-8">
            	<label class="form-control input-sm input-small"><?php echo $rs->code; ?></label>
            </div>
            <div class="divider-hidden margin-top-5 margin-bottom-5"></div>
            
            <div class="col-sm-4">
                <label class="form-control label-left">ชื่อคลัง : </label>
            </div>
            <div class="col-sm-8">
            	<label class="form-control input-sm input-large"><?php echo $rs->warehouse_name; ?></label>
            </div>
            <div class="divider-hidden margin-top-5 margin-bottom-5"></div>
            
            <div class="col-sm-4">
                <label class="form-control label-left">ประเภทคลัง : </label>
            </div>
            <div class="col-sm-8">
                <select class="form-control input-sm input-large inline" id="edit-whRole">
                <?php echo selectWarehouseRole($rs->role); ?>
                </select>
                <span class="label-left margin-left-15 red hide" id="edit-whRole-error">จำเป็นต้องเลือก</span>
            </div>
            <div class="divider-hidden margin-top-5 margin-bottom-5"></div>
            
            <div class="col-sm-4">
                <label class="form-control label-left">อนุญาตให้ขาย : </label>
            </div>
            <div class="col-sm-2">
                <div class="btn-group width-100">
                    <button type="button" class="btn btn-sm width-50 <?php echo $rs->sell == 1 ? 'btn-success':''; ?>" id="btn-sell-yes" onclick="toggleSell(1)">ใช่</button>
                    <button type="button" class="btn btn-sm width-50 <?php echo $rs->sell == 0 ? 'btn-danger' : ''; ?>" id="btn-sell-no" onclick="toggleSell(0)">ไม่ใช่</button>
                </div>
            </div>
            <div class="divider-hidden margin-top-5 margin-bottom-5"></div>
            
            <div class="col-sm-4">
                <label class="form-control label-left">อนุญาตให้จัด : </label>
            </div>
            <div class="col-sm-2">
                <div class="btn-group width-100">
                    <button type="button" class="btn btn-sm width-50 <?php echo $rs->prepare == 1 ? 'btn-success' : ''; ?>" id="btn-pre-yes" onclick="togglePrepare(1)">ใช่</button>
                    <button type="button" class="btn btn-sm width-50 <?php echo $rs->prepare == 0 ? 'btn-danger' : ''; ?>" id="btn-pre-no" onclick="togglePrepare(0)">ไม่ใช่</button>
                </div>
            </div>
            <div class="divider-hidden margin-top-5 margin-bottom-5"></div>
            
            <div class="col-sm-4">
                <label class="form-control label-left">อนุญาตให้ติดลบ : </label>
            </div>
            <div class="col-sm-2">
                <div class="btn-group width-100">
                    <button type="button" class="btn btn-sm width-50 <?php echo $rs->allow_under_zero == 1 ? 'btn-success' : ''; ?>" id="btn-under-zero-yes" onclick="toggleUnderZero(1)">ใช่</button>
                    <button type="button" class="btn btn-sm width-50 <?php echo $rs->allow_under_zero == 0 ? 'btn-danger' : ''; ?>" id="btn-under-zero-no" onclick="toggleUnderZero(0)">ไม่ใช่</button>
                </div>
            </div>
            <div class="divider-hidden margin-top-5 margin-bottom-5"></div>
            
            <div class="col-sm-4">
                <label class="form-control label-left">เปิดใช้งาน : </label>
            </div>
            <div class="col-sm-2">
                <div class="btn-group width-100">
                    <button type="button" class="btn btn-sm width-50 <?php echo $rs->active == 1 ? 'btn-success' : ''; ?>" id="btn-active-yes" onclick="toggleActive(1)">ใช่</button>
                    <button type="button" class="btn btn-sm width-50 <?php echo $rs->active == 0 ? 'btn-danger' : ''; ?>" id="btn-active-no" onclick="toggleActive(0)">ไม่ใช่</button>
                </div>
            </div>
            <div class="divider-hidden margin-top-5 margin-bottom-5"></div>
            
            
            <input type="hidden" id="sell" value="<?php echo $rs->sell; ?>" />
            <input type="hidden" id="prepare" value="<?php echo $rs->prepare; ?>" />
            <input type="hidden" id="underZero" value="<?php echo $rs->allow_under_zero; ?>" />
            <input type="hidden" id="active" value="<?php echo $rs->active; ?>" />
            <input type="hidden" id="id_warehouse" value="<?php echo $rs->id_warehouse; ?>" />
            
            <input type="hidden" id="oldCode" value="<?php echo $rs->code; ?>" />
            <input type="hidden" id="oldName" value="<?php echo $rs->warehouse; ?>" />
            <input type="hidden" id="oldRole" value="<?php echo $rs->role; ?>" />
            <input type="hidden" id="oldSell" value="<?php echo $rs->sell; ?>" />
            <input type="hidden" id="oldPrepare" value="<?php echo $rs->prepare; ?>" />
            <input type="hidden" id="oldUnderZero" value="<?php echo $rs->allow_under_zero; ?>" />
            <input type="hidden" id="oldActive" value="<?php echo $rs->active; ?>" />
        </div>
	<?php else : ?>
    <div class="row">
    	<div class="col-sm-12">
        	<div class="alert alert-info">
            	<strong>ไม่พบข้อมูลคลัง</strong>
            </div>
        </div>
    </div>
    <?php endif; ?>        

<?php else : ?>
<!----------------------  Warehouse List ------------------------>
<?php
	$whCode		= isset( $_POST['whCode'] ) ? $_POST['whCode'] : (getCookie('whCode') ? getCookie('whCode') : '');
	$whName		= isset( $_POST['whName'] ) ? $_POST['whName'] : (getCookie('whName') ? getCookie('whName') : '');
	$whRole		= isset( $_POST['whRole'] ) ? $_POST['whRole'] : (getCookie('whRole') ? getCookie('whRole') : 0);
	$underZero	= isset( $_POST['underZero'] ) ? $_POST['underZero'] : (getCookie('underZero') ? getCookie('underZero') : 2 );
?>
<form id="searchForm" method="post">
<div class="row">
	<div class="col-sm-2">
    	<label>รหัส</label>
        <input type="text" class="form-control input-sm search-box" name="whCode" id="whCode" placeholder="ค้นหารหัสคลัง" value="<?php echo $whCode; ?>" />
    </div>
    <div class="col-sm-3">
    	<label>ชื่อคลัง</label>
        <input type="text" class="form-control input-sm search-box" name="whName" id="whName" placeholder="ค้าหาชื่อคลัง" value="<?php echo $whName; ?>" />
    </div>
    <div class="col-sm-2">
    	<label>ประเภทคลัง</label>
        <select class="form-control input-sm search-select" name="whRole" id="whRole">
        <?php echo selectWarehouseRole($whRole); ?>
        </select>
    </div>
    <div class="col-sm-2">
    	<label >สต็อกติดลบ</label>
        <select class="form-control input-sm search-select" name="underZero" id="underZero">
        	<option value="2" <?php echo isSelected($underZero, 2); ?>>ทั้งหมด</option>
            <option value="1" <?php echo isSelected($underZero, 1); ?>>อนุญาติให้ติดลบ</option>
            <option value="0" <?php echo isSelected($underZero, 0); ?>>ไม่อนุญาติให้ติดลบ</option>
        </select>
    </div>
    <div class="col-sm-1 col-1-harf">
    	<label class="display-block not-show">ใช้ตัวกรอง</label>
        <button type="button" class="btn btn-sm btn-block btn-success" onclick="getSearch()"><i class="fa fa-search"></i> ใช้ตัวกรอง</button>
    </div>
     <div class="col-sm-1 col-1-harf">
    	<label class="display-block not-show">reset</label>
        <button type="button" class="btn btn-sm btn-block btn-warning" onclick="resetSearch()"><i class="fa fa-retweet"></i> รีเซ็ต</button>
    </div>
</div>
</form>
<hr class="margin-top-10"/>
<?php 
	$where 	= "WHERE id_warehouse != '' ";
	if( $whCode != '' )
	{	
		createCookie('whCode', $whCode);
		$where .= "AND code LIKE '%".$whCode."%' "; 
	}
	if( $whName != '' )
	{ 
		createCookie('whName', $whName);
		$where .= "AND warehouse_name LIKE '%".$whName."%' "; 
	}
	if( $whRole != 0 )
	{ 
		createCookie('whRole', $whRole);
		$where .= "AND role = ".$whRole." "; 
	}
	if( $underZero != 2 )
	{ 
		createCookie('underZero', $underZero);
		$where .= "AND allow_under_zero = ".$underZero." "; 
	}
	
	$paginator	= new paginator();
	$get_rows 	= get_rows();
	$page		= get_page();
	$paginator->Per_Page("tbl_warehouse",$where,$get_rows);
	$paginator->display($get_rows,"index.php?content=warehouse");
	$qs = dbQuery("SELECT * FROM tbl_warehouse ".$where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);
?>
<div class="row">
	<div class="col-sm-12">
    	<table class="table table-striped">
        	<thead>
            	<tr>
                	<th style="width:5%; text-align:center;">ลำดับ</th>
                    <th style="width:10%; text-align:center;">รหัสคลัง</th>
                    <th style="width:25%;">ชื่อคลัง</th>
                    <th style="width:10%; text-align:center;">ประเภทคลัง</th>
                    <th style="width:10%; text-align:center;">ขายสินค้า</th>
                    <th style="width:10%; text-align:center;">จัดสินค้า</th>
                    <th style="width:10%; text-align:center;">ติดลบได้</th>
                    <th style="width:10%; text-align:center;">เปิดใช้งาน</th>
                    <th style="width:10%; text-align:center;">การกระทำ</th>
                </tr>
            </thead>
            <tbody>
	<?php if( dbNumRows($qs) > 0 ) : ?>
    <?php	$no	= ($get_rows * ($page -1)) + 1 ;	?>
    <?php	while( $rs = dbFetchObject($qs) ) : 	?>
    			<tr style="font-size:12px;" id="row_<?php echo $rs->id_warehouse; ?>">
                	<td class="text-center middle"><?php echo number_format($no); ?></td>
                    <td class="text-center middle"><?php echo $rs->code; ?></td>
                    <td class="middle"><?php echo $rs->warehouse_name; ?></td>
                    <td class="text-center middle"><?php echo getWarehouseRoleName($rs->role); ?></td>
                    <td class="text-center middle"><?php echo isActived($rs->sell); ?></td>
                    <td class="text-center middle"><?php echo isActived($rs->prepare); ?></td>
                    <td class="text-center middle"><?php echo isActived($rs->allow_under_zero); ?></td>
                    <td class="text-center middle"><?php echo isActived($rs->active); ?></td>
                    <td align="right" class="middle">
                    <?php if( $edit ) : ?>	
                        <button type="button" class="btn btn-sm btn-warning" onclick="edit('<?php echo $rs->id_warehouse; ?>')"><i class="fa fa-pencil"></i></button>
					<?php endif; ?>
                    <?php if( $delete ) : ?>                       
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteWarehouse('<?php echo $rs->id_warehouse; ?>')"><i class="fa fa-trash"></i></button>
					<?php endif; ?>                        
                    </td>
                </tr>  
	<?php 	$no++; ?>           
	<?php	endwhile; ?>                  
    <?php endif; ?>           
            </tbody>
            
        </table>
    </div>	
</div>


<?php endif; ?>
</div><!--  end Container -->
<script src="script/warehouse.js"></script>