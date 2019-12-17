
//------  กำหนดสินค้า
function toggleDocument(option){

  if(option == '1'){

    $('#btn-all').addClass('btn-primary');
    $('#btn-range').removeClass('btn-primary');
    $('.doc-box').attr('disabled', 'disabled');

  }else if(option == '0'){

    $('#btn-all').removeClass('btn-primary');
    $('#btn-range').addClass('btn-primary');
    $('.doc-box').removeAttr('disabled');

  }

  $('#allDocument').val(option);
}


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



$('.doc-box').focusout(function(){
  let from = $('#txt-doc-from').val();
  let to = $('#txt-doc-to').val();

  if(from.length > 0 && to.length > 0){
    if(from > to){
      $('#txt-doc-from').val(to);
      $('#txt-doc-to').val(from);
    }
  }
});


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
  var allDocument = $('#allDocument').val();
  var docFrom  = $('#txt-doc-from').val();
  var docTo = $('#txt-doc-to').val();

  //----  วันที่
  var fromDate = $('#fromDate').val();
  var toDate = $('#toDate').val();

  //--- ตรวจสอบลูกค้า
  if(cusFrom.length == 0 || cusTo.length == 0){
    swal('กรุณาระบุลูกค้า');
    return false;
  }

  //--- ตรวจสอบสินค้า
  if(allDocument == 0){
      if(docFrom.length == 0 || docTo.length == 0){
        swal('กรุณาระบเลขที่เอกสาร');
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
    {'name' : 'allDocument', 'value' : allDocument},
    {'name' : 'docFrom', 'value' : docFrom},
    {'name' : 'docTo', 'value' : docTo},
    {'name' : 'fromDate', 'value' : fromDate},
    {'name' : 'toDate', 'value' : toDate}
  ];


  load_in();
  $('#result').html('');

  $.ajax({
    url:'controller/saleReportController.php?consignment_by_customer_group_by_document&report',
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
  var allDocument = $('#allDocument').val();
  var docFrom  = $('#txt-doc-from').val();
  var docTo = $('#txt-doc-to').val();

  //----  วันที่
  var fromDate = $('#fromDate').val();
  var toDate = $('#toDate').val();

  //--- ตรวจสอบลูกค้า
  if(cusFrom.length == 0 || cusTo.length == 0){
    swal('กรุณาระบุลูกค้า');
    return false;
  }

  //--- ตรวจสอบสินค้า
  if(allDocument == 0){
      if(docFrom.length == 0 || docTo.length == 0){
        swal('กรุณาระบเลขที่เอกสาร');
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
    {'name' : 'allDocument', 'value' : allDocument},
    {'name' : 'docFrom', 'value' : docFrom},
    {'name' : 'docTo', 'value' : docTo},
    {'name' : 'fromDate', 'value' : fromDate},
    {'name' : 'toDate', 'value' : toDate}
  ];


  data = $.param(data);

  var token = new Date().getTime();
  var target = 'controller/saleReportController.php?consignment_by_customer_group_by_document&export';
  target += '&'+data;
  target += '&token='+token;
  get_download(token);
  window.location.href = target;

}
