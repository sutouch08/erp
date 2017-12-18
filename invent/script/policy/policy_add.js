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
