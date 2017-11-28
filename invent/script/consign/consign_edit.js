function getEdit(){
  $('.header-box').removeAttr('disabled');
  $('#btn-edit').addClass('hide');
  $('#btn-update').removeClass('hide');
}


function checkUpdate(){
  var id = $("#id_consign").val();
  var dateAdd = $('#date_add').val();
  var id_customer = $('#id_customer').val();
  var customerName = $('#customerName').val();
  var id_zone = $('#id_zone').val();
  var zoneName = $('#zoneName').val();
  var id_shop = $('#id_shop').val();
  var shopName = $('#shopName').val();
  var eventName = $('#eventName').val();
  var id_event = $('#id_event').val();
  var remark = $('#remark').val();
  var is_so = $('#isSo').val();

  //----  ไว้ตรวจสอบ หากเลือก shop ลูกค้า และโซนต้องตรงกับที่กำหนดใน tbl_shop
  var id_customer_shop = $('#id_customer_shop').val();
  var id_zone_shop = $('#id_zone_shop').val();
  if( shopName.length == 0){
    id_shop = '';
  }

  if( zoneName.length == 0){
    id_zone = '';
  }

  if( customerName.length == 0){
    id_customer = '';
  }

  if( eventName.length == 0){
    id_event = '';
  }

  if( !isDate(dateAdd)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

  if( id_customer.length == 0 || customerName.length == 0){
    swal('ลูกค้าไม่ถูกต้อง');
    return false;
  }

  if( id_zone.length == 0 || zoneName.length == 0){
    swal('โซนไม่ถูกต้อง');
  }



  if( id_shop != '' && id_shop != 0 && shopName.length != 0){
    if( id_customer != id_customer_shop ){
      swal('ลูกค้าที่เลือกไม่ตรงกับจุดขาย');
      return false;
    }

    if( id_zone != id_zone_shop ){
      swal('โซนที่เลือกไม่ตรงกับจุดขาย');
      return false;
    }
  }

  //--- ตรวจสอบก่อนว่าคนอื่นบันทึกไปแล้วหรือยัง
  $.ajax({
    url:'controller/consignController.php?canUpdate',
    type:'GET',
    cache:'false',
    data:{
      'id_consign' : id
    },
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'ok'){
        update(id, dateAdd, id_customer, id_zone, id_shop, id_event, remark, is_so);
      }else{
        swal('Error!', rs, 'error');
      }
    }
  });
}


function update(id_consign, dateAdd, id_customer, id_zone, id_shop, id_event, remark, is_so){
  load_in();
  $.ajax({
    url:'controller/consignController.php?updateConsign',
    type:'POST',
    cache:'false',
    data:{
      'id_consign' : id_consign,
      'date_add' : dateAdd,
      'id_customer' : id_customer,
      'id_zone' : id_zone,
      'id_shop' : id_shop,
      'id_event' : id_event,
      'remark' : remark,
      'is_so' : is_so
    },
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if(rs == 'success'){

        $('.header-box').attr('disabled', 'disabled');
        $('#btn-update').addClass('hide');
        $('#btn-edit').removeClass('hide');

        swal({
          title:'Updated',
          type:'success',
          timer: 1000
        });

        updateStockZone(id_zone);

      }else{
        swal('Error', rs, 'error');
      }
    }
  });
}
