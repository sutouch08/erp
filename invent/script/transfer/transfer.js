

function goBack(){
  window.location.href = 'index.php?content=transfer';
}



function goAdd(id){
  if(id != undefined){
    window.location.href = 'index.php?content=transfer&add=Y&id_transfer='+id;
  }else{
    window.location.href = 'index.php?content=transfer&add=Y';
  }
}




//--- สลับมาใช้บาร์โค้ดในการคีย์สินค้า
function goUseBarcode(){
  var id = $('#id_transfer').val();
  window.location.href = 'index.php?content=transfer&add=Y&id_transfer='+id+'&barcode';
}




//--- สลับมาใช้การคื่ย์มือในการย้ายสินค้า
function goUseKeyboard(){
  var id = $('#id_transfer').val();
  window.location.href = 'index.php?content=transfer&add=Y&id_transfer='+id;
}
