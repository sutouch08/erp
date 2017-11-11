//--- store in WEB_ROOT/invent/script/adjust/

function goBack(){
  window.location.href = 'index.php?content=adjust';
}



function goAdd(){
  window.location.href = 'index.php?content=adjust&add=Y';
}


function goEdit(id){
  window.location.href = 'index.php?content=adjust&add=Y&id_adjust='+id;
}


$('#date_add').datepicker({
  dateFormat:'dd-mm-yy'
});
