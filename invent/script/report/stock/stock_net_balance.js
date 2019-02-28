//----- กำหนดการแสดงผล เป็นรุ่นสินค้า หรือ รายการสินค้า
function toggleResult(option){
  if(option == '1'){

    $('#btn-item').addClass('btn-primary');
    $('#btn-style').removeClass('btn-primary');
    $('.style').addClass('hide');
    $('.item').removeClass('hide');
    $('#txt-pd-from').focus();

  }else if(option == '0'){

    $('#btn-item').removeClass('btn-primary');
    $('#btn-style').addClass('btn-primary');
    $('.item').addClass('hide');
    $('.style').removeClass('hide');
    $('#txt-style-from').focus();

  }

  $('#showItem').val(option);
}



//------  กำหนดสินค้า
function toggleProduct(option){
  var showItem = $('#showItem').val();
  if(option == '1'){

    $('#btn-pd-all').addClass('btn-primary');
    $('#btn-pd-range').removeClass('btn-primary');
    $('.pd-box').attr('disabled', 'disabled');

  }else if(option == '0'){

    $('#btn-pd-all').removeClass('btn-primary');
    $('#btn-pd-range').addClass('btn-primary');
    $('.pd-box').removeAttr('disabled');

    if(showItem == 0){
      $('#txt-style-from').focus();
    }else{
      $('#txt-pd-from').focus();
    }

  }

  $('#allProduct').val(option);
}


function toggleWarehouse(option){
  $('#allWhouse').val(option);
  if(option == 1){
    //----  All warehouse
    $('#wh-modal').modal('hide');
    $('#btn-whAll').addClass('btn-primary');
    $('#btn-whList').removeClass('btn-primary');

  }else if(option == 0){
    //--- some warehouse
    $('#btn-whAll').removeClass('btn-primary');
    $('#btn-whList').addClass('btn-primary');
    $('#wh-modal').modal('show');
  }

}


$('#txt-po-start').keyup(function(event) {
  if(event.keyCode == 13){
    var po = $(this).val();
    if(po.length > 0 ){
      $('#txt-po-end').focus();
    }
  }
});


$('#txt-po-end').keyup(function(event) {
  if(event.keyCode == 13){
    var po = $(this).val();
    if(po.length > 0 ){
      $('#txt-po-start').focus();
    }
  }
});



$('#txt-po-start').focusout(function(event) {
  var from = $(this).val();
  var to   = $('#txt-po-end').val();

  if(to.length > 0 && from.length > 0 && from > to){
    $(this).val(to);
    $('#txt-po-end').val(from);
  }

});


$('#txt-po-end').focusout(function(event) {
  var from = $('#txt-po-start').val();
  var to   = $(this).val();

  if(from.length > 0 && to.length > 0 && from > to){
    $(this).val(from);
    $('#txt-po-start').val(to);
  }
});


$('#txt-pd-from').autocomplete({
  source:'controller/autoCompleteController.php?getItemCode',
  autoFocus:true,
  close:function(){
    var rs = $.trim($(this).val());
    if(rs == '' && rs == 'ไม่พบข้อมูล'){
      $(this).val('');
    }else{
      var pdFrom = $(this).val();
      var pdTo   = $('#txt-pd-to').val();
      if(pdTo.length > 0 && pdFrom > pdTo){
        $(this).val(pdTo);
        $('#txt-pd-to').val(pdFrom);
      }

      $('#txt-pd-to').focus();
    }
  }
});


$('#txt-pd-to').autocomplete({
  source:'controller/autoCompleteController.php?getItemCode',
  autoFocus:true,
  close:function(){
    var rs = $.trim($(this).val());
    if(rs == '' && rs == 'ไม่พบข้อมูล'){
      $(this).val('');
    }else{
      var pdFrom = $('#txt-pd-from').val();
      var pdTo   = $(this).val();
      if(pdFrom.length > 0 && pdFrom > pdTo){
        $(this).val(pdFrom);
        $('#txt-pd-from').val(pdTo);
      }

      if(pdFrom.length == 0){
        $('#txt-pd-from').focus();
      }
    }
  }
});




$('#txt-style-from').autocomplete({
  source:'controller/autoCompleteController.php?getStyleCode',
  autoFocus:true,
  close:function(){
    var rs = $.trim($(this).val());
    if(rs == '' && rs == 'ไม่พบข้อมูล'){
      $(this).val('');
    }else{
      var pdFrom = $(this).val();
      var pdTo   = $('#txt-style-to').val();
      if(pdTo.length > 0 && pdFrom > pdTo){
        $(this).val(pdTo);
        $('#txt-style-to').val(pdFrom);
      }

      $('#txt-style-to').focus();
    }
  }
});




