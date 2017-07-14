// JavaScript Document
//----------------  Inititaiils ------------//
var codeError = 0;
var nameError = 0;

function showError(el, error){
	var EL = 	$("#"+el+"-error");
	if( error != ''){
		EL.text(error);
	}
	EL.removeClass('not-show');
	$("#"+el).addClass('has-error');
}

function hideError(el){
	$("#"+el+"-error").addClass('not-show');
	$("#"+el).removeClass('has-error');
}

//---------------- End Inititaiils ------------//

//------------------ Edit Page --------------//
$("#edit-zWH").change(function(e) {
    if( $(this).val() != 0 ){
		hideError('edit-zWH');
		$("#edit-zCode").focus();
	}else{
		showError('edit-zWH', 'จำเป็นต้องเลือก');
	}
});

$("#edit-zCode").keyup(function(e){
	if( e.keyCode == 13){
		hideError('edit-zCode');
		if( $(this).val() != ""){
			$("#edit-zName").focus();
		}
	}
});

$("#edit-zCode").focusout(function(e) {
    if( $(this).val() != "" ){
		var id = $("#id_zone").val();
		$.ajax({
			url:"controller/zoneController.php?checkBarcode",
			type:"GET", cache:false, data:{ "barcode" : $(this).val(), "id_zone" : id },
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'duplicate' ){
					codeError = 1;
					showError('edit-zCode', 'รหัสซ้ำ');
				}else{
					codeError = 0;
					hideError('edit-zCode');
				}
			}
		});
	}
});

$("#edit-zName").keyup(function(e){
	if( e.keyCode == 13 && $(this).val() != "" ){
		var name = $(this).val();
		var id		= $("#id_zone").val();
		hideError('edit-zName');
		$.ajax({
			url:"controller/zoneController.php?checkName",
			type:"GET", cache:false, data:{ "name" : name, "id_zone" : id },
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'duplicate' ){
					nameError = 1;
					showError('edit-zName', 'ชื่อซ้ำ');
				}else{
					nameError = 0;
					hideError('edit-zName');
				}
			}
		});
	}
});

$("#edit-zName").focusout(function(e){
	if( $(this).val() != "" ){
		var name = $(this).val();
		var id		= $("#id_zone").val();
		hideError('edit-zName');
		$.ajax({
			url:"controller/zoneController.php?checkName",
			type:"GET", cache:false, data:{ "name" : name, "id_zone" : id },
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'duplicate' ){
					nameError = 1;
					showError('edit-zName', 'ชื่อซ้ำ');
				}else{
					nameError = 0;
					hideError('edit-zName');
				}
			}
		});
	}
});


function saveEdit(){
	var zWH 		= $("#edit-zWH").val();
	var zCode	= $("#edit-zCode").val();
	var zName	= $("#edit-zName").val();
	var id			= $("#id_zone").val();
	console.log(codeError);
	console.log(nameError);
	if( zWH == 0 ){
		showError('edit-zWH', 'จำเป็นต้องเลือก');
		return false;
	}

	if( zCode == '' || codeError == 1){
		showError('edit-zCode', 'รหัสไม่ถูกต้อง');
		return false;
	}

	if( zName == '' || nameError == 1){
		showError('edit-zName', 'ชื่อไม่ถูกต้อง');
		return false;
	}

	load_in();
	$.ajax({
		url:"controller/zoneController.php?updateZone",
		type:"POST", cache:"false", data: { "id_warehouse" : zWH, "code" : zCode, "name" : zName, "id_zone" : id },
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( 	rs == 'success' ){
				swal({ title: 'สำเร็จ' , text: 'ปรับปรุงข้อมูลเรียบร้อยแล้ว', type: 'success', timer: 1000 });
			}else{
				swal('ข้อผิดพลาด', 'ปรับปรุงข้อมูลไม่สำเร็จ', 'error');
			}
		}
	});
}

//------------ Add Page ------------//
$("#add-zWH").change(function(e) {
    if( $(this).val() != 0 ){
		hideError('add-zWH');
		$("#add-zCode").focus();
	}else{
		showError('add-zWH', 'จำเป็นต้องเลือก');
	}
});

