// JavaScript Document
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



function saveAdd(){
	var date	= $("#dateAdd").val();
	var po	= $("#poCode").val();
	var inv	= $("#invoice").val();
	var remark = $("#remark").val();
	if( ! isDate(date) ){
		swal("วันที่ไม่ถูกต้อง");
		return false;
	}
	if( inv.length < 4 ){
		swal("ใบส่งสินค้าไม่ถูกต้อง");
		return false;
	}
	if( po.length < 4 ){
		swal("ใบสั่งซื้อไม่ถูกต้อง");
		return false;
	}
	
	$.ajax({
		url:"controller/receiveProductController.php?addNew",
		type:"POST", cache:"false", data:{ "date_add" : date, "po" : po, "invoice" : inv, "remark" : remark },
		success: function(rs){
			var rs = $.trim(rs);
			var arr = rs.split(' | ');
			if( arr[0] == 'success' ){
				window.location.href = "index.php?content=receive_product&edit=Y&id_receive_product="+arr[1];
			}else{
				swal("ข้อผิดพลาด !", arr[1], "error");
			}
		}
	});
}

$("#poCode").autocomplete({
	source: "controller/receiveProductController.php?search_po",
	autoFocus: true
});

$("#dateAdd").datepicker({ dateFormat: 'dd-mm-yy'});