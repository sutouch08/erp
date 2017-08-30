<?php
	$id_tab 			= 47;
	$id_profile 		= $_COOKIE['profile_id'];
    $pm 				= checkAccess($id_profile, $id_tab);
	$view 			= $pm['view'];
	$add 				= $pm['add'];
	$edit 				= $pm['edit'];
	$delete 			= $pm['delete'];
	accessDeny($view);
?>

<div class="container">
<!-- page place holder -->

<div class="row top-row">
	<div class="col-sm-6 top-col">
    	<h4 class="title" ><i class="fa fa-download"></i>&nbsp;<?php echo $pageTitle; ?></h4>
	</div>
    <div class="col-sm-6">
      	<p class="pull-right top-p">
<?php	if( ! isset( $_GET['add'] ) && ! isset( $_GET['edit'] )  && ! isset( $_GET['view_detail'] ) && $add ) : ?>
				<button type="button" class="btn btn-sm btn-success" onclick="goAdd()"><i class="fa fa-plus"></i> เพิ่มใหม่</button>
<?php	endif; ?>        
<?php	if( isset( $_GET['add'] ) OR isset( $_GET['edit'] ) OR isset( $_GET['view_detail'] ) ) : ?>
				<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
<?php	endif; ?>        	
        </p>
    </div>
</div>
<hr />
<?php

if( isset( $_GET['add'] ) )
{
	include 'include/receive_product/receive_product_add.php';	
}
else if( isset( $_GET['edit'] ) )
{
	include 'include/receive_product/receive_product_edit.php';
}
else if( isset( $_GET['view_detail'] ) )
{
	include 'include/receive_product/receive_product_detail.php';	
}
else
{
	include 'include/receive_product/receive_product_list.php';	
}

?>



</div><!-- /container -->
<script src="script/receive_product/receive_product.js"></script>
<!---------------  Beep sount for alert ----------->
<script src="../library/js/beep.js" type="text/javascript"></script>