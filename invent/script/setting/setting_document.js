
function checkPrefix(){
  var pre = {};
  var data = {};
  $('.prefix').each(function(index, el){
    name = $(this).attr('name');
    id = $(this).attr('id');
    value = $(this).val();
    //--- ถ้าพบว่ามีรายการใดที่ว่าง
    if($(this).val() == ''){
      $(this).addClass('has-error');
      swal('กรุณากำหนดรหัสเอกสารให้ครบทุกช่อง');
      return false;
    }

    if(pre[value] != undefined){
      $(this).addClass('has-error');
      swal('รหัสเอกสาร '+ pre[value] +' ซ้ำ');
      return false;
    }else{
      $(this).removeClass('has-error');
      pre[value] = value;
      data[name] = $(this).val();
    }
  });

  return data;
}
