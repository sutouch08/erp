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
		type:"POST",
    cache:"false",
    data: formData,
		success: function(rs){
			load_out();
      rs = $.trim(rs);
      if(rs == 'success'){
        swal({
          title:'Updated',
          type:'success',
          timer:1000
        });
      }else{
        swal('Error!', rs, 'error');
      }
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
