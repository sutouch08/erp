<?php 
	$id_tab = 21;
   $pm 	= checkAccess($id_profile, $id_tab);
	$view = $pm['view'];
	$add 	= $pm['add'];
	$edit 	= $pm['edit'];
	$delete = $pm['delete'];
	accessDeny($view);
	?>
    
<div class="container">
    <div class="row top-row">
        <div class="col-sm-6 top-col">
            <h4 class="title"><i class="fa fa-users"></i> <?php echo $pageTitle; ?></h4>
        </div>
        <div class="col-sm-6">
            <p class="pull-right top-p">
		<?php if( isset( $_GET['edit'] ) OR isset( $_GET['view_detail'] ) ) : ?>
        		<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
        <?php endif; ?>        
        <?php if( ! isset( $_GET['add'] ) && ! isset( $_GET['edit'] ) && ! isset( $_GET['view_detail'] ) ) : ?>        
                <button class="btn btn-sm btn-success" onclick="syncCustomer()"><i class="fa fa-refresh"></i> อัพเดตข้อมูล</button>
        <?php endif; ?>
        <?php if( isset( $_GET['edit'] ) && isset( $_GET['id'] ) ) : ?>
        		<button type="button" class="btn btn-sm btn-success" onclick="saveEdit()"><i class="fa fa-save"></i> บันทึก</button>
        <?php endif; ?>
            </p>
        </div>
    </div>
    <hr />
<?php 
if( isset( $_GET['edit'] ) )
{
	include 'include/customer_edit.php';
}
else if( isset( $_GET['view_detail'] ) )
{
	
}
else
{
	include 'include/customer_list.php';	
}
?>

</div><!--/ Container -->
<script src="script/customer.js"></script>
