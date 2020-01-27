function getSearch(){
  $('#searchForm').submit();
}


function clearFilter(){
  $.get('controller/consignCheckController.php?clearFilter', function(){
    goBack();
  });
}

$('.search-box').keyup(function(e){
  if(e.keyCode == 13){
    getSearch();
  }
})

function goDelete(id, reference)
{
  var message = '<center>ข้อมูลทั้งหมดใน '+ reference +' จะถูกลบ</center>';
  message += '<center>ต้องการดำเนินการหรือไม่ ?</center>';
  swal({
    title:'ต้องการยกเลิก ?',
    text:message,
    type:'warning',
    html:true,
    showCancelButton:true,
    confirmButtonColor:'#FA5858',
    confirmButtonText: 'ดำเนินการ',
		cancelButtonText: 'ไม่ต้องการ',
		closeOnConfirm: false
  },function(){
    load_in();
    $.ajax({
      url:'controller/consignCheckController.php?cancleConsignCheck',
      type:'POST',
      cache:'false',
      data:{
        'id_consign_check' : id
      },
      success:function(rs){
        load_out();
        var rs = $.trim(rs);
        if(rs == 'success'){
          swal({
            title:'Deleted',
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
  });
}



function pullback(id, reference)
{
  swal({
    title:'ต้องการดึงสถานะกลับ ?',
    text:'ดำเนินการดึงสถานะ '+reference+' กลับหรือไม่ ?',
    type:'warning',
    html:true,
    showCancelButton:true,
    confirmButtonColor:'#FA5858',
    confirmButtonText: 'ดำเนินการ',
		cancelButtonText: 'ไม่ต้องการ',
		closeOnConfirm: false
  },function(){
    load_in();
    $.ajax({
      url:'controller/consignCheckController.php?pullBack',
      type:'POST',
      cache:'false',
      data:{
        'id_consign_check' : id
      },
      success:function(rs){
        load_out();
        var rs = $.trim(rs);
        if(rs == 'success'){
          swal({
            title:'Updated',
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
  });
}
