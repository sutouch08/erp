// JavaScript Document
$('#fromDate').datepicker({
  dateFormat:'dd-mm-yy',
  onClose:function(sd){
    $('#toDate').datepicker('option', 'minDate', sd);
  }
});

$('#toDate').datepicker({
  dateFormat:'dd-mm-yy',
  onClose:function(sd){
    $('#fromDate').datepicker('option', 'maxDate', sd);
  }
});


$('.search').keyup(function(e){
  if(e.keyCode == 13){
    getSearch();
  }
})


function getSearch(){
  $('#searchForm').submit();
}


function clearFilter(){
	$.get("../invent/controller/orderController.php?clearFilter", function(){ goBack(); });
}


$('#pd-search-box').autocomplete({
	source:'../invent/controller/autoCompleteController.php?getStyleCode',
	autoFocus:true,
	close:function(){
		var code = $(this).val();
		if(code == 'ไม่พบข้อมูล'){
			$(this).val('');
			$('#id_style').val('');
		}else{
			$.ajax({
				url:'../invent/controller/styleController.php?getStyleId',
				type:'GET',
				cache:'false',
				data:{
					'style_code' : code
				},
				success:function(rs){
					var rs = $.trim(rs);
					if(rs.length != 0){
						$('#id_style').val(rs);
					}else{
						$('#id_style').val('');
						swal('รหัสสินค้าไม่ถูกต้อง');
					}
				}
			});
		}
	}
});



function getStockGrid(){
	var pdCode = $('#pd-search-box').val();
	var id_style = $('#id_style').val();
	if(pdCode.length > 0 && id_style != ''){
		load_in();
		$.ajax({
			url:'../invent/controller/orderController.php?getSaleStockGrid',
			type:'GET',
			cache:'false',
			data:{
				'id_style' : id_style
			},
			success:function(rs){
				load_out();
				var rs = rs.split(' | ');
				if( rs.length == 4 ){
					var grid = rs[0];
					var width = rs[1];
					var pdCode = rs[2];
					var id_style = rs[3];
					$("#modal").css("width", width +"px");
					$("#modalTitle").html(pdCode);
					$("#modalBody").html(grid);
					$("#orderGrid").modal('show');
				}else{
					swal("สินค้าไม่ถูกต้อง");
				}
			}
		})
	}
}


$('#pd-search-box').keyup(function(event) {
	if(event.keyCode == 13){
		var id_style = $('#id_style').val();
		var code = $('#pd-search-box').val();
		if(code.length > 0 && id_style != ''){
			setTimeout(function(){
				getStockGrid();
			},500);
		}
	}
});
