// JavaScript Document
function getEdit(id){
	window.location.href = "index.php?content=customer&edit&id="+id;	
}

$(".search-box").keyup(function(e){
	if( e.keyCode == 13 ){
		getSearch();
	}
});

function getSearch(){
	var name = $.trim( $("#cName").val() );
	var code = $.trim( $("#cCode").val() );
	var prov	 = $.trim($("#cProvince").val() );
	if( name != "" || code != "" || prov != "" ){
		$("#searchForm").submit();
	}		
}

function syncCustomer(){
	load_in();
	$.ajax({
		url:"controller/interfaceController.php?syncMaster&customer",
		type:"GET", cache:"false",
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'success' ){
				swal({ title : "Success", text: "Sync Completed", type: "success", timer: 1000 });
				setTimeout(function(){ goBack(); }, 1200);
			}else{
				swal("ข้อผิดพลาด !", rs , "error");
			}
		}
	});
}

function goBack(){
	window.location.href = "index.php?content=customer";
}

function clearFilter(){
	$.ajax({
		url: "controller/customerController.php?clearFilter",
		type:"GET", cache:"false",
		success: function(rs){
			goBack();
		}
	});
}