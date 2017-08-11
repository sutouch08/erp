<?php
	$id_tab 			= 1;
    $pm 				= checkAccess($id_profile, $id_tab);
	$view 			= $pm['view'];
	$add 				= $pm['add'];
	$edit 				= $pm['edit'];
	$delete 			= $pm['delete'];
	accessDeny($view);
	require 'function/product_helper.php';
	?>
<div class="container">
	<div class="row top-row">
    	<div class="col-sm-6 top-col">
        	<h4 class="title"><i class="fa fa-tags"></i> <?php echo $pageTitle; ?></h4>
        </div>
        <div class="col-sm-6">
        	<p class="pull-right top-p">
            	<button type="button" class="btn btn-sm btn-success" onclick="syncMaster()"><i class="fa fa-plus"></i> อัพเดตข้อมูล</button>
            </p>
        </div>
    </div>
    <hr />
<?php
	$sProduct	= isset( $_POST['sProduct'] ) ? trim( $_POST['sProduct'] ) : ( getCookie('sProduct') ? getCookie('sProduct') : '' );
	$sStyle		= isset( $_POST['sStyle'] ) ? trim( $_POST['sStyle'] ) : ( getCookie('sStyle') ? getCookie('sStyle') : '' );
	

?>    
<form id="searchForm" method="post">
	<div class="row">
    	<div class="col-sm-2">
        	<label>สินค้า</label>
            <input type="text" class="form-control input-sm text-center search-box" name="sProduct" id="sProduct" value="<?php echo $sProduct; ?>" />
        </div>
        <div class="col-sm-2">
        	<label>รุ่นสินค้า</label>
            <input type="text" class="form-control input-sm text-center search-box" name="sStyle" id="sStyle" value="<?php echo $sStyle; ?>" />
        </div>
    </div>
</form>    
<hr class="margin-top-15" />
</div><!--/ Container -->

<script src="script/product.js"></script>




