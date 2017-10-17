function saveSupport(){
  var id_employee = $('#id_employee').val();
  var name        = $('#employee').val();
  var reference   = $('#reference').val();
  var budget      = $('#budget').val() == '' ? 0.00 : $('#budget').val();
  var fromDate    = $('#fromDate').val();
  var toDate      = $('#toDate').val();
  var year        = $('#year').val();
  var remark      = $('#remark').val();

  //--- ตรวจสอบชือผู้รับสปอนเซอร์
  if( id_employee == '' || name == ''){
    $('#employee-error').removeClass('hidden');
    $('#employee').addClass('has-error');
    return false;
  }else{
    $('#employee-error').addClass('hidden');
    $('#employee').removeClass('has-error');
  }

  //--- ตรวจสอบวันที่
  if(!isDate(fromDate) || !isDate(toDate)){

    if( !isDate(fromDate)){
      $('#fromDate').addClass('has-error');
    }

    if( !isDate(toDate)){
      $('#toDate').addClass('has-error');
    }

    $('#date-error').removeClass('hidden');

    return false;

  }else{
    $('#fromDate').removeClass('has-error');
    $('#toDate').removeClass('has-error');
    $('#date-error').addClass('hidden');
  }

  load_in();
  $.ajax({
    url:'controller/supportController.php?addNewSupport',
    type:'POST',
    cache:'false',
    data:{
      'id_employee' : id_employee,
      'name'        : name,
      'reference'   : reference,
      'budget'      : budget,
      'fromDate'    : fromDate,
      'toDate'      : toDate,
      'year'        : year,
      'remark'      : remark
    },
    success:function(rs){
      load_out();

      var rs = $.trim(rs);

      //--- ถ้าเพิ่มสำเร็จ
      if( rs == 'success'){
        swal({
          title:'สำเร็จ',
          text: 'เพิ่มผู้รับเรียบร้อยแล้ว <br/>ต้องการเพิ่มผู้รับอื่นอีกหรือไม่ ?',
          type:'success',
          html:true,
          showCancelButton:true,
          cancelButtonText:'ไม่ต้องการ',
          confirmButtonColor:'#3ABFDA',
          confirmButtonText:'ต้องการ',
          closeOnConfirm:true
        }, function(isConfirm){
          if(isConfirm){
            clearFields();
          }else{
            goBack();
          }
        });

      }else{
        //--- ถ้าไม่สำเร็จ
        swal('Error !', rs, 'error');
      }
    }
  });
}






//--- Clear input fields to continue support add
function clearFields(){
  window.location.reload();
}



$('#employee').autocomplete({
  source:'controller/supportController.php?getEmployee',
  autoFocus:true,
  close:function(){
    var rs = $(this).val().split(' | ');
    if(rs.length == 2){
      var id = rs[1];
      var name = rs[0];
      $(this).val(name);
      checkDuplicate(id);
    }else{
      $('#id_employee').val('');
      $(this).val('');
    }
  }
});






function checkDuplicate(id){
  $.ajax({
    url:'controller/supportController.php?isExistsSupport',
    type:'POST', cache:'false', data:{'id_employee':id},
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'ok'){
        $('#id_employee').val(id);
        $('#reference').focus();
      }else if( ! isNaN(parseInt(rs))){
        $('#id_employee').val('');
        $('#employee').val('');
        swal({
          title:'Duplicated !',
          text:'มีรายชื่อนี้อยู่ในผู้รับอภินันท์แล้ว ต้องการเพิ่มงบประมาณหรือไม่',
          type:'warning',
          showCancelButton:true,
          cancelButtonText:'ไม่ต้องการ',
          confirmButtonText:'ใช่ ต้องการ',
          confirmButtonColor:'#3ABFDA',
          closeOnConfirm:true
        },function(){
          window.location.href = 'index.php?content=support&edit=Y&id_support='+rs;
        });
      }else{
        $('#id_employee').val('');
        $('#employee').val('');
      }
    }
  });
}