$("#add-zCode").keyup(function(e){
	if( e.keyCode == 13){
		hideError('add-zCode');
		if( $(this).val() != ""){
			$("#add-zName").focus();
		}
	}
});


$("#add-zCode").focusout(function(e) {
    if( $(this).val() != "" ){
		$.ajax({
			url:"controller/zoneController.php?checkBarcode",
			type:"GET", cache:false, data:{ "barcode" : $(this).val() },
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'duplicate' ){
					codeError = 1;
					showError('add-zCode', 'รหัสซ้ำ');
				}else{
					codeError = 0;
					hideError('add-zCode');
				}
			}
		});
	}
});

$("#add-zName").keyup(function(e){
	if( e.keyCode == 13 && $(this).val() != "" ){
		var name = $(this).val();
		hideError('add-zName');
		$.ajax({
			url:"controller/zoneController.php?checkName",
			type:"GET", cache:false, data:{ "name" : name },
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'duplicate' ){
					nameError = 1;
					showError('add-zName', 'ชื่อซ้ำ');
				}else{
					nameError = 0;
					hideError('add-zName');
					saveAdd();
				}
			}
		});
	}
});

function saveAdd(){
	var zWH 		= $("#add-zWH").val();
	var zCode	= $("#add-zCode").val();
	var zName	= $("#add-zName").val();
	if( zWH == 0 ){
		showError('add-zWH', 'จำเป็นต้องเลือก');
		return false;
	}

	if( zCode == '' || codeError == 1){
		showError('add-zCode', 'รหัสไม่ถูกต้อง');
		return false;
	}

	if( zName == '' || nameError == 1){
		showError('add-zName', 'ชื่อไม่ถูกต้อง');
		return false;
	}

	load_in();
	$.ajax({
		url:"controller/zoneController.php?addNewZone",
		type:"POST", cache:"false", data: { "id_warehouse" : zWH, "code" : zCode, "name" : zName },
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( 	isJson(rs) ){
				var source	= $("#addRow-Template").html();
				var data		= $.parseJSON(rs);
				var output	= $("#add-table");
				render_append(source, data, output);
				$("#add-zName").val('');
				$("#add-zCode").val('');
				$("#add-zCode").focus();
			}else{
				swal(rs);
			}
		}
	});
}

//----------------- End Add Page -----------------//

//--------- List Page -------//
function deleteZone(id, zoneName){
	swal({
		title: 'คุณแน่ใจ ? ',
		text: 'ต้องการลบ '+zoneName+' ใช่หรือไม่? โปรดทราบว่า เมื่อลบสำเร็จแล้วไม่สามารถย้อนคืนได้',
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#DD6855',
		confirmButtonText: 'ใช่ ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
	}, function(){
		$.ajax({
			url:"controller/zoneController.php?deleteZone",
			type:"POST", cache:"false", data:{ "id_zone" : id },
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'success' ){
					$("#row_"+id).remove();
					swal({ title: 'สำเร็จ', text: 'ลบรายการเรียบร้อยแล้ว', type: 'success', timer: 1000 });
				}else{
					swal({title: 'ข้อผิดพลาด', text: rs, type: 'error' });	
				}
			}
		});
	});
}



$("#zCode").keyup(function(e){
	if( e.keyCode == 13 ){
		getSearch();
	}
});


$("#zName").keyup(function(e){
	if( e.keyCode == 13 ){
		getSearch();
	}
});

$("#zWH").change(function(e) {
    getSearch();
});

function addNew(){
	window.location.href = 'index.php?content=zone&add';
}

function editZone(id){
	window.location.href = "index.php?content=zone&edit&id_zone="+id;	
}


function goBack(){
	window.location.href = 'index.php?content=zone';
}

function getSearch(){
	$("#searchForm").submit();
}

function clearFilter(){
	var zCode 	= $("#zCode").val();
	var zName	= $("#zName").val();
	var zWH		= $("#zWH").val();

	if( zCode != "" || zName != "" || zWH != 0 ){
		$.ajax({
			url:"controller/zoneController.php?clearFilter",
			type:"POST", cache:"false", success: function(){
				goBack();
			}
		});
	}
}

//------------------ End List Page  ------------------------///