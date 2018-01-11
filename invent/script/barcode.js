// JavaScript Document
function syncBarcode(){
	load_in();
	$.ajax({
		url:"controller/interfaceController.php?syncMaster&barcode"	,
		type:"GET", cache:"false",
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == "success" ){
				swal({ title: 'Success', text: 'Sync Completed', timer: 1000, type: 'success' });
				setTimeout(function(){ goBack(); }, 1500);
			}else{
				swal({ title: "ข้อผิดพลาด !!", text: rs, type: "warning" });
			}
		}
	});
}

function deleteBarcode(barcode, product){
	swal({
		title: 'คุณแน่ใจ ? ',
		text: 'ต้องการลบ '+product+' ใช่หรือไม่? โปรดทราบว่า เมื่อลบสำเร็จแล้วไม่สามารถย้อนคืนได้',
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#DD6855',
		confirmButtonText: 'ใช่ ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
	}, function(){
		$.ajax({
			url:"controller/barcodeController.php?deleteBarcode",
			type:"POST",
			cache:"false",
			data:{
				"barcode" : barcode
			},
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'success' ){
					$("#row_"+barcode).remove();
					swal({ title: 'สำเร็จ', text: 'ลบรายการเรียบร้อยแล้ว', type: 'success', timer: 1000 });
				}else{
					swal({title: 'ข้อผิดพลาด', text: 'ลบรายการไม่สำเร็จ', type: 'error' });
				}
			}
		});
	});
}


function goBack(){
	window.location.href = "index.php?content=barcode";
}

function getSearch(){
	var sProduct 	= $("#sProduct").val();
	var sBarcode 	= $("#sBarcode").val();
	var sUnit			= 	$("#sUnit").val();
	if( sProduct != '' || sBarcode != '' || sUnit != '' ) {
		$("#searchForm").submit();
	}
}

function clearFilter(){
	$.ajax({
		url:"controller/barcodeController.php?clearFilter",
		type:"GET", cache:"false",
		success: function(rs){
			goBack();
		}
	});
}

$("#sProduct").keyup(function(e){
	if( e.keyCode == 13 ){
		getSearch();
	}
});

$("#sBarcode").keyup(function(e){
	if( e.keyCode == 13 ) {
		getSearch();
	}
});

$("#sUnit").keyup(function(e){
	if( e.keyCode == 13 ){
		getSearch();
	}
});
