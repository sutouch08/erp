<?php
	$id_tab 			= 73;
    $pm 				= checkAccess($id_profile, $id_tab);
	$view 			= $pm['view'];
	$add 				= $pm['add'];
	$edit 				= $pm['edit'];
	$delete 			= $pm['delete'];
	accessDeny($view);
	include 'function/productTab_helper.php';
?>
<div class="container">
<div class="row top-row">
	<div class="col-sm-6 top-col">
    	<h4 class="title"><i class="fa fa-tags"></i>&nbsp;<?php echo $pageTitle; ?></h4>
    </div>
    <div class="col-sm-6">
    	<p class="pull-right top-p" >
	<?php if( $add ) : ?>
    		<button type="button" class="btn btn-sm btn-success" onclick="goAdd()"><i class="fa fa-plus"></i> เพิ่มใหม่</button>
    <?php endif; ?>        
        </p>
    </div>
</div><!-- / row -->

<hr style="margin-bottom:15px;" />
<?php
			
			$sName	= isset( $_POST['sName'] ) ? trim( $_POST['sName'] ) : ( getCookie('sTabName') ? getCookie('sTabName') : '' );
			$sParent	= isset( $_POST['sParent'] ) ? trim( $_POST['sParent'] ) : ( getCookie('sParent') ? getCookie('sParent') : '' );
			$sLevel	= isset( $_POST['sLevel'] ) ? trim( $_POST['sLevel'] ) : ( getCookie('sLevel') ? getCookie('sLevel') : '' );
?>


    <form id="searchForm" method="post">
    <div class="row">
        <div class="col-sm-2">
        	<label>ชื่อ</label>
            <input type="text" class="form-control input-sm text-center search-box" name="sName" id="sName"  value="<?php echo $sName; ?>"  />
        </div>
        
        <div class="col-sm-2">
        	<label>แถบหลัก</label>
            <input type="text" class="form-control input-sm text-center search-box" name="sParent" id="sParent"  value="<?php echo $sParent; ?>"  />
        </div>
        
        <div class="col-sm-2">
        	<label>ระดับ</label>
            <select class="form-control input-sm select-box" name="sLevel" id="sLevel">
            	<?php echo selectLevel($sLevel); ?>
            </select>
        </div>

        <div class="col-sm-2">
        	<label class="display-block not-show">Apply</label>
            <button type="button" class="btn btn-sm btn-primary btn-block" onClick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
        </div>
        <div class="col-sm-2">
        	<label class="display-block not-show">Apply</label>
            <button type="button" class="btn btn-sm btn-warning btn-block" onClick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
        </div>

    </div>
    </form>
    <hr class="margin-top-15" />

<?php
	$where	= "WHERE id != 0 ";
	if( $sParent != '' )
	{
		createCookie('sParent', $sParent);
		$where .= "AND id_parent IN(". parentIn($sParent).") ";
	}

	if( $sName != '' )
	{
		createCookie('sTabName', $sName);
		$where .= "AND name LIKE '%". $sName ."%' ";
	}
	
	if( $sLevel != '' )
	{
		createCookie('SLevel', $sLevel);
		$where .= "AND level = ".$sLevel." ";	
	}

	
	$paginator	= new paginator();
	$get_rows	= get_rows();
	$paginator->Per_Page('tbl_product_tab', $where, $get_rows);
	$paginator->display($get_rows, 'index.php?content=product_tab');

	$qs = dbQuery("SELECT * FROM tbl_product_tab ".$where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);
?>
	<div class="row">
    	<div class="col-sm-12">
        	<table class="table table-striped border-1">
            	<thead>
                	<tr>
                    	<th class="width-5 text-center">ลำดับ</th>
                        <th class="width-20">ชื่อ</th>
                        <th class="width-20">แถบหลัก</th>
                        <th class="width-15 text-center">จำนวนสินค้า</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
