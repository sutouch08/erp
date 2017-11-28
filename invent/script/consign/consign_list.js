function goDelete(id, code){
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
      $.ajax({
        url:'controller/consignController.php?deleteConsign',
        type:'POST',
        cache:'false',
        data:{
          'id_consign' : id
        },
        success:function(rs){
          var rs = $.trim(rs);
          if(rs == 'success'){
            $('#xLabel'+id).html('<span class="red">CN</span>');
            $('#btn-edit-'+id).remove();
            $('#btn-delete-'+id).remove();
            swal({
              title:'Deleted',
              type:'success',
              timer:1000
            });
          }else{
            swal('Error', rs, 'error');
          }
        }
      });

	});
}


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


$('.search-box').keyup(function(e) {
  if(e.keyCode == 13){
    getSearch();
  }
});



function getSearch(){
  $('#searchForm').submit();
}


function clearFilter(){
  $.get('controller/consignController.php?clearFilter', function(){ goBack(); });
}
