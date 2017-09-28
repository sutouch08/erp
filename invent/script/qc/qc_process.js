$("#chk-force-close").change(function(){
  if( $("#chk-force-close").prop('checked') == true){
    $("#btn-force-close").removeClass('hide');
  }else{
    $("#btn-force-close").addClass('hide');
  }
});
