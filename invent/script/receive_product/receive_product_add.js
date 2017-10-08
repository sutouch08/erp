// JavaScript Document

var data = [];
var poError = 0;
var invError = 0;
var zoneError = 0;


function receiveProduct(){
	var qty = isNaN( parseInt( $("#qty").val() ) ) ? 1 : parseInt( $("#qty").val() );
	var bc = $("#barcode");
	var barcode = bc.val();
	if( barcode.length > 0 ){
		bc.val('');
		bc.attr('disabled', 'disabled');
		var input = $("#receive-"+barcode);
		var count = input.length;
		if( count == 1 ){
			var cqty = input.val() == "" ? 0 : parseInt(input.val());
			qty += cqty;
			input.val(qty);
			$("#qty").val(1);
		}else{
			swal({
				title: "ข้อผิดพลาด !",
				text: "บาร์โค้ดไม่ถูกต้องหรือสินค้าไม่ตรงกับใบสั่งซื้อ",
				type: "error"},
				function(){
					setTimeout( function(){ $("#barcode")	.focus(); }, 1000 );
				});
		}
		bc.removeAttr('disabled');
		bc.focus();
	}
}





function save(){
	var date			= $("#dateAdd").val();
	var id_sup		= $("#id_supplier").val();
	var po			= $("#poCode").val();
	var invoice 		= $("#invoice").val();
	var zoneName 	= $("#zoneName").val();
	var id_zone		= $("#id_zone").val();
	var remark		= $("#remark").val();
	var count = $(".receive-box").length;
	var id_rec		= $("#id_receive_product").val();

	if( id_rec == "" )
	{

		if( count == 0 ){
			swal("ข้อผิดพลาด !", "ไม่พบรายการ", "error");
			return false;
		}

		//---- validate Date
		if( ! isDate(date) ){
			var message = "วันที่ไม่ถูกต้อง";
			addError($("#dateAdd"), $("#date-error"), message);
			return false;
		}else{
			removeError($("#dateAdd"), $("#date-error"), "");
		}

		//--- validate PO Reference
		if( po.length == 0  ){
			var message = "กรุณาระบุใบสั่งซื้อ";
			addError($("#poCode"), $("#po-error"), message);
			return false;
		}else{
			removeError($("#poCode"), $("#po-error"),"");
		}

		//--- validate zone
		if( zoneName.length == 0 || id_zone == "" ){
			var message = "กรุณาระบุโซนรับเข้า";
			addError($("#zoneName"), $("#zone-error"), message);
			return false;
		}else{
			removeError($("#zoneName"), $("#zone-error"), "");
		}

		var receive = $("#receiveForm").serializeArray();
		//load_in();
		$.ajax({
			url:"controller/receiveProductController.php?addNew&date="+date+"&id_supplier="+id_sup+"&po="+po+"&invoice="+invoice+"&id_zone="+id_zone+"&remark="+remark,
			type:"POST", cache:"false", data: $("#receiveForm").serializeArray(),
			success: function(rs){
				//load_out();
				var rs = $.trim(rs);
				arr = rs.split(' | ');
				if( arr[0] == 'success' ){
					var id_receive_product = arr[1];
					if( id_receive_product.length > 0 )
					{
						load_in();
						$.ajax({
							url:"controller/interfaceController.php?export&BI",
							type:"POST", cache:"false", data:{ "id_receive_product" : id_receive_product },
							success: function(rs){
								load_out();
								var rs = $.trim(rs);
								if( rs == 'success' ){
									swal({ title: "Success", type:"success", timer: 1000 });
									setTimeout(function(){ goBack(); }, 1200);
								}else{
									swal("ข้อผิดพลาด !", rs, "error");
								}
							}
						});
					}else{
						swal("id_receive_product not found");
					}
					//swal({title: "บันทึกสำเร็จ", type: "success", timer: 1000 });
					//
				}else{
					swal("ข้อผิดพลาด !", rs, "error");
				}
			}
		});
	}else{
		doExport();
	}
}






function checkLimit(){
	var limit = $("#overLimit").val();
	var over = 0;
	$(".barcode").each(function(index, element) {
        var arr = $(this).attr("id").split('_');
		var barcode = arr[1];
		var limit = parseInt($("#limit_"+barcode).val() );
		var qty = parseInt($("#receive-"+barcode).val() );
		if( ! isNaN(limit) && ! isNaN( qty ) ){
			if( qty > limit ){
				over++;
			}
		}
    });
	if( over > 0 ){
		getApprove();
	}else{
		save();
	}
}






