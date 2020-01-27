function getReport(){
  allItems = $('#allItems').val();
  fromPd = $('#txt-from-pd').val();
  toPd = $('#txt-to-pd').val();
  fromDate = $('#fromDate').val();
  toDate = $('#toDate').val();

  if(allItems == 0 ){
    if(fromPd.length == 0 || toPd.length == 0){
      swal('เลขที่เอกสารไม่ถูกต้อง');
      return false;
    }
  }

  if(!isDate(fromDate) || !isDate(toDate)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

  load_in();
  $.ajax({
    url:'controller/stockReportController.php?received_by_items',
    type:'GET',
    cache:'false',
    data:{
      'report' : 'Y',
      'allItems' : allItems,
      'from_code' : fromPd,
      'to_code' : toPd,
      'fromDate' : fromDate,
      'toDate' : toDate
    },
    success:function(rs){
      load_out();

      if(!isJson(rs)){
        swal(rs);
      }else{
        source = $('#report-template').html();
        data = $.parseJSON(rs);
        output = $('#result');
        render(source, data, output);
      }
    }
  });
}




function doExport(){
  allItems = $('#allItems').val();
  fromPd = $('#txt-from-pd').val();
  toPd = $('#txt-to-pd').val();
  fromDate = $('#fromDate').val();
  toDate = $('#toDate').val();

  if(allItems == 0 ){
    if(fromPd.length == 0 || toPd.length == 0){
      swal('เลขที่เอกสารไม่ถูกต้อง');
      return false;
    }
  }

  if(!isDate(fromDate) || !isDate(toDate)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }


  data = [
    {'name' : 'allItems' , 'value' : allItems },
    {'name' : 'from_code', 'value' : fromPd},
    {'name' : 'to_code', 'value' : toPd},
    {'name' : 'fromDate', 'value' : fromDate},
    {'name' : 'toDate', 'value' : toDate}
  ];

  data = $.param(data);

  var token = new Date().getTime();
  var target = 'controller/stockReportController.php?received_by_items&export';
  target += '&'+data;
  target += '&token='+token;
  get_download(token);
  window.location.href = target;
}





$('#txt-from-pd').focusout(function(event) {
  from = $(this).val();
  to = $('#txt-to-pd').val();
  if(from.length > 0 && to.length > 0)
  {
    if(from > to){
      $('#txt-from-pd').val(to);
      $('#txt-to-pd').val(from);
    }
  }
});


$('#txt-to-pd').focusout(function(event) {
  from = $('#txt-from-pd').val();
  to = $(this).val();
  if(from.length > 0 && to.length > 0){
    if(from > to){
      $('#txt-from-pd').val(to);
      $('#txt-to-pd').val(from);
    }
  }
});

function toggleItems(option){
  $('#allItems').val(option);
  if(option == 1){
    $('#btn-all').addClass('btn-primary');
    $('#btn-range').removeClass('btn-primary');
    $('#txt-from-pd').attr('disabled', 'disabled');
    $('#txt-to-pd').attr('disabled', 'disabled');

  }else{
    $('#btn-all').removeClass('btn-primary');
    $('#btn-range').addClass('btn-primary');
    $('#txt-from-pd').removeAttr('disabled');
    $('#txt-to-pd').removeAttr('disabled');
    $('#txt-from-pd').focus();
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


$('#txt-from-pd').autocomplete({
  source:'controller/autoCompleteController.php?getItemCode',
  autoFocus:true,
  close:function(){
    var rs = $.trim($(this).val());
    if(rs == '' && rs == 'ไม่พบข้อมูล'){
      $(this).val('');
    }else{
      var pdFrom = $(this).val();
      var pdTo   = $('#txt-to-pd').val();
      if(pdTo.length > 0 && pdFrom > pdTo){
        $(this).val(pdTo);
        $('#txt-to-pd').val(pdFrom);
      }
      $('#txt-to-pd').focus();

    }
  }
});


$('#txt-to-pd').autocomplete({
  source:'controller/autoCompleteController.php?getItemCode',
  autoFocus:true,
  close:function(){
    var rs = $.trim($(this).val());
    if(rs == '' && rs == 'ไม่พบข้อมูล'){
      $(this).val('');
    }else{
      var pdFrom = $('#txt-from-pd').val();
      var pdTo   = $(this).val();
      if(pdFrom.length > 0 && pdFrom > pdTo){
        $(this).val(pdFrom);
        $('#txt-from-pd').val(pdTo);
      }

      if(pdFrom.length == 0){
        $('#txt-from-pd').focus();
      }
    }
  }
});
