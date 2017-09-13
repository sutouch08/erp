// JavaScript Document
function addToOrder(){
	var count = countInput();
	if(count > 0 ){
		$("#orderGrid").modal('hide');
		$.ajax({
			url:"controller/orderController.php?addToOrder",
			type:"POST", cache:"false", data: $("#orderForm").serializeArray(),
			success: function(rs){
				load_out();
				console.log(rs);
			}
		});
	}
}

function countInput(){
	var qty = 0;
	$(".order-grid").each(function(index, element) {
        if( $(this).val() != '' ){
			qty++;	
		}
    });	
	return qty;
}

function addNew(){
	var dateAdd 		= $("#dateAdd").val();
	var id_customer 	= $("#id_customer").val();	
	var customer 		= $("#customer").val();
	var channels 		= $("#channels").val();
	var payment 		= $("#paymentMethod").val();
	var remark			= $("#remark").val();
	
	if( ! isDate(dateAdd) ){
		swal("วันที่ไม่ถูกต้อง");
		return false;
	}
	
	if( id_customer == "" || customer == "" ){
		swal("ชื่อลูกค้าไม่ถูกต้อง");
		return false;
	}
	
	$.ajax({
		url:"controller/orderController.php?addNew",
		type:"POST", cache:"false", data:{ "dateAdd" : dateAdd, "id_customer" : id_customer, "channels" : channels, "paymentMethod" : payment, "remark" : remark },
		success: function(rs){
			var rs = $.trim(rs);
			if( ! isNaN( parseInt(rs) ) ){
				goAddDetail(rs);
			}else{
				swal("ข้อผิดพลาด", rs, "error");	
			}
		}
	});
	
}


$("#dateAdd").datepicker({
	dateFormat: 'dd-mm-yy'
});

$("#customer").autocomplete({
	source: "controller/orderController.php?getCustomer",
	autoFocus: true,
	close: function(){
		var rs = $.trim($(this).val());
		var arr = rs.split(' | ');
		if( arr.length == 3 ){
			var code = arr[0];
			var name = arr[1];
			var id = arr[2];
			$("#id_customer").val(id);
			$("#customer").val(name);
		}else{
			$("#id_customer").val('');
			$(this).val('');	
		}
	}
});

$("#pd-box").autocomplete({
	source: "controller/orderController.php?searchProducts",
	autoFocus: true
});

function goAddDetail(id){
	window.location.href = "index.php?content=order&add=Y&id_order="+id;
}