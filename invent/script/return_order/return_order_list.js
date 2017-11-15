
$(document).ready(function() {

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

});


$('.search-box').keyup(function(e){
  if(e.keyCode == 13){
    getSearch();
  }
});


$('#sStatus').change(function(event) {
  getSearch();
});


function getSearch(){
  var from = $('#fromDate').val();
  var to = $('#toDate').val();
  if( (from.length == 0 && to.length == 0) || (from.length > 0 && to.length > 0) )
  {
    $('#searchForm').submit();
  }

}


function clearFilter(){
  $.get('controller/returnOrderController.php?clearFilter', function(){ goBack(); });
}


function syncDocument(){
  load_in();
  $.ajax({
    url:'controller/interfaceController.php?syncDocument&SM',
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if( rs == 'success')
      {
        swal({
          title: 'Success',
          type:'success',
          timer: 1000
        });

        setTimeout(function(){
          goBack();
        }, 1200);
      }else{
        swal('Error', rs, 'error');
      }
    }
  });
}
