<?php
	$id_tab 		= 1;
    $pm 			= checkAccess($id_profile, $id_tab);
	$view 		= $pm['view'];
	$add			= $pm['add'];
	$edit			= $pm['edit'];
	$delete 		= $pm['delete'];
	accessDeny($view);	
?>
<div class="container">
	<div class="row top-row">
    	<div class="col-sm-6 top-col">
        	<h4 class="title"><i class="fa fa-tags"></i> <?php echo $pageTitle; ?></h4>
        </div>
        <div class="col-sm-6">
        	<p class="pull-right top-p">
            	<button class="btn btn-sm btn-success" onClick="syncMaster()"><i class="fa fa-refresh"></i> อัพเดตข้อมูล</button>
            </p>
        </div>
    </div>
    <hr/>
<?php	
			$sCode 	= isset( $_POST['stCode'] ) ? trim( $_POST['stCode'] ) : ( getCookie('stCode') ? trim( getCookie('stCode') ) : '' ); 		
			$sName	= isset( $_POST['stName'] ) ? trim( $_POST['stName'] ) : ( getCookie('stName') ? trim( getCookie('stName') ) : '' ); 
?>			
    
    
    <form id="searchForm" method="post">
    <div class="row">
    	<div class="col-sm-3">
        	<label>รหัส</label>
            <input type="text" class="form-control input-sm text-center search-box" name="stCode" id="stCode" placeholder="ค้นหารหัสกลุ่ม" value="<?php echo $sCode; ?>"  />
        </div>
        <div class="col-sm-3">
        	<label>ชื่อ</label>
            <input type="text" class="form-control input-sm text-center search-box" name="stName" id="stName" placeholder="ค้นหารชื่อกลุ่ม" value="<?php echo $sName; ?>" autofocus />
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
	if( $sCode != '' )
	{
		createCookie('stCode', $sCode);
		$where .= "AND code LIKE '%". $sCode ."%' ";
	}
	
	if( $sName != '' )
	{
		createCookie('stName', $sName);
		$where .= "AND name LIKE '%". $sName ."%' ";
	}
	
	$paginator	= new paginator();
	$get_rows	= get_rows();
	$paginator->Per_Page('tbl_style', $where, $get_rows);
	$paginator->display($get_rows, 'index.php?content=style');
	
	$qs = dbQuery("SELECT * FROM tbl_style ".$where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);
?>    
	<div class="row">
    	<div class="col-sm-12">
        	<table class="table table-striped border-1">
            	<thead>
                	<tr>
                    	<th class="width-10 text-center">ลำดับ</th>
                        <th class="width-20">รหัส</th>
                        <th class="width-50">ชื่อ</th>
                        <th class="width-10"></th>
                    </tr>
                </thead>
                <tbody>
<?php	if( dbNumRows($qs) > 0 ) : ?>
<?php		$no = row_no(); ?>
<?php		while( $rs = dbFetchObject($qs) ) : ?>
					<tr class="font-size-12" id="row_<?php echo $rs->id; ?>">
                    	<td class="middle text-center"><?php echo $no; ?></td>
                        <td class="middle"><?php echo $rs->code; ?></td>
                        <td class="middle"><?php echo $rs->name; ?></td>
                        <td class="middle text-right">
                        <?php if( $delete ) : ?>
                        	<button type="button" class="btn btn-sm btn-danger" onClick="remove('<?php echo $rs->id; ?>', '<?php echo $rs->code; ?>')"><i class="fa fa-trash"></i></button>
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
    
</div><!--/ Container -->
<script src="script/style.js"></script>