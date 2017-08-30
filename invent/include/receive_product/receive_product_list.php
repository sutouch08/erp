<?php
	$sCode	= isset( $_POST['sCode'] ) ? trim( $_POST['sCode'] ) : ( getCookie('sReceiveCode') ? getCookie('sReceiveCode') : '' );
	$sPo		= isset( $_POST['sPo'] ) ? trim( $_POST['sPo'] ) : ( getCookie('sReceivePo') ? getCookie('sReceivePo') : '' );
	$sInv		= isset( $_POST['sInv'] ) ? trim( $_POST['sInv'] ) : ( getCookie('sReceiveInv') ? getCookie('sReceiveInv') : '' );
	$sFrom	= isset( $_POST['sFrom'] ) ? trim( $_POST['sFrom'] ) : ( getCookie('sFrom') ? getCookie('sFrom') : '' );
	$sTo		= isset( $_POST['sTo'] ) ? trim( $_POST['sTo'] ) : ( getCookie('sTo') ? getCookie('sTo') : '' );

?>
<form id="searchForm" method="post">
<div class="row">
	<div class="col-sm-2 padding-5 first">
    	<label>เลขที่เอกสาร</label>
        <input type="text" class="form-control input-sm text-center search-box" name="sCode" id="sCode" value="<?php echo $sCode; ?>" />
    </div>
    <div class="col-sm-2 padding-5">
    	<label>ใบสั่งซื้อ</label>
        <input type="text" class="form-control input-sm text-center search-box" name="sPo" id="sPo" value="<?php echo $sPo; ?>" />
    </div>
    <div class="col-sm-2 padding-5">
    	<label>ใบส่งสินค้า</label>
        <input type="text" class="form-control input-sm text-center search-box" name="sInv" id="sInv" value="<?php echo $sInv; ?>" />
    </div>
    <div class="col-sm-3 padding-5">
    	<label class="display-block">วันที่</label>
        <input type="text" class="form-control input-sm input-discount text-center" name="sFrom" id="sFrom" placeholder="เริ่มต้น" value="<?php echo $sFrom; ?>" />
        <input type="text" class="form-control input-sm input-unit text-center" name="sTo" id="sTo" placeholder="สิ้นสุด" value="<?php echo $sTo; ?>" />
    </div>
    <div class="col-sm-1 col-1-harf padding-5">
    	<label class="display-block not-show">search</label>
        <button type="button" class="btn btn-sm btn-block btn-primary" onClick="getSearch()"><i class="fa fa-search"></i>  ค้นหา</button>
    </div>
    <div class="col-sm-1 col-1-harf padding-5 last">
    	<label class="display-block not-show">reset</label>
        <button type="button" class="btn btn-sm btn-block btn-warning" onClick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
    </div>
    
</div>
</form>

<hr class="margin-top-15" />
<?php
	$where = "WHERE id != 0 ";
	
	if( $sCode != "" )
	{
		createCookie('sReceiveCode', $sCode);
		$where .= "AND reference LIKE '%".$sCode."%' ";
	}
	
	if( $sPo != "" )
	{
		createCookie('sReceivePo', $sPo);
		$where .= "AND po LIKE '%".$sPo."%' ";
	}
	
	if( $sInv != "" )
	{
		createCookie('sReceiveInv', $sInv);
		$where .= "AND invoice LIKE '%".$sInv."%' ";
	}
	
	if( $sFrom != "" && $sTo != "" )
	{
		createCookie('sFrom', $sFrom);
		createCookie('sTo', $sTo);
		$where .= "AND date_add >= '". fromDate($sFrom) ."' AND date_add <= '".toDate($sTo)."' ";	
	}
	
	$where .= "ORDER BY reference DESC";
	
	$paginator = new paginator();
	$get_rows = get_rows();
	$paginator->Per_Page("tbl_receive_product", $where, $get_rows);
	$paginator->display($get_rows, "index.php?content=receive_product");
	
	
	$qs = dbQuery("SELECT * FROM tbl_receive_product ".$where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);
	
?>
<div class="row">
	<div class="col-sm-12">
    	<table class="table table-striped table-bordered">
        	<thead>
            	<tr>
                	<th class="width-5 text-center">ลำดับ</th>
                    <th class="width-10 text-center">วันที่</th>
                    <th class="width-15 text-center">เลขที่เอกสาร</th>
                    <th class="width-15 text-center">ใบส่งสินค้า</th>
                    <th class="width-15 text-center">ใบสั่งซื้อ</th>
                    <th class="width-10 text-center">จำนวน</th>
                    <th class="width-10 text-center">สถานะ</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
<?php if( dbNumRows($qs) > 0 ) : ?>
<?php	$no = row_no();			?>
<?php	$cs = new receive_product();	?>
<?php	while( $rs = dbFetchObject($qs) ) : ?>
				<tr class="font-size-12">
                	<td class="middle text-center"><?php echo number_format($no); ?></td>
                    <td class="middle text-center"><?php echo thaiDate($rs->date_add); ?></td>
                    <td class="middle"><?php echo $rs->reference; ?></td>
                    <td class="middle"><?php echo $rs->invoice; ?></td>
                    <td class="middle"><?php echo $rs->po; ?></td>
                    <td class="middle text-center"><?php echo number_format( $cs->getTotalQty( $rs->id ) ); ?></td>
                    <td class="middle text-center">
                    <?php if( $rs->status == 1 ) : ?>
                    	<span class="green">บันทึกแล้ว</span>
					<?php else : ?>
                    	<span class="red">ยังไม่บันทึก</span>                    
                    <?php endif; ?>
                    </td>
                    <td class="middle text-right">
                    	<button type="button" class="btn btn-xs btn-info" onClick="goDetail(<?php echo $rs->id; ?>)"><i class="fa fa-eye"></i></button>
                    <?php if( $edit ) : ?>
                    	<button type="button" class="btn btn-xs btn-warning" onClick="goEdit(<?php echo $rs->id; ?>)"><i class="fa fa-pencil"></i></button>
                    <?php endif; ?>
                    <?php if( $delete ) : ?>
                    	<button type="button" class="btn btn-xs btn-danger" onClick="goDelete(<?php echo $rs->id; ?>)"><i class="fa fa-trash"></i></button>
                    <?php endif; ?>
                    </td>
                </tr>
<?php		$no++; ?>
<?php	endwhile; ?>			
<?php else : ?>
				<tr>
                	<td colspan="8" class="text-center"><h4>ไม่พบรายการ</h4></td>
                </tr>
<?php endif; ?>            
            </tbody>            
        </table>
    </div>
</div>
