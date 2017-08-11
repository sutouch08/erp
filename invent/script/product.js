function goBack(){
	window.location.href = "index.php?content=product";
}


function getSearch(){
	$("#searchForm").submit();	
}

$(".search-box").keyup(function(e) {
    if( e.keyCode == 13 ){
		getSearch();
	}
});