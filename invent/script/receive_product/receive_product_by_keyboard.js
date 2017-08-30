// JavaScript Document
function changeZone(){
	$("#id_zone").val('');
	$("#styleCode").val('');
	$("#zoneBarcode").val('');
	$("#zoneName").val('');
	$("#styleCode").attr('disabled', 'disabled');
	$("#btn-get-product").attr('disabled', 'disabled');
	$("#zoneBarcode").removeAttr('disabled');
	$("#zoneName").removeAttr('disabled');
	$("#zoneName").focus();
}


$("#zoneName").autocomplete({
	source: "controller/receiveProductController.php?search_zone",
	autoFocus: true,
	close: function(){
		var rs = $(this).val();
		var arr = rs.split(' | ');
		if( arr.length == 2 ){
			$("#id_zone").val(arr[1]);
			$("#zoneName").val(arr[0]);		
			$("#zoneBarcode").attr('disabled', 'disabled');
			$("#zoneName").attr('disabled', 'disabled');
			$("#styleCode").removeAttr('disabled');
			$("#btn-get-product").removeAttr('disabled');
			$("#styleCode").focus();		
		}else{
			$("#id_zone").val('');
			$("#zoneName").val('');
		}
	}
});