$('#txt-style-to').autocomplete({
  source:'controller/autoCompleteController.php?getStyleCode',
  autoFocus:true,
  close:function(){
    var rs = $.trim($(this).val());
    if(rs == '' && rs == 'ไม่พบข้อมูล'){
      $(this).val('');
    }else{
      var pdFrom = $('#txt-style-from').val();
      var pdTo   = $(this).val();
      if(pdFrom.length > 0 && pdFrom > pdTo){
        $(this).val(pdFrom);
        $('#txt-style-from').val(pdTo);
      }

      if(pdFrom.length == 0){
        $('#txt-style-from').focus();
      }
    }
  }
});



function getReport(){

  var allWhouse   = $('#allWhouse').val();
  var showItem    = $('#showItem').val();  //--- 1 = แสดงเป็นรายการ  0 = แสดงเป็นรุ่นสินค้า
  var allProduct  = $('#allProduct').val();
  var pdFrom      = $('#txt-pd-from').val();
  var pdTo        = $('#txt-pd-to').val();
  var styleFrom   = $('#txt-style-from').val();
  var styleTo     = $('#txt-style-to').val();

  if(allProduct == 0 )
  {
    if(showItem == 1 && (pdFrom == '' || pdTo == '')){
      swal('กรุณาระบุสินค้าให้ครบถ้วน');
      return false;
    }

    if(showItem == 0 && (styleFrom == '' || styleTo == '')){
      swal('กรุณาระบุสินค้าให้ครบถ้วน');
      return false;
    }
  }

  if(allWhouse == 0 && $('.chk:checked').length == 0 ){
    swal('กรุณาระบุคลังสินค้า');
    return false;
  }

  var data = [
    {'name' : 'allProduct', 'value' : allProduct},
    {'name' : 'allWhouse' , 'value' : allWhouse},
    {'name' : 'pdFrom', 'value' : pdFrom},
    {'name' : 'pdTo', 'value' : pdTo},
    {'name' : 'styleFrom', 'value' : styleFrom},
    {'name' : 'styleTo', 'value' : styleTo},
    {'name' : 'showItem', 'value' : showItem}
  ];

  $('.chk').each(function(index, el) {
    if($(this).is(':checked')){
      let names = 'warehouse['+$(this).val()+']';
      data.push({'name' : names, 'value' : $(this).val() });
    }
  });

  load_in();

  $.ajax({
    url:'controller/stockReportController.php?stock_net_balance&report',
    type:'GET',
    cache:'false',
    data:data,
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if(isJson(rs)){
        var source = $('#template').html();
        var data = $.parseJSON(rs);
        var output = $('#rs');
        render(source,  data, output);
      }else{
        swal({
          title:'Error',
          text: rs,
          type:'error'
        });
      }
    }
  });


}



function doExport(){
  var allWhouse  = $('#allWhouse').val();
  //---- Product
  var showItem = $('#showItem').val();  //--- 1 = แสดงเป็นรายการ  0 = แสดงเป็นรุ่นสินค้า
  var allProduct = $('#allProduct').val();
  var pdFrom = $('#txt-pd-from').val();
  var pdTo  = $('#txt-pd-to').val();
  var styleFrom = $('#txt-style-from').val();
  var styleTo = $('#txt-style-to').val();


  if(allProduct == 0 )
  {
    if(showItem == 1 && (pdFrom == '' || pdTo == '')){
      swal('กรุณาระบุสินค้าให้ครบถ้วน');
      return false;
    }

    if(showItem == 0 && (styleFrom == '' || styleTo == '')){
      swal('กรุณาระบุสินค้าให้ครบถ้วน');
      return false;
    }
  }


  if(allWhouse == 0 && $('.chk:checked').length == 0 ){
    swal('กรุณาระบุคลังสินค้า');
    return false;
  }


  var data = [
    {'name' : 'allProduct', 'value' : allProduct},
    {'name' : 'allWhouse' , 'value' : allWhouse},
    {'name' : 'pdFrom', 'value' : pdFrom},
    {'name' : 'pdTo', 'value' : pdTo},
    {'name' : 'styleFrom', 'value' : styleFrom},
    {'name' : 'styleTo', 'value' : styleTo},
    {'name' : 'showItem', 'value' : showItem}
  ];

  $('.chk').each(function(index, el) {
    if($(this).is(':checked')){
      let names = 'warehouse['+$(this).val()+']';
      data.push({'name' : names, 'value' : $(this).val() });
    }
  });

  data = $.param(data);

  var token = new Date().getTime();
  var target = 'controller/stockReportController.php?stock_net_balance&export';
  target += '&'+data;
  target += '&token='+token;
  get_download(token);
  window.location.href = target;

}
