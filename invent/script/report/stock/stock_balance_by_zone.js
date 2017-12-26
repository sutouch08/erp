function toggleProduct(option){
  if(option == 'all'){

    $('#allProduct').val(1);
    $('#btn-product-all').addClass('btn-primary');
    $('#btn-product-range').removeClass('btn-primary');
    $('.pd-box').attr('disabled', 'disabled');

  }else{

    $('#allProduct').val(0);
    $('#btn-product-all').removeClass('btn-primary');
    $('#btn-product-range').addClass('btn-primary');
    $('.pd-box').removeAttr('disabled');
    $('#txt-pdFrom').focus();
  }
}






function toggleZone(option){
  if(option == 'all'){

    $('#allZone').val(1);
    $('#btn-zone-all').addClass('btn-primary');
    $('#btn-zone-sp').removeClass('btn-primary');
    $('#txt-zone').attr('disabled', 'disabled');

  }else{

    $('#allZone').val(0);
    $('#btn-zone-all').removeClass('btn-primary');
    $('#btn-zone-sp').addClass('btn-primary');
    $('#txt-zone').removeAttr('disabled');
    $('#txt-zone').focus();
  }
}





$('#txt-pdFrom').autocomplete({
  source: 'controller/autoCompleteController.php?getStyleCode',
  autoFocus:true,
  close:function(){
    var pdFrom = $(this).val();
    if(pdFrom == 'ไม่พบข้อมูล'){
      $(this).val('');
    }else{
      var pdTo = $('#txt-pdTo').val();
      if(pdFrom > pdTo && pdTo != '' ){
        $('#txt-pdTo').val(pdFrom);
        $('#txt-pdFrom').val(pdTo);
      }else{
        $('#txt-pdFrom').val(pdFrom);
      }
    }
  }
});



$('#txt-pdTo').autocomplete({
  source:'controller/autoCompleteController.php?getStyleCode',
  autoFocus:true,
  close:function(){
    var pdTo = $(this).val();
    if(pdTo == 'ไม่พบข้อมูล'){
      $(this).val('');
    }else{
      var pdFrom = $('#txt-pdFrom').val();
      if(pdTo < pdFrom && pdFrom != ''){
        $('#txt-pdFrom').val(pdTo);
        $('#txt-pdTo').val(pdFrom);
      }else{
        $('#txt-pdTo').val(pdTo);
      }
    }
  }
});




$('#txt-warehouse').autocomplete({
  source:'controller/autoCompleteController.php?getWarehouse',
  autoFocus:true,
  close:function(){
    var rs = $(this).val();
    var arr = rs.split(' | ');
    if(arr.length == 3){
      var name = arr[1];
      var id = arr[2];
      $('#id_warehouse').val(id);
      $(this).val(name);
    }else{
      $('#id_warehouse').val('');
      $(this).val('');
    }
  }
});



$('#txt-zone').autocomplete({
  source:'controller/autoCompleteController.php?getZone',
  autoFocus:true,
  close:function(){
    var rs = $(this).val();
    var arr = rs.split(' | ');
    if(arr.length == 2){
      var name = arr[0];
      var id = arr[1];
      $('#id_zone').val(id);
      $(this).val(name);
    }else{
      $('#id_zone').val('');
      $(this).val('');
    }
  }
});




function getReport(){
  var allProduct = $('#allProduct').val();
  var allZone = $('#allZone').val();
  var pdFrom = $('#txt-pdFrom').val();
  var pdTo = $('#txt-pdTo').val();
  var id_warehouse = $('#id_warehouse').val();
  var id_zone = $('#id_zone').val();

  if(allProduct == 0 && (pdFrom == '' || pdTo == ''))
  {
    swal('กรุณาระบุสินค้าให้ครบถ้วน');

    if(pdFrom == ''){
      $('#txt-pdFrom').addClass('has-error');
    }

    if(pdTo == ''){
      $('#txt-pdTo').addClass('has-error');
    }
    return false;
  }else{
    $('#txt-pdFrom').removeClass('has-error');
    $('#txt-pdTo').removeClass('has-error');
  }



  if(allZone == 0 && id_zone == ''){
    swal('กรุณาระบุโซน');
    $('#txt-zone').addClass('has-error');
    return false;
  }else{
    $('#txt-zone').removeClass('has-error');
  }

  $.ajax({
    url:'controller/stockReportController.php?stock_balance_by_zone&report',
    type:'GET',
    cache:'false',
    data:{
      'allProduct' : allProduct,
      'allZone' : allZone,
      'pdFrom' : pdFrom,
      'pdTo' : pdTo,
      'id_warehouse' : id_warehouse,
      'id_zone' : id_zone
    },
    success:function(rs){
      var rs = $.trim(rs);
      if(isJson(rs)){
        var source = $('#template').html();
        var data = $.parseJSON(rs);
        var output = $('#rs');
        render(source,  data, output);
      }
    }
  });

}



function doExport(){
  var allProduct = $('#allProduct').val();
  var allZone = $('#allZone').val();
  var pdFrom = $('#txt-pdFrom').val();
  var pdTo = $('#txt-pdTo').val();
  var id_warehouse = $('#id_warehouse').val();
  var id_zone = $('#id_zone').val();

  if(allProduct == 0 && (pdFrom == '' || pdTo == ''))
  {
    swal('กรุณาระบุสินค้าให้ครบถ้วน');

    if(pdFrom == ''){
      $('#txt-pdFrom').addClass('has-error');
    }

    if(pdTo == ''){
      $('#txt-pdTo').addClass('has-error');
    }
    return false;
  }else{
    $('#txt-pdFrom').removeClass('has-error');
    $('#txt-pdTo').removeClass('has-error');
  }



  if(allZone == 0 && id_zone == ''){
    swal('กรุณาระบุโซน');
    $('#txt-zone').addClass('has-error');
    return false;
  }else{
    $('#txt-zone').removeClass('has-error');
  }

  var token = new Date().getTime();
  var target = 'controller/stockReportController.php?stock_balance_by_zone&export';
  target += '&allProduct='+allProduct;
  target += '&allZone='+allZone;
  target += '&pdFrom='+pdFrom;
  target += '&pdTo='+pdTo;
  target += '&id_warehouse='+id_warehouse;
  target += '&id_zone='+id_zone;
  target += '&token='+token;
  get_download(token);
  window.location.href = target;

}
