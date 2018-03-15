function addNew(){
  var name = $('#policyName').val();
  var fromDate = $('#fromDate').val();
  var toDate = $('#toDate').val();

  if( name.length == 0){
    swal('ชื่อนโยบายไม่ถูกต้อง');
    return false;
  }

  if( !isDate(fromDate) || !isDate(toDate)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

  $.ajax({
    url:'controller/policyController.php?addNew',
    type:'POST',
    cache:'false',
    data:{
      'name' : name,
      'fromDate' : fromDate,
      'toDate' : toDate
    },
    success:function(rs){
      var rs = $.trim(rs);
      if(!isNaN(parseInt(rs))){
        goAdd(rs);
      }else{
        swal({
          title:'Error',
          text:rs,
          type:'error'
        });
      }
    }
  });
}


function newRule(id){
  var id = $('#id_policy').val();
  window.location.href = 'index.php?content=discount_policy&add_rule=Y&id_policy='+id;
}



function getEdit(){
  $('.header-box').removeAttr('disabled');
  $('#btn-edit').addClass('hide');
  $('#btn-update').removeClass('hide');
}



function update(){
  var id_policy = $('#id_policy').val();
  var pName = $('#policyName').val();
  var fromDate = $('#fromDate').val();
  var toDate = $('#toDate').val();

  if(isNaN(parseInt(id_policy))){
    swal('Error!', 'ไม่พบ ID Policy', 'error');
    return false;
  }

  if(pName.length < 4 || pName.length > 150){
    swal('ข้อมูลไม่ถูกต้อง','กรุณากำหนดชื่อนโยบายอย่างน้อย 4 ตัวอักษร สูงสุด 150 ตัวอักษร', 'warning');
    return false;
  }

  if(!isDate(fromDate) || ! isDate(toDate)){
    swal('วันที่ไม่ถูกต้อง', 'กรุณากำหนดวันที่ให้ถูกต้อง', 'error');
    return false;
  }

  load_in();

  $.ajax({
    url:'controller/policyController.php?updatePolicy',
    type:'POST',
    cache:'false',
    data:{
      'id' : id_policy,
      'name' : pName,
      'date_start' : fromDate,
      'date_end' : toDate
    },
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if(rs == 'success'){
        swal({
          title:'Success',
          type:'success',
          timer:1000
        });

        $('.header-box').attr('disabled', 'disabled');
        $('#btn-update').addClass('hide');
        $('#btn-edit').removeClass('hide');
      }
    }
  });
}
