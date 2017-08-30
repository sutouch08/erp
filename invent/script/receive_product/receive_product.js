// JavaScript Document


function goAdd(){
	window.location.href = "index.php?content=receive_product&add";	
}

function goEdit(id){
	window.location.href = "index.php?content=receive_product&edit=Y&id_receive_product="+id;	
}


function goBack(){
	window.location.href = "index.php?content=receive_product";
}

function getSearch(){
	var code = $("#sCode").val();
	var PO	= $("#sPo").val();
	var Inv	= $("#sInv").val();
	var from	= $("#sFrom").val();
	var to		= $("#sTo").val();
	
	if( ( from.length > 0 && to.length > 0 ) || code.length > 0 || PO.length > 0 || Inv.length > 0 ){
		$("#searchForm").submit();	
	}
}


$(".search-box").keyup(function(e){
	if( e.keyCode == 13 ){
		getSearch();
	}
});

$("#sFrom").datepicker({
	dateFormat: "dd-mm-yy",
	onClose: function(sd){
		$("#sTo").datepicker("option", "minDate", sd);
	}
});

$("#sTo").datepicker({
	dateFormat: "dd-mm-yy", 
	onClose: function(sd){
		$("#sFrom").datepicker("option", "maxDate", sd);
	}
});

function clearFilter(){
	$.get("controller/receiveProductController.php?clearFilter", function(){ goBack(); });
}