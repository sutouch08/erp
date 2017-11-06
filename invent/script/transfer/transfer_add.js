
//--- เพิ่มเอกสารโอนคลังใหม่
function addNew(){

  //--- วันที่เอกสาร
  var date_add = $('#date_add').val();

  //--- คลังต้นทาง
  var from_warehouse = $('#from-warehouse').val();

  //--- คลังปลายทาง
  var to_warehouse = $('#to-warehouse').val();

  //--- หมายเหตุ
  var remark = $('#remark').val();

  //--- ตรวจสอบวันที่
  if( ! isDate(date_add))
  {
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

  //--- ตรวจสอบคลังต้นทาง
  if(from_warehouse == ''){
    swal('กรุณาเลือกคลังต้นทาง');
    return false;
  }

  //--- ตรวจสอบคลังปลายทาง
  if(to_warehouse == ''){
    swal('กรุณาเลือกคลังปลายทาง');
    return false;
  }

  //--- ตรวจสอบว่าเป็นคนละคลังกันหรือไม่ (ต้องเป็นคนละคลังกัน)
  if( from_warehouse == to_warehouse){
    swal('คลังต้นทางต้องไม่ตรงกับคลังปลายทาง');
    return false;
  }

  //--- ถ้าไม่มีข้อผิดพลาด
  $.ajax({
    url:'controller/transferController.php?addNew',
    type:'POST',
    cache:'false',
    data:{
      'date_add' : date_add,
      'from_warehouse' : from_warehouse,
      'to_warehouse' : to_warehouse,
      'remark' : remark
    },
    success:function(rs){
      //--- ถ้าสำเร็จจะได้เป็น ไอดีกลับมา
      var rs = $.trim(rs);

      //--- ตรวจสอบว่าผลลัพธ์เป็นตัวเลขหรือไม่(ไอดี)
      if( ! isNaN( parseInt(rs))){
        //--- พาไปหน้าเพิ่มรายการ
        goAdd(rs);

      }else{
        //--- แจ้ง error
        swal('Error !', rs, 'error');
      }
    }
  });
}




//--- update เอกสาร
function update(){
  //--- ไอดีเอกสาร สำหรับส่งไปอ้างอิงการแก้ไข
  var id_transfer = $('#id_transfer').val();

  //--  วันที่เอกสาร
  var date_add = $('#date_add').val();

  //--- หมายเหตุ
  var remark = $('#remark').val();

  //--- ตรวจสอบไอดี
  if(id_transfer == ''){
    swal('Error !', 'ไม่พบ ID เอกสาร', 'error');
    return false;
  }

  //--- ตรวจสอบวันที่
  if( ! isDate(date_add)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }


  //--- ถ้าไม่มีอะไรผิดพลาด ส่งข้อมูไป update
  $.ajax({
    url:'controller/transferController.php?update',
    type:'POST',
    cache:'false',
    data:{
      'id_transfer' : id_transfer,
      'date_add'    : date_add,
      'remark'      : remark
    },
    success:function(rs){
      var rs = $.trim(rs)
      if( rs == 'success'){

        $('#date_add').attr('disabled', 'disabled');
        $('#remark').attr('disabled', 'disabled');
        $('#btn-update').addClass('hide');
        $('#btn-edit').removeClass('hide');

        swal({
          title:'Success',
          type:'success',
          timer: 1000
        });

      }else{

        swal('Error', rs, 'error');
      }
    }
  });
}



//--- แก้ไขหัวเอกสาร
function getEdit(){

  var isExport = $('#isExport').val();
  if( isExport == 0){
    $('#date_add').removeAttr('disabled');
    $('#remark').removeAttr('disabled');
    $('#btn-edit').addClass('hide');
    $('#btn-update').removeClass('hide');

  }else{

    swal('','เอกสารถูกส่งออกแล้ว ไม่อนุญาติให้แก้ไข','warning');
  }
}



$('#date_add').datepicker({
  dateFormat:'dd-mm-yy'
});
