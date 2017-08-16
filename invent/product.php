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
if( isset( $_GET['edit'] ) )
{
	include 'include/product_edit.php';
}
else if( isset( $_GET['deleted'] ) )
{
	include 'include/product_deleted.php';
}
else
{
	include 'include/product_list.php';	
}

?>


</div><!--/ Container -->

<script src="script/product.js"></script>




