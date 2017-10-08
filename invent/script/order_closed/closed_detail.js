function doExport(){
	var id_order = $("#id_order").val();
	load_in();
	$.ajax({
		url:"controller/interfaceController.php?export&SO",
		type:"POST",
    cache:"false",
    data:{ "id_order" : id_order },
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'success' ){
				swal({ title: "Success", type:"success", timer: 1000 });
			}else{
				swal("ข้อผิดพลาด !", rs, "error");
			}
		}
	});
}
