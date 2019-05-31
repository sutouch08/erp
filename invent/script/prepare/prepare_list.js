

function getSearch(){
  $("#searchForm").submit();
}






function clearFilter(){
  $.get("controller/prepareController.php?clearFilter", function(){ goBack(); });
}





$(".search-box").keyup(function(e){
  if( e.keyCode == 13){
    getSearch();
  }
});



$('.search-select').change(function(event) {
  getSearch();
});




$("#fromDate").datepicker({
  dateFormat: 'dd-mm-yy',
  onClose: function(sd){
    $("#toDate").datepicker("option", "minDate", sd);
  }
});


$("#toDate").datepicker({
  dateFormat: 'dd-mm-yy',
  onClose: function(sd){
    $("#fromDate").datepicker("option", "maxDate", sd);
  }
});



function toggleOnline(){
  var i = $('#sOnline').val();
  if(i == 1){
    $('#sOnline').val(0);
    $('#btn-online').removeClass('btn-info');
  }else{
    $('#sOnline').val(1);
    $('#btn-online').addClass('btn-info');
  }

  getSearch();

}


function toggleOffline(){
  var i = $('#sOffline').val();
  if(i == 1){
    $('#sOffline').val(0);
    $('#btn-offline').removeClass('btn-info');
  }else{
    $('#sOffline').val(1);
    $('#btn-offline').addClass('btn-info');
  }

  getSearch();

}


//---- Reload page every 5 minute
$(document).ready(function(){
  setInterval(function(){ goBack();}, 300000);
});
