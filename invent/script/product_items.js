// JavaScript Document

function getItem(id){
	$.ajax({
		url:"controller/productController.php?getItem"	,
		type:"GET", cache:"false", data:{ "id" : id },
		success: function(rs){
			var rs = $.trim(rs);
			if( isJson(rs) ){
				var source 	= $("#itemTemplate").html();
				var data		= $.parseJSON(rs);
				var output	= $("#itemBody");
				render(source, data, output);
				$("#itemModal").modal('show');
			}else{
				swal("ผิดพลาด !", rs, "error");
			}
		}
	});
}


function saveItem(){
	$("#itemModal").modal('hide');
	$.ajax({
		url:"controller/productController.php?saveItem",
		type:"POST", cache:"false", data: $("#editForm").serializeArray(),
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'success' ){
				swal({title: 'Updated', text: 'บันทึกข้อมูลเรียบร้อยแล้ว', timer: 1000, type: 'success' });
			}else{
				swal("ผิดพลาด !!", rs, "error");
			}
		}
	});
}


function setShowInSale(id){
	$.ajax({
		url:"controller/productController.php?setShowInSale",
		type:"POST", cache:"false", data:{ "id" : id },
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'fail' ){
				swal("Error", "Update Fail", "error");
			}else{
				$("#showInSale-"+id).html(rs);	
			}
		}
	});
}


function setShowInCustomer(id){
	$.ajax({
		url:"controller/productController.php?setShowInCustomer",
		type:"POST", cache:"false", data:{ "id" : id },
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'fail' ){
				swal("Error", "Update Fail", "error");
			}else{
				$("#showInCustomer-"+id).html(rs);	
			}
		}
	});
}



function setShowInOnline(id){
	$.ajax({
		url:"controller/productController.php?setShowInOnline",
		type:"POST", cache:"false", data:{ "id" : id },
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'fail' ){
				swal("Error", "Update Fail", "error");
			}else{
				$("#showInOnline-"+id).html(rs);	
			}
		}
	});
}




function setCanSell(id){
	$.ajax({
		url:"controller/productController.php?setCanSell",
		type:"POST", cache:"false", data:{ "id" : id },
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'fail' ){
				swal("Error", "Update Fail", "error");
			}else{
				$("#canSell-"+id).html(rs);	
			}
		}
	});
}




function setActive(id){
	$.ajax({
		url:"controller/productController.php?setActive",
		type:"POST", cache:"false", data:{ "id" : id },
		success: function(rs){
			var rs = $.trim(rs);
			if( rs == 'fail' ){
				swal("Error", "Update Fail", "error");
			}else{
				$("#active-"+id).html(rs);	
			}
		}
	});
}