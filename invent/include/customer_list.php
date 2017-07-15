
<?php $cName	= isset( $_POST['cName'] ) ?  trim( $_POST['cName'] ) : ( getCookie('cName') ? getCookie('cName') : '') ; 		?>
<?php $cCode	= isset( $_POST['cCode'] ) ? trim( $_POST['cCode'] ) : ( getCookie('cCode') ? getCookie('cCode') : '' ); 		?>   
<?php $cProvince = isset( $_POST['cProvince'] ) ? trim( $_POST['cProvince'] ) : ( getCookie('cProvince') ? getCookie('cProvince') : '' ); ?>
<?php $nFocus = $cName != '' ? "autofocus" : ( $cCode =="" && $cProvince == "" ? "autofocus" : ""); ?>
<?php $cFocus = $cName == "" && $cCode != "" ? "autofocus" : ""; ?>
<?php $pFocus = $cName == "" && $cCode == "" && $cProvince != "" ? "autofocus" : ""; ?>
    
    <form id="searchForm" method="post">
    <div class="row">
    	<div class="col-sm-3">
        	<label>ชื่อ</label>
            <input type="text" class="form-control input-sm text-center search-box" name="cName" id="cName" placeholder="ค้นหาชื่อลูกค้า" value="<?php echo $cName; ?>" <?php echo $nFocus; ?>  />
        </div>
        <div class="col-sm-3">
        	<label>รหัส</label>
            <input type="text" class="form-control input-sm text-center search-box" name="cCode" id="cCode" placeholder="ค้นหารหัสลูกค้า" value="<?php echo $cCode; ?>" <?php echo $cFocus; ?> />
        </div>
        <div class="col-sm-3">
        	<label>จังหวัด</label>
            <input type="text" class="form-control input-sm text-center search-box" name="cProvince" id="cProvince" placeholder="ค้นหารหัสลูกค้า" value="<?php echo $cProvince; ?>" <?php echo $pFocus; ?> />
        </div>
        <div class="col-sm-1 col-1-harf">
        	<label class="display-block not-show">apply</label>
            <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()" ><i class="fa fa-search"></i> Search</button>
        </div>
       <div class="col-sm-1 col-1-harf">
        	<label class="display-block not-show">reset</label>
            <button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()" ><i class="fa fa-retweet"></i> Reset</button>
        </div>
    </div>
    </form>
<hr class="margin-top-15"/>

<?php
	$where = "WHERE id != 0 ";
	if( $cName != '' )
	{
		createCookie('cName', $cName);
		$where .= "AND name LIKE '%". $cName ."%' ";
	}
	
	if( $cCode != '' )
	{
		createCookie('cCode', $cCode);
		$where .= "AND code LIKE '%" . $cCode . "%' ";
	}
	
	if( $cProvince != '' )
	{
		createCookie('cProvince', $cProvince);
		$where .= "AND province LIKE '%" . $cProvince ."%' ";	
	}
	
	$where .= "ORDER BY name ASC";
	
	$paginator	= new paginator();
	$get_rows	= get_rows();
	$paginator->Per_Page('tbl_customers', $where, $get_rows);
	$paginator->display($get_rows, 'index.php?content=customer');
	
	$qs = dbQuery("SELECT * FROM tbl_customers ". $where ." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);
?>

<div class="row">
	<div class="col-sm-12">
    	<table class="table table-striped border-1">
        	<thead>
            	<th class="width-5 text-center">ลำดับ</th>
                <th class="width-20">รหัส</th>
                <th class="width-40">ชื่อ</th>
                <th class="width-15">จังหวัด</th>
                <th></th>
            </thead>
            <tbody>
	<?php if( dbNumRows( $qs ) > 0 ) : ?>
    <?php	$no = row_no(); ?>
    <?php	while( $rs = dbFetchObject($qs) ) : ?>
    			<tr class="font-size-12" id="row_<?php echo $rs->code; ?>">
                	<td class="middle text-center"><?php echo number_format($no); ?></td>
                    <td class="middle"><?php echo $rs->code; ?></td>
                    <td class="middle"><?php echo $rs->name; ?></td>
                    <td class="middle"><?php echo $rs->province; ?></td>
                    <td class="middle text-right">
                    	<button type="button" class="btn btn-xs btn-info" onclick="viewDetail(<?php echo $rs->id; ?>)"><i class="fa fa-search"></i> รายละเอียด</button>
				<?php if( $edit ) : ?>
        				<button type="button" class="btn btn-xs btn-warning" onclick="getEdit(<?php echo $rs->id; ?>)"><i class="fa fa-pencil"></i> แก้ไข</button>
        		<?php endif; ?>  
                <?php if( $delete ) : ?>
                		<button type="button" class="btn btn-xs btn-danger" onclick="deleteCustomer(<?php echo $rs->id; ?>, '<?php echo $rs->name; ?>')"><i class="fa fa-trash"></i> ลบ</button>
                <?php endif; ?>                 
                    </td>
                </tr>
	<?php	$no++; ?>                
    <?php	endwhile; ?>
    <?php else : ?>
    		<tr>
            	<td colspan="5" align="center"><h4> ไม่พบข้อมูลตามเงื่อนไขที่กำหนด</h4></td>
            </tr>
    <?php endif; ?>            	
            </tbody>
        </table>
    </div>
</div>