$("#sKey").keyup(function(e) {
    if( e.keyCode == 13 ){
		doApprove();
	}
});





function getApprove(){
	$("#approveModal").modal("show");
}





$("#approveModal").on('shown.bs.modal', function(){ $("#sKey").focus(); });






function doApprove(){
	var password = $("#sKey").val();
	if( password.length > 0 )
	{
		$.ajax({
			url:"controller/receiveProductController.php?getApprove",
			type:"GET", cache:"false", data:{ "sKey" : password },
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'fail' ){
					$("#id_emp").val('');
					$("#sKey").val('');
					$("#approvError").removeClass('not-show');
				}else{
					var arr = rs.split(' | ');
					$("#id_emp").val(arr[0]);
					$("#approvKey").val(arr[1]);
					$("#approveModal").modal('hide');
					save();
				}
			}
		});
	}
}





function leave(){
	swal({
		title: 'ยกเลิกข้อมูลนี้ ?',
		type: 'warning',
		showCancelButton: true,
		cancelButtonText: 'No',
		confirmButtonText: 'Yes',
		closeOnConfirm: false
	}, function(){
		goBack();
	});

}





function getData(){
	var po = $("#poCode").val();
	load_in();
	$.ajax({
		url:"controller/receiveProductController.php?getPoData",
		type:"GET", cache:"false", data:{ "reference" : po },
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( isJson(rs) ){
				data = $.parseJSON(rs);
				var source = $("#template").html();
				var output = $("#receiveTable");
				render(source, data, output);
				$("#poCode").attr('disabled', 'disabled');
				$(".receive-box").numberOnly();
				$(".receive-box").keyup(function(e){
    				sumReceive();
				});
			}else{
				swal("ข้อผิดพลาด !", rs, "error");
				$("#receiveTable").html('');
			}
		}
	});
}





function getEdit(){
	var id = $("#id_receive_product").val();
	$.ajax({
		url:"controller/receiveProductController.php?isExistsDetails",
		type:"GET", cache:"false", data:{ "id_receive_product" : id },
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'no_details' ){
				$(".input-header").removeAttr('disabled');
			}else{
				$("#dateAdd").removeAttr('disabled');
				$("#invoice").removeAttr('disabled');
				$("#remark").removeAttr('disabled');
			}
		}
	});
	$("#btn-edit").addClass('hide');
	$("#btn-update").removeClass('hide');
}






$("#supplier").autocomplete({
	source: "controller/receiveProductController.php?search_supplier",
	autoFocus: true,
	close: function(){
		var rs = $(this).val();
		var arr = rs.split(' | ');
		if( arr.length == 2 ){
			$(this).val(arr[0]);
			$("#id_supplier").val(arr[1]);
		}else{
			$(this).val('');
			$("#id_supplier").val('');
		}
	}
});






$("#poCode").autocomplete({
	source: "controller/receiveProductController.php?search_po",
	autoFocus: true
});






$("#poCode").focusout(function(e) {
    if( $(this).val().length > 0 ){
		getData();
	}
});






$("#zoneName").autocomplete({
	source: "controller/receiveProductController.php?search_zone",
	autoFocus: true,
	close: function(){
		var rs = $(this).val();
		var arr = rs.split(' | ');
		if( arr.length == 2 ){
			$("#id_zone").val(arr[1]);
			$("#zoneName").val(arr[0]);
		}else{
			$("#id_zone").val('');
			$("#zoneName").val('');
		}
	}
});





$("#dateAdd").datepicker({ dateFormat: 'dd-mm-yy'});





$("#barcode").keyup(function(e) {
    if( e.keyCode == 13 ){
		if( $(this).val() != "" ){
			receiveProduct();
		}
	}
});






function sumReceive(){

	var qty = 0;
	$(".receive-box").each(function(index, element) {
        var cqty = isNaN( parseInt( $(this).val() ) ) ? 0 : parseInt( $(this).val() );
		qty += cqty;
    });
	$("#total-receive").text( addCommas(qty) );
}
