// JavaScript Document
function goBack(){
	window.location.href = "index.php?content=order_transform";
}


function goAdd(){
	window.location.href = "index.php?content=order_transform&add=Y";
}



function goEdit(id){
	window.location.href = "index.php?content=order_transform&edit=Y&id_order="+id;
}



function goAddDetail(id){
	window.location.href = "index.php?content=order_transform&add=Y&id_order="+id;
}


function unClose(id){
	$.ajax({
		url:"controller/transformController.php?unClose&id_order="+id,
		type:'GET',
		cache:false,
		success:function(rs){
			if(rs == 'success'){
				swal({
					title:'Success',
					text:'Unclose successful',
					type:'success',
					timer:1000
				});

				setTimeout(function(){
					window.location.reload();
				}, 1200);
			}else{
				swal({
					title:'Failed',
					type:'error'
				});
			}
		}
	})
}
