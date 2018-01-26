function goBack(){
  window.location.href = 'index.php?content=consign_check';
}


function goAdd(id){
  if(id === undefined){
    window.location.href = 'index.php?content=consign_check&add';
  }else{
    window.location.href = 'index.php?content=consign_check&add=Y&id_consign_check='+id;
  }

}
