//---store in  WEB_ROOT/invent/script/adjust

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



function getSearch(){
  var from = $('#fromDate').val();
  var to = $('#toDate').val();

  if( (from.length > 0 && to.length > 0 ) || (from.length == 0 && to.length == 0) ){
    $('#searchForm').submit();
  }
}


function clearFilter(){
  $.get('controller/adjustController.php?clearFilter', function(){ goBack(); });
}
