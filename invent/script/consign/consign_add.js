function saveConsign(){
  var err = 0;
  var valid = 0;
  var id_consign = $('#id_consign').val();
  var id_zone = $('#id_zone').val();
  var id_customer = $('#id_customer').val();
  var id_shop = $('#id_shop').val();
  var id_event = $('#id_event').val();
  var is_so = $('#isSo').val();

  //--- โซนติดลบได้หรือไม่
  var allowUnderZero = $('#allowUnderZero').val();

  var ds = [
    {'name' : 'id_consign', 'value' : id_consign},
    {'name' : 'id_zone', 'value' : id_zone},
    {'name' : 'id_customer', 'value' : id_customer},
    {'name' : 'id_shop', 'value' : id_shop},
    {'name' : 'id_event', 'value' : id_event},
    {'name' : 'allowUnderZero', 'value' : allowUnderZero},
    {'name' : 'is_so', 'value' : is_so}
  ];

  $('.product').each(function(index, el) {
    var id     = $(this).val();

    //--- จำนวนที่จะตัดยอด
    var qty    = isNaN(parseInt($('#qty-'+id).val())) ? 0 : parseInt($('#qty-'+id).val());

    //--- ราคาสินค้า
    var price  = isNaN(parseFloat($('#price-'+id).val())) ? 0 : parseFloat($('#price-'+id).val());

    //--- ส่วนลดเป็น %
    var p_disc = isNaN(parseFloat($('#p_disc-'+id).val())) ? 0 : parseFloat($('#p_disc-'+id).val());

    //--- ส่วนลดเป็นจำนวนเงิน
    var a_disc = isNaN(parseFloat($('#a_disc-'+id).val())) ? 0 : parseFloat($('#a_disc-'+id).val());

    //--- สต็อกคงเหลือในโซนที่จะตัด
    var stock  = isNaN(parseInt($('#stock-'+id).val())) ? 0 : parseInt($('#stock-'+id).val());

    //console.log('qty-'+id+' = '+qty);

    //--- จำนวนต้องมากกว่า 0
    //--- และ จำนวนต้องน้อยกว่าหรือเท่ากับในโซนที่จะตัด
    //--- หรือ ถ้ามากกว่าโซนที่จะตัดต้องสามารถติดลบได้
    if( qty > 0 && (qty <= stock || allowUnderZero == 1) ){
      ds.push({'name' : 'product['+id+']', 'value' : $(this).val()});
      ds.push({'name' : 'qty['+id+']', 'value' : qty});
      ds.push({'name' : 'price['+id+']', 'value' : price});
      ds.push({'name' : 'p_disc['+id+']', 'value' : p_disc});
      ds.push({'name' : 'a_disc['+id+']', 'value' : a_disc});
      $('#qty-'+id).removeClass('has-error');
      valid++;
    }else{
      $('#qty-'+id).addClass('has-error');
      err++;
    }
  });

  if( err > 0){
    swal('Error!', 'จำนวนไม่ถูกต้อง กรุณาตรวจสอบ', 'error');
    return false;
  }

  if(valid == 0){
    swal('Error!', 'ไม่พบรายการที่จะตัดยอด กรุณาตรวจสอบ', 'error');
    return false;
  }

  if(valid > 0){
    $.ajax({
      url:'controller/consignController.php?saveConsign',
      type:'POST',
      cache:'false',
      data: ds,
      success:function(rs){
        var rs = $.trim(rs);
        if(rs == 'success'){
          if( is_so == 1){
            exportConsignSold(id_consign);
          }else{

            swal({
              title:'Saved',
              type:'success',
              timer:1000
            });

            setTimeout(function(){
              goDetail(id_consign);
            }, 1200);
          }

        }else{
          swal('Error!', rs, 'error');
        }
      }
    });
  }
}




