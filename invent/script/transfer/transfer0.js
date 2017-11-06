// JavaScript Document



function getMoveIn(){
	$(".moveIn-zone").removeClass('hide');
	$(".moveOut-zone").addClass('hide');
	$(".control-btn").addClass('hide');
	hideTransferTable();
	getTempTable();
	showTempTable();
	$("#toZone-barcode").focus();
}






$("#barcode-item-to").keyup(function(e) {
    if( e.keyCode == 13 ){
		var barcode = $(this).val();
		var id_zone_to	= $("#id_zone_to").val();
		var id_transfer = $("#id_transfer").val();
		var id_transfer_detail = $("#row_"+barcode).val();
		if( id_zone_to.length == 0 ){
			swal("กรุณาระบุโซนปลายทาง");
			return false;
		}

		var qty = parseInt($("#qty-to").val());

		var curQty	= parseInt($("#qty-"+barcode).val());

		$(this).val('');

		if( isNaN(curQty) ){
			swal("สินค้าไม่ถูกต้อง");
			return false;
		}

		console.log(qty);
		console.log(curQty);


		if( qty != '' && qty != 0 ){
			if( qty <= curQty ){
				$.ajax({
					url:"controller/transferController.php?moveBarcodeToZone",
					type:"POST", cache:"false", data:{"id_transfer_detail" : id_transfer_detail, "id_transfer" : id_transfer, "id_zone_to" : id_zone_to, "qty" : qty, "barcode" : barcode },
					success: function(rs){
						var rs = $.trim(rs);
						if( rs == 'success'){
							curQty = curQty - qty;
							if(curQty == 0 ){
								$("#row-temp-"+id_transfer_detail).remove();
							}else{
								$("#qty-label-"+barcode).text(curQty);
								$("#qty-"+barcode).val(curQty);
							}
							$("#qty-to").val(1);
							$("#barcode-item-to").focus();
						}else{
							swal("ข้อผิดพลาด", rs, "error");
						}
					}
				});
			}else{
				swal("จำนวนในโซนไม่เพียงพอ");
			}
		}
	}
});


function deleteTransfer(id_transfer, reference){
	swal({
		title: 'คุณแน่ใจ ?',
		text: 'ต้องการลบ '+ reference +' หรือไม่ ?',
		type: 'warning',
		showCancelButton: true,
		comfirmButtonColor: '#DD6855',
		confirmButtonText: 'ใช่ ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
	}, function(){
		$.ajax({
			url:"controller/transferController.php?deletetransfer",
			type:"POST", cache:"false", data:{ "id_transfer" : id_transfer },
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'success' ){
					swal({ title:'Deleted', text: 'ลบรายการเรียบร้อยแล้ว', type: 'success', timer: 1000 });
					$("#row_"+id_transfer).remove();
				}else{
					swal("ข้อผิดพลาด", rs, "error");
				}
			}
		});
	});
}



function deleteMoveItem(id_transfer_detail, product_reference){
	swal({
		title: 'คุณแน่ใจ ?',
		text: 'ต้องการลบ '+ product_reference +' หรือไม่ ?',
		type: 'warning',
		showCancelButton: true,
		comfirmButtonColor: '#DD6855',
		confirmButtonText: 'ใช่ ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
	}, function(){
		$.ajax({
			url:"controller/transferController.php?deletetransferDetail",
			type:"POST", cache:"false", data:{ "id_transfer_detail" : id_transfer_detail },
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'success' ){
					swal({ title:'Deleted', text: 'ลบรายการเรียบร้อยแล้ว', type: 'success', timer: 1000 });
					$("#row-"+id_transfer_detail).remove();
				}else{
					swal("ข้อผิดพลาด", rs, "error");
				}
			}
		});
	});
}








































function newToZone(){
	$("#id_zone_to").val("");
	$("#toZone-barcode").val("");
	$("#zone-table").addClass('hide');
	$("#toZone-barcode").focus();
}



function getZoneTo(){
	var txt = $("#toZone-barcode").val();
	if( txt != ""){
		var id_wh = $("#id_wh_to").val();
		$.ajax({
			url:"controller/transferController.php?getZone",
			type:"GET", cache:"false", data:{ "txt" : txt, "id_warehouse" : id_wh},
			success: function(rs){
				var rs = $.trim(rs);
				if( isJson(rs) ){
					var ds = $.parseJSON(rs);
					$("#id_zone_to").val(ds.id_zone);
					$("#zoneName-label").text(ds.zone_name);
					$("#toZone-barcode").val("");

				}else{
					swal("ข้อผิดพลาด", rs, "error");
					$("#id_zone_to").val("");
					$("#zone-table").addClass('hide');
					beep();
				}
			}
		});
	}
}




$("#toZone-barcode").keyup(function(e) {
    if( e.keyCode == 13 ){
		getZoneTo();
		setTimeout(function(){ $("#barcode-item-to").focus(); }, 500);
	}
});






