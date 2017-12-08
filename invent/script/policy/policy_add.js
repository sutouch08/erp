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
}
