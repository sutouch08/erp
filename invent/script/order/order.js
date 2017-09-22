// JavaScript Document
function goBack(){
	window.location.href = "index.php?content=order";	
}


function goAdd(){
	window.location.href = "index.php?content=order&add=Y";	
}


function goAddOnline(){
	window.location.href = "index.php?content=order&online=Y&add=Y";	
}

function goEdit(id){
	window.location.href = "index.php?content=order&edit=Y&id_order="+id;
}



function goAddDetail(id){
	window.location.href = "index.php?content=order&add=Y&id_order="+id;	
}





function goViewStock(){
	window.location.href = "index.php?content=order&view_stock=Y";
}

