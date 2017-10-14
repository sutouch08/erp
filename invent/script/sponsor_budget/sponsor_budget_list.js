function toggleActive(no){
  $('#sActive').val(no);
  getSearch();
}


$('.search-box').keyup(function(e){
  if(e.keyCode == 13){
    getSearch();
  }
});




function getSearch(){
  $('#searchForm').submit();
}


function clearFilter(){
  $.get('controller/sponsorController.php?clearFilter', function(){ goBack(); });
}
