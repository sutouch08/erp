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



$('#txt-customer-from').autocomplete({
  source:'controller/autoCompleteController.php?getCustomerCodeAndName',
  autoFocus:true,
  close:function(){
    rs = $(this).val().split(' | ');
    if(rs.length == 2){
      $(this).val(rs[0]);
      reorder();
      $('#txt-customer-to').focus();
    }else{
      $(this).val('');
    }
  }
});


$('#txt-customer-to').autocomplete({
  source:'controller/autoCompleteController.php?getCustomerCodeAndName',
  autoFocus:true,
  close:function(){
    rs = $(this).val().split(' | ');
    if(rs.length == 2){
      $(this).val(rs[0]);
      reorder();
    }else{
      $(this).val('');
    }
  }
});



function reorder(){
  from = $('#txt-customer-from').val();
  to = $('#txt-customer-to').val();

  if(from.length > 0 && to.length > 0){
    if(from > to){
      $('#txt-customer-from').val(to);
      $('#txt-customer-to').val(from);
    }
  }
}



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
      $('#fromDate').datepicker('option','maxDate', sd);
    }
  });
});




function getReport(){
  //--- ลูกค้า
  var cusFrom = $('#txt-customer-from').val();
  var cusTo = $('#txt-customer-to').val();

  //--- สินค้า
  var allProduct = $('#allProduct').val();
  var showItem = $('#showItem').val();
  var pdFrom  = $('#txt-pd-from').val();
  var pdTo = $('#txt-pd-to').val();
  var styleFrom = $('#txt-style-from').val();
  var styleTo = $('#txt-style-to').val();

  //----  วันที่
  var fromDate = $('#fromDate').val();
  var toDate = $('#toDate').val();

  //--- ตรวจสอบลูกค้า
  if(cusFrom.length == 0 || cusTo.length == 0){
    swal('กรุณาระบุลูกค้า');
    return false;
  }

  //--- ตรวจสอบสินค้า
  if(allProduct == 0){
      if(showItem == 1 && (pdFrom.length == 0 || pdTo.length == 0)){
        swal('กรุณาระบุรหัสสินค้า');
        return false;
      }

      if(showItem == 0 && (styleFrom.length == 0 || styleTo.length == 0)){
        swal('กรุณาระบุรหัสสินค้า');
        return false;
      }
  }

  //------  ตรวจสอบวันที่
  if(!isDate(fromDate) || !isDate(toDate)){
    swal('กรุณาระบุวันที่');
    return false;
  }

  var data = [
    {'name' : 'cusFrom', 'value' : cusFrom},
    {'name' : 'cusTo', 'value' : cusTo},
    {'name' : 'allProduct', 'value' : allProduct},
    {'name' : 'showItem', 'value' : showItem},
    {'name' : 'pdFrom', 'value' : pdFrom},
    {'name' : 'pdTo', 'value' : pdTo},
    {'name' : 'styleFrom', 'value' : styleFrom},
    {'name' : 'styleTo', 'value' : styleTo},
    {'name' : 'fromDate', 'value' : fromDate},
    {'name' : 'toDate', 'value' : toDate}
  ];


  load_in();
  $('#result').html('');

  $.ajax({
    url:'controller/saleReportController.php?consignment_by_customer&report',
    type:'GET',
    cache:'false',
    data:data,
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if(isJson(rs)){
        var source = $('#template').html();
        var data = $.parseJSON(rs);
        var output = $('#result');
        render(source, data, output);
        tableInit();
      }else{
        //--- ถ้าผลลัพธ์เกิน 1000 รายการ
        swal('Error!', rs, 'error');
      }
    }
  });

}


function tableInit(){
  $.tablesorter.addParser({
    id: "commaNum",
    is: function(s) {
        return /^[\d-]?[\d,]*(\.\d+)?$/.test(s);
    },
    format: function(s) {
        return s.replace(/,/g,'');
    },
    type: 'numeric'
  });

  $('#myTable').tablesorter({
    dateFormat:"uk",
    headers:{
      0:{
        sorter:false
      },
      3:{
        sorter:"commaNum"
      },
      4:{
        sorter:"commaNum"
      }
    }
  });

  $('#myTable').bind('sortEnd', function(){
    reIndex();
  });
}




function doExport(){
  //--- ลูกค้า
  var cusFrom = $('#txt-customer-from').val();
  var cusTo = $('#txt-customer-to').val();

  //--- สินค้า
  var allProduct = $('#allProduct').val();
  var showItem = $('#showItem').val();
  var pdFrom  = $('#txt-pd-from').val();
  var pdTo = $('#txt-pd-to').val();
  var styleFrom = $('#txt-style-from').val();
  var styleTo = $('#txt-style-to').val();

  //----  วันที่
  var fromDate = $('#fromDate').val();
  var toDate = $('#toDate').val();

  //--- ตรวจสอบลูกค้า
  if(cusFrom.length == 0 || cusTo.length == 0){
    swal('กรุณาระบุลูกค้า');
    return false;
  }

  //--- ตรวจสอบสินค้า
  if(allProduct == 0){
      if(showItem == 1 && (pdFrom.length == 0 || pdTo.length == 0)){
        swal('กรุณาระบุรหัสสินค้า');
        return false;
      }

      if(showItem == 0 && (styleFrom.length == 0 || styleTo.length == 0)){
        swal('กรุณาระบุรหัสสินค้า');
        return false;
      }
  }

  //------  ตรวจสอบวันที่
  if(!isDate(fromDate) || !isDate(toDate)){
    swal('กรุณาระบุวันที่');
    return false;
  }

  var data = [
    {'name' : 'cusFrom', 'value' : cusFrom},
    {'name' : 'cusTo', 'value' : cusTo},
    {'name' : 'allProduct', 'value' : allProduct},
    {'name' : 'showItem', 'value' : showItem},
    {'name' : 'pdFrom', 'value' : pdFrom},
    {'name' : 'pdTo', 'value' : pdTo},
    {'name' : 'styleFrom', 'value' : styleFrom},
    {'name' : 'styleTo', 'value' : styleTo},
    {'name' : 'fromDate', 'value' : fromDate},
    {'name' : 'toDate', 'value' : toDate}
  ];

  data = $.param(data);

  var token = new Date().getTime();
  var target = 'controller/saleReportController.php?consignment_by_customer&export';
  target += '&'+data;
  target += '&token='+token;
  get_download(token);
  window.location.href = target;

}
