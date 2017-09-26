function goBack(){
    window.location.href = "index.php?content=prepare";
}





//---- ไปหน้าจัดสินค้า
function goPrepare(id){
    window.location.href = "index.php?content=prepare&process=Y&id_order="+id;
}




//--- ไปหน้ารายการที่กำลังจัดสินค้าอยู่
function viewProcess(){
  window.location.href = "index.php?content=prepare&viewProcess=Y";
}
