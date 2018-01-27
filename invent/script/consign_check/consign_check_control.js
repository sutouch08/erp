
//---- When User scan barcode box
$('#txt-box-barcode').keyup(function(e){
  if(e.keyCode == 13){
    var barcode = $.trim($(this).val());
    if(barcode.length > 0){
      getBox(barcode);
    }
  }
});



//---- When User scan barcode item
$('#txt-pd-barcode').keyup(function(e){
  if(e.keyCode == 13){
    var barcode = $.trim($(this).val());
    var qty = $('#txt-qty').val();
    if(barcode.length > 0 && qty != 0){
      checkItem(barcode, qty);
    }
  }
});




function checkItem(barcode, qty){
  var id = $('#id_consign_check').val();
  var id_box = $('#id_box').val();
  var qty = parseInt(qty);
  if(id_box != ''){
    $.ajax({
      url:'controller/consignCheckController.php?checkItem',
      type:'POST',
      cache:'false',
      data:{
        'id_consign_check' : id,
        'id_box' : id_box,
        'barcode' : barcode,
        'qty' : qty
      },
      success:function(rs){
        var rs = $.trim(rs);
        if(rs == 'success'){

          var stock = parseInt($('#stock-qty-'+barcode).text());
          var checked = parseInt($('#check-qty-'+barcode).text());
          var box_qty = parseInt($('#box-qty').text());
          checked += qty;
          var diff = stock - checked;
          box_qty += qty;

          $('#check-qty-'+barcode).text(checked);
          $('#diff-qty-'+barcode).text(diff);
          $('#box-qty').text(box_qty);
          $('#txt-qty').val(1);
          $('#txt-pd-barcode').val('');
          if(checked != 0){
            $('#btn-'+barcode).removeClass('hide');
          }else{
            $('#btn-'+barcode).addClass('hide');
          }

          $('#detail-table').prepend($('#row-'+barcode));

          $('#txt-pd-barcode').focus();
        }else{
          swal('Error!', rs, 'error');
          $('#txt-qty').val(1);
          $('#txt-pd-barcode').val('');
        }
      }
    });
  }else{
    swal('Error!', 'กรุณายิงบาร์โค้ดกล่อง', 'error');
  }

}





//----- get box id to start check in box
function getBox(barcode){
  var id = $('#id_consign_check').val();
  $.ajax({
    url:'controller/consignCheckController.php?getBox',
    type:'GET',
    cache:'false',
    data:{
      'id_consign_check' : id,
      'barcode' : barcode
    },
    success:function(rs){
      var id_box = $.trim(rs);
      if(isJson(rs))
      {
        box = $.parseJSON(rs);
        $('#id_box').val(box.id_box);
        $('#box-qty').text(box.qty);
        $('#box-label').text('กล่องที่ ' + box.box_no);
        activeControl();
      }
    }
  });
}


function changeBox(){
  $('#id_box').val('');
  $('#txt-box-barcode').val('');
  $('#txt-pd-barcode').val('');
  $('#box-qty').text('0');
  $('#box-label').text('จำนวนในกล่อง');
  $('.item').attr('disabled', 'disabled');
  $('.box').removeAttr('disabled');
  $('#txt-box-barcode').focus();
}



function activeControl(){
  var id_box = $('#id_box').val();
  if(id_box != ''){

    $('.box').attr('disabled', 'disabled');
    $('.item').removeAttr('disabled');
    $('#txt-pd-barcode').focus();

  }else{
    swal('โซนไม่ถูกต้อง');
  }
}



$(document).ready(function() {
  var sumStock = $('#sumStock').val();
  var sumCheck = $('#sumCount').val();
  var sumDiff  = $('#sumDiff').val();
  $('#total-zone').text(sumStock);
  $('#total-checked').text(sumCheck);
  $('#total-diff').text(sumDiff);
});