<?php	if( dbNumRows($qs) > 0 ) : ?>
<?php		$no = row_no(); ?>
<?php		$cs = new product_tab();		?>
<?php		while( $rs = dbFetchObject($qs) ) : ?>
					<tr class="font-size-12" id="row_<?php echo $rs->id; ?>">
                    	<td class="middle text-center"><?php echo $no; ?></td>
                        <td class="middle"><?php echo $rs->name; ?></td>
                        <td class="middle"><?php echo $cs->getName($rs->id_parent); ?></td>
                        <td class="middle text-center"><?php echo number_format($cs->countMember($rs->id) ); ?></td>
                        <td class="middle text-right">
                        <?php if( $edit ) : ?>
                        	<button type="button" class="btn btn-xs btn-warning" onClick="getEdit(<?php echo $rs->id; ?>)"><i class="fa fa-pencil"></i></button>
                        <?php endif; ?>
                        <?php if( $delete ) : ?>
                        	<button type="button" class="btn btn-xs btn-danger" onClick="remove('<?php echo $rs->id; ?>', '<?php echo $rs->name; ?>')">
                            	<i class="fa fa-trash"></i>
                            </button>
                        <?php endif; ?>
                        </td>
                    </tr>
<?php			$no++;	?>
<?php		endwhile; ?>
<?php	else : ?>
				<tr>
                	<td colspan="5" align="center"><h4>ไม่พบรายการ</h4></td>
				</tr>
<?php	endif; ?>
                </tbody>
            </table>
        </div>
    </div>
	<!---- Modal Add --->
    <form id="addForm">
	<div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" style="width:500px;">
			<div class="modal-content">
	  			<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="modal_title">เพิ่มแถบแสดงสินค้า</h4>
				 </div>
				 <div class="modal-body" id="modal_body">
                 <div class="row">
                 	<div class="col-sm-12">
                    	<label>ชื่อแถบ</label>
                        <input type="text" class="form-control input-sm"  name="addName" id="addName" placeholder="กำหนดชื่อแถบ" />
                        <span class="help-block red" id="addName-error"></span>
                    </div>
                    <div class="divider-hidden"></div>
                    <div class="col-sm-12">
                    	<label class="display-block">แถบหลัก</label>
                        <div class="col-sm-12" id="addParentTree"></div>
                        <input type="text" class="hide" name="actction" pattern="Avoid submit" />
                    </div>
                 </div>
                 </div>
				 <div class="modal-footer">
					<button type="button" class="btn btn-sm btn-success" onClick="addNew()" ><i class="fa fa-save"></i> บันทึก</button>
				 </div>
			</div>
		</div>
	</div>
    </form>
    
    
    <!---- Modal Edit --->
    <form id="editForm">
	<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" style="width:500px;">
			<div class="modal-content">
	  			<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="modal_title">แก้ไขแถบแสดงสินค้า</h4>
                    <input type="hidden" name="id_tab" id="id_tab" value="" />
				 </div>
				 <div class="modal-body" id="modal_body">
                 <div class="row">
                 	<div class="col-sm-12">
                    	<label>ชื่อแถบ</label>
                        <input type="text" class="form-control input-sm"  name="editName" id="editName" placeholder="กำหนดชื่อแถบ" />
                        <span class="help-block red" id="editName-error"></span>
                    </div>
                    <div class="divider-hidden"></div>
                    <div class="divider-hidden"></div>
                    <div class="col-sm-12">
                    	<label class="display-block">แถบหลัก</label>
                       <div class="col-sm-12" id="editParentTree"></div>
                       <input type="text" class="hide" name="actction" pattern="Avoid submit" />
                    </div>
                 </div>
                 </div>
				 <div class="modal-footer">
					<button type="button" class="btn btn-sm btn-success" onClick="saveEdit()" ><i class="fa fa-save"></i> บันทึก</button>
				 </div>
			</div>
		</div>
	</div>
	</form>

</div><!--/ container -->
<script src="script/product_tab.js"></script>
<script src="script/validate.js"></script>
