function goBack(){
  window.location.href = 'index.php?content=stock';
}





function getSearch(){
  $('#searchForm').submit();
}





function clearFilter(){
  $.get('controller/stockController.php?clearFilter', function(){ goBack();});
}





$('.search-box').keyup(function(e){
  if(e.keyCode == 13){
    getSearch();
  }
});


function editStock(id_stock){
  $('#qty-'+id_stock).removeAttr('disabled');
  $('#btn-edit-'+id_stock).addClass('hide');
  $('#btn-update-'+id_stock).removeClass('hide');
  $('#qty-'+id_stock).focus();
}


function updateStock(id_stock){
  var qty = $('#qty-'+id_stock).val();
  if(!isNaN(parseInt(qty))){
    $.ajax({
      url:'controller/stockController.php?updateStock',
      type:'POST',
      cache:'false',
      data:{
        'id_stock' : id_stock,
        'qty' : qty
      },
      success:function(rs){
        var rs = $.trim(rs);
        if(rs == 'success'){
          $('#qty-'+id_stock).attr('disabled', 'disabled');
          $('#btn-update-'+id_stock).addClass('hide');
          $('#btn-edit-'+id_stock).removeClass('hide');
        }else{
          swal('Error', rs, 'error');
        }
      }
    });
  }
}


function deleteStock(id_stock){
  swal({
		title: "คุณแน่ใจ ?",
		text: "ต้องการลบ หรือไม่ ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#FA5858",
		confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
		}, function(){
      $.ajax({
        url:'controller/stockController.php?deleteStock',
        type:'POST',
        cache:'false',
        data:{
          'id_stock' : id_stock
        },
        success:function(rs){
          var rs = $.trim(rs);
          if(rs == 'success'){
            swal({
              title:'Deleted',
              type:'success',
              timer:1000
            });

            $('#row-'+id_stock).remove();

          }else{
            swal('Error', rs, 'error');
          }
        }
      });

	});
}