function addNew(){
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

  load_in();
  $.ajax({
    url:'controller/consignController.php?addNew',
    type:'POST',
    cache:'false',
    data:{
      'date_add' : dateAdd,
      'id_customer' : id_customer,
      'id_zone' : id_zone,
      'id_shop' : id_shop,
      'id_event' : id_event,
      'remark'  : remark,
      'is_so' : is_so
    },
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if( !isNaN(rs/1) ){
        goAdd(rs);
      }else{
        swal('Error!', rs, 'error');
      }
    }
  });

}




$('#date_add').datepicker({
  dateFormat:'dd-mm-yy'
});


$('#shopName').autocomplete({
  source:'controller/shopController.php?getShop',
  autoFocus:true,
  close:function(){
    var rs = $(this).val();
    var rs = rs.split(' | ');
    if(rs.length == 3){
      var code = rs[0];
      var name = rs[1];
      var id = rs[2];
      $(this).val(name);
      $('#id_shop').val(rs[2]);
      //--- ดึงข้อมูล shop มา update
      getShopData(id);
    }else{
      $('#id_shop').val('');
      $(this).val('');
    }
  }
});




function getShopData(id){
  $.ajax({
    url:'controller/shopController.php?getShopData',
    type:'GET',
    cache:'false',
    data:{
      'id_shop' : id
    },
    success:function(rs){
      var rs = $.trim(rs);
      var rs = rs.split(' | ');
      if( rs.length == 4)
      //--- ไว้ตรวจสอบ
      $('#id_customer_shop').val(rs[0]);
      $('#id_zone_shop').val(rs[2]);


      //--- ไว้ใช้งาน
      $('#id_customer').val(rs[0]);
      $('#customerName').val(rs[1]);
      $('#id_zone').val(rs[2]);
      $('#zoneName').val(rs[3]);
      updateAllowUnderZero(rs[2]);
    }
  });
}




$('#customerName').autocomplete({
  source:'controller/consignController.php?getCustomer',
  autoFocus:true,
  close:function(){
    var rs = $(this).val();
    var rs = rs.split(' | ');
    if(rs.length == 2){
      var code = rs[0];
      var name = rs[1];
      $(this).val(name);
      getCustomerId(code);
    }else{
      $('#id_customer').val('');
      $(this).val('');
    }
  }
});


function getCustomerId(code){
  $.ajax({
    url:'controller/customerController.php?getCustomerId',
    type:'GET',
    cache:'false',
    data:{
      'customerCode' : code
    },
    success:function(rs){
      var rs = $.trim(rs);
      $('#id_customer').val(rs);
      zoneInit();
    }
  });
}




function zoneInit(){
  var id = $('#id_customer').val();
  $('#id_zone').val('');
  $('#zoneName').val('');
  $('#allowUnderZero').val(0);
  $('#zoneName').autocomplete({
    source:'controller/consignController.php?getCustomerZone&id_customer='+id,
    autoFocus:true,
    close:function(){
      var rs = $(this).val();
      var rs = rs.split(' | ');
      if( rs.length == 2){
        var name = rs[0];
        var id_zone = rs[1];
        $(this).val(name);
        $('#id_zone').val(id_zone);
        updateAllowUnderZero(id_zone);
      }else{
        $('#id_zone').val('');
        $(this).val('');
        $('#allowUnderZero').val(0);
      }
    }
  });
}


function updateAllowUnderZero(id_zone){
  $.ajax({
    url:'controller/consignController.php?isAllowUnderZeroZone',
    type:'GET',
    cache:'false',
    data:{
      'id_zone' : id_zone
    },
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == '1'){
        $('#allowUnderZero').val(rs);
      }else{
        $('#allowUnderZero').val(0);
      }
    }
  });
}


$('#eventName').autocomplete({
  source:'controller/eventController.php?getEvent',
  autoFocus:true,
  close:function(){
    var rs = $(this).val();
    var rs = rs.split(' | ');
    if( rs.length == 3){
      var id = rs[2];
      var name = rs[1];
      $('#id_event').val(id);
      $(this).val(name);
    }else{
      $('#id_event').val('');
      $(this).val('');
    }
  }
});
