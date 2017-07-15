
<?php if( ! isset( $_GET['id'] ) ) : ?>
<?php 	include 'include/page_error.php'; ?>
<?php else : ?>
<?php 	$customer = new customer($_GET['id'] );  ?>
<div class="row">
<div class="col-sm-2 padding-right-0" style="padding-top:15px;">
<ul id="myTab1" class="setting-tabs">
        <li class="li-block active"><a href="#page1" data-toggle="tab">หน้าที่ 1</a></li>
        <li class="li-block"><a href="#page2" data-toggle="tab">หน้าที่ 2</a></li>
        <li class="li-block"><a href="#page3" data-toggle="tab">หน้าที่ 3</a></li>
        <li class="li-block"><a href="#page4" data-toggle="tab">หน้าที่ 4</a></li>
</ul>
</div>
<div class="col-sm-10" style="padding-top:15px; border-left:solid 1px #ccc; min-height:600px; max-height:1000px;">
<div class="tab-content">
        <?php include 'include/customer_edit1.php'; ?>     
        <?php include 'include/customer_edit2.php'; ?>
</div>
</div><!--/ col-sm-9  -->    
</div><!--/ row  -->

<?php endif; ?>