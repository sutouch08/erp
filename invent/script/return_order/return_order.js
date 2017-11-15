function goBack(){
  window.location.href = 'index.php?content=return_order';
}


function viewDetail(code){
  window.location.href = 'index.php?content=return_order&view_detail=Y&reference='+code;
}


function goEdit(code){
  window.location.href = 'index.php?content=return_order&edit=Y&reference='+code;
}


function confirmExit(){
  swal({
    title:'คุณแน่ใจ ?',
    text:'รายการทั้งหมดจะไม่ถูกบันทึก ต้องการออกหรือไม่ ?',
    type:'warning',
    showCancelButton:true,
    cancelButtonText:'ไม่ใช่',
    confirmButtonText:'ออกจากหน้านี้',
  },
  function(){
    goBack();
  });
}
