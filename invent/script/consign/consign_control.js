$('#barcode-item').keyup(function(e){
  if(e.keyCode == 13){
    if($(this).val() != ''){
      getItemByBarcode();
    }
  }
});




$('#item-code').keyup(function(e) {
  if(e.keyCode == 13){
    getProductByCode();
  }
});




$('#item-code').autocomplete({
  source:'controller/consignController.php?getProduct',
  autoFocus:true,
  close:function(){
    var rs = $(this).val();
    if( rs == 'ไม่พบสินค้า'){
      $(this).val('');
    }
  }
});





function getProductByCode(){
  var code = $.trim($('#item-code').val());
  var id_zone = $('#id_zone').val();
  //--- ถ้ายังไม่มีรายการอยู่แล้ว
  if( $('#'+code).length == 0){
    $.ajax({
      url:'controller/consignController.php?getProductByCode',
      type:'GET',
      cache:'false',
      data:{
        'code' : code,
        'id_zone' : id_zone
      },
      success:function(rs){
        var rs = $.trim(rs);
        if( isJson(rs) ){
          var ds = $.parseJSON(rs);
          var source = $('#row-template').html();
          var output = $('#detail-table');
          render_prepend(source, ds, output);
          reOrder();
          reCal(ds.id_product);
          focusRow(ds.id_product);
          $('#item-code').focus();
        }else{
          swal('Error', rs, 'error');
        }
      }
    });

  }else{
    //--- ถ้ามีรายการอยู่แล้ว
    var id_product = $('#'+code).val();
    var qty = isNaN(parseInt($('#qty-'+id_product).val())) ? 0 : parseInt($('#qty-'+id_product).val());
    qty++;
    $('#qty-'+id_product).val(qty);
    reCal(id_product);
    focusRow(id_product);
    $('#item-code').focus();
  }

}






function getItemByBarcode(){
  var barcode = $.trim($('#barcode-item').val());
  $('#barcode-item').val('');
  var id_zone = $('#id_zone').val();
  //--- ถ้ายังไม่มีรายการอยู่แล้ว
  if( $('#'+barcode).length == 0){
    $.ajax({
      url:'controller/consignController.php?getItemByBarcode',
      type:'GET',
      cache:'false',
      data:{
        'barcode' : barcode,
        'id_zone' : id_zone
      },
      success:function(rs){
        var rs = $.trim(rs);
        if( isJson(rs) ){
          var ds = $.parseJSON(rs);
          var source = $('#row-template').html();
          var output = $('#detail-table');
          render_prepend(source, ds, output);
          reOrder();
          reCal(ds.id_product);
          focusRow(ds.id_product);
          $('#barcode-item').focus();
        }else{
          swal('Error', rs, 'error');
          $('#barcode-item').val(barcode);
        }
      }
    });

  }else{
    //--- ถ้ามีรายการอยู่แล้ว
    var id_product = $('#'+barcode).val();
    var qty = isNaN(parseInt($('#qty-'+id_product).val())) ? 0 : parseInt($('#qty-'+id_product).val());
    qty++;
    $('#qty-'+id_product).val(qty);
    reCal(id_product);
    focusRow(id_product);
    $('#barcode-item').focus();
  }

}


function reOrder(){
  $('.no').each(function(index, el) {
    $(this).text(index+1);
  });
}


function focusRow(id){
  $('.rox').removeClass('blue');
  $('#row-'+id).addClass('blue');
}


function reCal(id_product){
  var price  = isNaN(parseFloat($('#price-'+id_product).val())) ? 0 : parseFloat($('#price-'+id_product).val());
  var p_disc = isNaN(parseFloat($('#p_disc-'+id_product).val())) ? 0 : parseFloat($('#p_disc-'+id_product).val());
  var a_disc = isNaN(parseFloat($('#a_disc-'+id_product).val())) ? 0 : parseFloat($('#a_disc-'+id_product).val());
  var qty    = isNaN(parseInt($('#qty-'+id_product).val())) ? 0 : parseInt($('#qty-'+id_product).val());
  var stock  = parseInt($('#stock-'+id_product).val());

  var disc   = (price * (p_disc * 0.01)) + a_disc;
  var amount = qty * (price - disc);
  $('#amount-'+id_product).text(addCommas(amount.toFixed(2)));
  if( qty > stock ){
    $('#qty-'+id_product).addClass('has-error');
  }else{
    $('#qty-'+id_product).removeClass('has-error');
  }

  updateTotalQty();
  updateTotalAmount();
}


function p_disc_recal(id){
  var pDisc = $('#p_disc-'+id).val();
  if( pDisc > 0){
    $('#a_disc-'+id).val(0);
  }

  reCal(id);
}




function a_disc_recal(id){
  var aDisc = $('#a_disc-'+id).val();
  if( aDisc > 0){
    $('#p_disc-'+id).val(0);
  }

  reCal(id);
}



function updateTotalAmount(){
  var total = 0;
  $('.amount').each(function(index, el) {
    var amount = isNaN(parseFloat(removeCommas($(this).text()))) ? 0 :parseFloat(removeCommas($(this).text()));
    total += amount;
  });

  total = parseFloat(total).toFixed(2);
  $('#total-amount').text(addCommas(total));
}





function updateTotalQty(){
  var total = 0;
  $('.qty').each(function(index, el) {
    var qty = isNaN(parseInt($(this).val())) ? 0 : parseInt($(this).val());
    total += qty;
  });

  $('#total-qty').text(addCommas(total));
}