function addNew(){
	var dateAdd = $("#dateAdd").val();
	var fromWH	= $("#fromWH").val();
	var toWH		= $("#toWH").val();
	var remark	= $("#remark").val();

	if( ! isDate( dateAdd ) ){
		swal('วันที่ไม่ถูกต้อง');
		return false;
	}

	if( fromWH == 0 || toWH == 0 ){
		swal('คลังสินค้าไม่ถูกต้อง');
		return false;
	}

	load_in();
	$.ajax({
		url:"controller/transferController.php?addNew",
		type:"POST", cache:"false", data:{ "date_add" : dateAdd, "fromWH" : fromWH, "toWH" : toWH, "remark" : remark },
		success: function(rs){
			load_out();
			if( isJson(rs) ){
				var ds = $.parseJSON(rs);
				var id = ds.id;
				window.location.href = "index.php?content=transfer&add&id_transfer="+id;
			}else{
				swal('ข้อผิดพลาด !', rs, 'error');
			}
		}
	});
}



function updateHeader(id){
	var dateAdd = $("#dateAdd").val();
	var fromWH	= $("#fromWH").val();
	var toWH		= $("#toWH").val();
	var remark	= $("#remark").val();

	if( ! isDate( dateAdd ) ){
		swal('วันที่ไม่ถูกต้อง');
		return false;
	}

	if( fromWH == 0 || toWH == 0 ){
		swal('คลังสินค้าไม่ถูกต้อง');
		return false;
	}

	load_in();
	$.ajax({
		url:"controller/transferController.php?updateHeader",
		type:"POST", cache:"false", data:{ "id_transfer" : id, "date_add" : dateAdd, "fromWH" : fromWH, "toWH" : toWH, "remark" : remark },
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'success' ){
				swal({ title: 'success', text: 'บันทึกข้อมูลเรียบร้อยแล้ว', type: 'success', timer: 1000 });
				$("#dateAdd").attr('disabled', 'disabled');
				$('#fromWH').attr('disabled', 'disabled');
				$('#toWH').attr('disabled', 'disabled');
				$('#remark').attr('disabled', 'disabled');
				$('#btn-update').addClass('hide');
				$('#btn-edit').removeClass('hide');
			}else{
				swal('ข้อผิดพลาด !', rs, 'error');
			}
		}
	});
}




function editHeader(){
	$(".header-box").removeAttr('disabled');
	$("#btn-edit").addClass('hide');
	$("#btn-update").removeClass('hide');
}



function toggleActive(){
	var status = $("#sStatus").val();
	if( status == 1 ){
		$("#sStatus").val(0);
		$("#btn-inComplete").removeClass('btn-info');
	}else{
		$("#sStatus").val(1);
		$("#btn-inComplete").addClass('btn-info');
	}
	$("#searchForm").submit();
}





function goBack(){
	window.location.href = "index.php?content=transfer";
}




function goEdit(id){
	window.location.href = "index.php?content=transfer&edit&id_transfer="+id;
}



function goDetail(id){
	window.location.href = "index.php?content=transfer&view_detail&id_transfer="+id;
}


function getNew(){
	window.location.href = "index.php?content=transfer&add";
}

function goUseBarcode(id){
	window.location.href = "index.php?content=transfer&edit&id_transfer="+id+"&barcode";
}



function printTransfer(){
	var id = $("#id_transfer").val();
	var center = ($(document).width() - 800) /2;
	window.open("controller/transferController.php?printtransfer&id_transfer="+id, "_blank", "width=800, height=900, left="+center+", scrollbars=yes");
}



function getSearch(){
	var sCode 	= $("#sCode").val();
	var sEmp	 	= $("#sEmp").val();
	var from		= $("#fromDate").val();
	var to			= $("#toDate").val();
	if( sCode.length > 0 || sEmp.length > 0 || ( isDate(from) && isDate(to) ) ) {
		$("#searchForm").submit();
	}
}






$(".search-box").keyup(function(e) {
    if( e.keyCode == 13 ){
		getSearch();
	}
});




function clearFilter(){
	$.get("controller/transferController.php?clearFilter", function(){ goBack(); });
}


$(document).ready(function(e) {
	if( $("#fromDate").length > 0 ){
		var from = $("#fromDate").val();
		var to	 = $("#toDate").val();
		if( isDate(from) && isDate(to) ){
			$("#toDate").datepicker('option', 'minDate', from);
			$("#fromDate").datepicker('option', 'maxDate', to);
		}
	}
});


$("#dateAdd").datepicker({
	dateFormat: 'dd-mm-yy'
});


$("#fromDate").datepicker({
	dateFormat: 'dd-mm-yy',
	onClose: function(se){
		$("#toDate").datepicker("option", "minDate", se);
	}
});




$("#toDate").datepicker({
	dateFormat : 'dd-mm-yy',
	onClose: function(se){
		$("#fromDate").datepicker("option", "maxDate", se);
	}
});
