function getSearch(){
  $('#searchForm').submit();
}


function clearFilter(){
  $.ajax({
    url:'controller/policyController.php?clearFilter',
    type:'GET',
    cache:'false',
    success: function(){
      goBack();
    }
  });

}
