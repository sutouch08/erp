<?php
	$pageName	= "การตั้งค่า";
	$id_tab 		= 25;
	$id_profile 	= $_COOKIE['profile_id'];
    $pm		= checkAccess($id_profile, $id_tab);
	$view 	= $pm['view'];
	$add 		= $pm['add'];
	$edit 		= $pm['edit'];
	$delete 	= $pm['delete'];

	$su		= checkAccess($id_profile, 61); //-------  ตรวจสอบว่ามีสิทธิ์ในการปิดระบบหรือไม่  -----//
	$cando	= ($su['view'] + $su['add'] + $su['edit'] + $su['delete'] ) > 0 ? TRUE : FALSE;
	accessDeny($view);
	?>
<script src="<?php echo WEB_ROOT; ?>library/ckeditor/ckeditor.js"></script>
<script src="<?php echo WEB_ROOT; ?>library/ckfinder/ckfinder.js"></script>
<div class="container">
<div class="row top-row">
	<div class="col-lg-12 top-col">
    	<h4 class="title"><?php echo $pageName; ?></h4>
	</div>
</div>
<hr style="border-color:#CCC; margin-top: 15px; margin-bottom:0px;" />
<!----------------------------- ตั้งค่าทั่วไป  ----------------------->
<div class="row">
<div class="col-sm-2 padding-right-0" style="padding-top:15px;">
<ul id="myTab1" class="setting-tabs">
        <li class="li-block active"><a href="#general" data-toggle="tab">ตั้งค่าทั่วไป</a></li>
        <li class="li-block"><a href="#product" data-toggle="tab">ตั้งค่าสินค้า</a></li>
         <li class="li-block"><a href="#order" data-toggle="tab">ตั้งค่าออเดอร์</a></li>
        <li class="li-block"><a href="#document" data-toggle="tab">ตั้งค่าเอกสาร</a></li>
        <li class="li-block"><a href="#other" data-toggle="tab">ตั้งค่าอื่นๆ</a></li>
</ul>
</div>
<div class="col-sm-10" style="padding-top:15px; border-left:solid 1px #ccc; min-height:600px; max-height:1000px;">
<div class="tab-content">
        <!-------------------------------------------------------  ตั้งค่าทั่วไป  ----------------------------------------------------->
            <?php include 'include/setting_general.php'; ?>
		<!-------------------------------------------------------  ตั้งค่าสินค้า  ----------------------------------------------------->
            <?php include 'include/setting_product.php'; ?>
		<!-------------------------------------------------------  ตั้งค่าออเดอร์  --------------------------------------------------->
            <?php include 'include/setting_order.php'; ?>
        <!-------------------------------------------------------  ตั้งค่าเอกสาร  --------------------------------------------------->
            <?php include 'include/setting_document.php'; ?>
		<!-------------------------------------------------------  ตั้งค่าอื่นๆ  ------------------------------------------------------>
         	<?php include 'include/setting_other.php'; ?>
</div>
</div><!--/ col-sm-9  -->
</div><!--/ row  -->

</div><!---/ container -->

<script>
CKEDITOR.replace( 'content',{
	toolbarGroups: [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'links' },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'tools' },
		{ name: 'others' },
		{ name: 'about' }
	]
});

//------------  UPDATE TEXT AREA BEFORE SERIALIZE ---------------//
function CKupdate()
{
    for ( instance in CKEDITOR.instances )
	{
        CKEDITOR.instances[instance].updateElement();
	}
}

function updateConfig(formName)
{
	load_in();
	CKupdate();
	var formData = $("#"+formName).serialize();
	$.ajax({
		url:"controller/settingController.php?updateConfig",
		type:"POST", cache:"false", data: formData,
		success: function(rs){
			load_out();
			console.log(rs);
		}
	});
}


function openShop()
{
	$("#shopOpen").val(1);
	$("#btn-sclose").removeClass('btn-danger');
	$("#btn-sopen").addClass('btn-success');
}

function closeShop()
{
	$("#shopOpen").val(0);
	$("#btn-sopen").removeClass('btn-success');
	$("#btn-sclose").addClass('btn-danger');
}

function openSystem()
{
	$("#closed").val(0);
	$("#btn-close").removeClass('btn-danger');
	$("#btn-open").addClass('btn-success');
}

function closeSystem()
{
	$("#closed").val(1);
	$("#btn-open").removeClass('btn-success');
	$("#btn-close").addClass('btn-danger');
}

function allow()
{
	$("#allowUnderZero").val(1);
	$("#btn-not-allow").removeClass('btn-danger');
	$("#btn-allow").addClass('btn-success');
}

function notAllow()
{
	$("#allowUnderZero").val(0)
	$("#btn-allow").removeClass('btn-success');
	$("#btn-not-allow").addClass('btn-danger');
}


function checkPrefix(){
	var pre = [];
	$('.prefix').each(function(index, el) {
		pre.push({'name':$(this).val(), 'value':$(this).val()});
	});

	return pre;
}

$(".prefix").keyup(function(e) {
  var pf = $(this).val();
	var du = 0;
	if(pf != "")
	{
		$(".prefix").each(function(index, element) {
      var val = $(this).val();
			if(val == pf){ du += 1; }
        });
		if(du > 1 )
		{
			swal("ตัวย่อซ้ำ");
			$(this).val('');
			return false;
		}
	}
});
</script>
