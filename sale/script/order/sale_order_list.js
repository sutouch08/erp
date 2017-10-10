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
