function saveConsign(){
  var id_consign = $('#id_consign').val();
  swal({
		title: "บันทึกขายและตัดสต็อก",
		text: "เมื่อบันทึกแล้วจะไม่สามารถแก้ไขได้ ต้องการบันทึกหรือไม่ ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#8CC152",
		confirmButtonText: 'บันทึก',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false,
    showLoaderOnConfirm:true
		}, function(){
      $.ajax({
        url:'controller/consignController.php?saveConsign',
        type:'POST',
        cache:'false',
        data:{
          'id_consign' : id_consign
        },
        success:function(rs){
          var rs = $.trim(rs);
          if(rs == 'success'){
            swal({
              title:'Saved',
              type:'success',
              timer:1000
            });

            setTimeout(function(){
              goDetail(id_consign);
            }, 1500);
          }else{
            swal('Error!', rs, 'error');
          }
        }
      });
	});
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
      consignCheckInit();
    }
  });
}


function consignCheckInit(){
  var id_customer = $('#id_customer').val();
  var id_zone = $('#id_zone').val();
  $('#txt-consign').autocomplete({
    source:'controller/consignCheckController.php?getConsignCheckReference&id_customer='+id_customer+'&id_zone='+id_zone,
    autoFocus:true,
    close:function(){
      var rs = $(this).val();
      if(rs == 'ไม่พบข้อมูล'){
        $(this).val('');
      }
    }
  });
}



function getActiveCheckList(){
  var id_customer = $('#id_customer').val();
  var id_zone = $('#id_zone').val();
  load_in();
  $.ajax({
    url:'controller/consignCheckController.php?getActiveCheckList',
    type:'GET',
    cache:'false',
    data:{
      'id_customer' : id_customer,
      'id_zone' : id_zone
    },
    success:function(rs){
      load_out();
      if(isJson(rs)){
        var source = $('#check-list-template').html();
        var data = $.parseJSON(rs);
        var output = $('#check-list-body');
        render(source, data, output);
        $('#check-list-modal').modal('show');
      }else{
        swal('Error', rs, 'error');
      }
    }
  });
}



function loadCheckDiff(id_consign_check, reference){
  swal({
    title: "นำเข้ายอดต่าง",
		text: "ต้องการนำเข้ายอดต่างจากเอกสารกระทบยอด "+reference+" หรือไม่ ?",
		type: "warning",
		showCancelButton: true,
		confirmButtonText: 'ใช่, ฉันต้องการ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: true
  },function(){
    var id_consign = $('#id_consign').val();
    var id_customer = $('#id_customer').val();
    var id_zone = $('#id_zone').val();
    load_in();
    $.ajax({
      url:'controller/consignController.php?loadCheckDiff',
      type:'GET',
      cache:'false',
      data:{
        'id_consign' : id_consign,
        'id_consign_check' : id_consign_check,
        'id_zone' : id_zone,
        'id_customer' : id_customer
      },
      success:function(rs){
        load_out();
        var rs = $.trim(rs);
        if(rs == 'success'){
          swal({
            title: 'Success',
            type:'success',
            timer:1000
          });

          setTimeout(function(){
            window.location.reload();
          },1500);
        }else{
          swal('Error!', rs, 'error');
        }
      }
    });

  });//--- swal
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

$(document).ready(function() {
  consignCheckInit();
});
