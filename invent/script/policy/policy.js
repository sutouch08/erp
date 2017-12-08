function goBack(){
  window.location.herf = 'index.php?content=discount_policy';
}


function goAdd(id){
  if(id != undefined){
    window.location.href = 'index.php?content=discount_policy&add=Y&id_policy='+id;
  }else{
    window.location.href = 'index.php?content=discount_policy&add=Y';
  }
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
