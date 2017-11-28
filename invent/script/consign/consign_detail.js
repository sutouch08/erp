function deleteRow(id, code){
  swal({
		title: "คุณแน่ใจ ?",
		text: "ต้องการลบ '"+code+"' หรือไม่ ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#FA5858",
		confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
		}, function(){
      $('#row-'+id).remove();
      reOrder();
      updateTotalQty();
      updateTotalAmount();
      swal({
        title:'Deleted',
        type:'success',
        timer:1000
      });
	});
}



function updateStockZone(id_zone){
  $('.product').each(function(index, el) {
    var id = $(this).val();
    $.ajax({
      url:'controller/consignController.php?getStockInZone',
      type:'GET',
      cache:'false',
      data:{
        'id_zone' : id_zone,
        'id_product' : id
      },
      success:function(rs){
        var rs = $.trim(rs);
        if( ! isNaN(parseInt(rs)) ){
          $('#stock-'+id).val(rs);
          reCal(id);
        }else{
          $('#stock-'+id).val(0);
        }
      }
    })
  });
}
