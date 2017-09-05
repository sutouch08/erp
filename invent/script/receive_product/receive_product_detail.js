// JavaScript Document
function printReceived(){
	var id = $("#id_receive_product").val();
	var center = ($(document).width() - 800) /2;
	if( !isNaN( parseInt( id ) ) )	{
		window.open("print/receive_product/printReceived.php?id_receive_product="+id, "_blank", "width=800, height=900, left="+center+", scrollbars=yes");	
	}
}