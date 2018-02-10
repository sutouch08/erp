function getEdit(){
  var id_consign_check = $('#id_consign_check').val();
  if(id_consign_check > 0){
    $('.header-box').removeAttr('disabled');
  }else{
    $('.header-box').removeAttr('disabled');
    $('.import-box').removeAttr('disabled');
  }

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
  var remark = $('#remark').val();
  var is_so = $('#isSo').val();

  if( zoneName.length == 0){
    id_zone = '';
  }

  if( customerName.length == 0){
    id_customer = '';
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
        update(id, dateAdd, id_customer, id_zone, remark, is_so);
      }else{
        swal('Error!', rs, 'error');
      }
    }
  });
}


function update(id_consign, dateAdd, id_customer, id_zone, remark, is_so){
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
      'remark' : remark,
      'is_so' : is_so
    },
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if(rs == 'success'){
        $('.header-box').attr('disabled', 'disabled');
        $('.import-box').attr('disabled', 'disabled');
        $('#btn-update').addClass('hide');
        $('#btn-edit').removeClass('hide');

        swal({
          title:'Updated',
          type:'success',
          timer: 1000
        });

        //updateStockZone(id_zone);

      }else{
        swal('Error', rs, 'error');
      }
    }
  });
}
