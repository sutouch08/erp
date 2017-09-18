<div class="row">
	<div class="col-sm-12 margin-top-15 margin-bottom-15">
    	<button type="button" class="btn btn-sm btn-default" id="btn-edit-discount" onclick="showDiscountBox()">แก้ไขส่วนลด</button>
        <button type="button" class="btn btn-sm btn-primary hide" id="btn-update-discount" onClick="getApprove('discount')">บันทึกส่วนลด</button>
        <button type="button" class="btn btn-sm btn-default" id="btn-edit-price" onClick="showPriceBox()">แก้ไขราคา</button>
        <button type="button" class="btn btn-sm btn-primary hide" id="btn-update-price" onClick="getApprove('price')">บันทึกราคา</button>
    </div>
</div>


<?php include 'include/validate_credentials.php'; ?>

<script>
function showPriceBox(){
	$(".price-label").addClass('hide');
	$(".price-box").removeClass('hide');
	$("#btn-edit-price").addClass('hide');
	$("#btn-update-price").removeClass('hide');	
}

function showDiscountBox(){
	$(".discount-label").addClass('hide');
	$(".discount-box").removeClass('hide');
	$("#btn-edit-discount").addClass('hide');
	$("#btn-update-discount").removeClass('hide');	
}

$(document).ready(function(e) {
	$(".discount-box").numberOnly();
    $(".discount-box").keyup(function(e) {
		var id = $(this).attr('id').split('_');
		var id = id[1];
		var price = $("#price_"+id).val();
		var discount = $(this).val();
		var disc = discount.split('%');
		if( disc.length > 1 ){
			if( parseFloat( disc[0] ) > 100 ){
				swal("ส่วนลดไม่ถูกต้อง");
				$(this).val('');
			}
		}else{
			if( isNaN( parseFloat(disc[0] ) ) || parseFloat( disc[0] ) > parseFloat(price) ){
				swal("ส่วนลดไม่ถูกต้อง");
				$(this).val('');
			}
		}
	});
});


$(document).ready(function(e) {
	$(".price-box").numberOnly();
    $(".price-box").keyup(function(e) {
		var id = $(this).attr('id').split('_');
		var id = id[1];
		var oldprice = parseFloat($("#price-label-"+id).val());
		var price = parseFloat( $(this).val() );
		
		if( price < 0 ){
			swal("ราคาไม่ถูกต้อง");
			$(this).val("");
		}
	});
});

function updateDiscount(){
	var disc = [];
	disc.push( {"name" : "id_order", "value" : $("#id_order").val() } ); //---- id_order
	$(".discount-box").each(function(index, element) {
        var attr = $(this).attr('id').split('_');
		var id = attr[1];
		var name = "discount["+id+"]";
		var value = $(this).val();
		disc.push( {"name" : name, "value" : value }); //----- discount each row
    });	
	$.ajax({
		url:"controller/orderController.php?updateEditDiscount",
		type:"POST", cache:"false", data: disc,
		success: function(rs){
			console.log(rs);
		}
	});						
}


function updatePrice(){
	var price = [];
	
	price.push( { "name" : "id_order", "value" : $("#id_order").val() } );
	$(".price-box").each(function(index, element) {
        var attr = $(this).attr('id').split('_');
		var id = attr[1];
		var name = "price["+id+"]";
		var value = $(this).val();
		price.push( {"name" : name, "value" : value });
    });
	$.ajax({
		url:"controller/orderController.php?updateEditPrice",
		type:"POST", cache:"false", data: price,
		success: function(rs){
			console.log(rs);
		}
	});
}


function getApprove(tab){
	//--- แก้ไขส่วนลด id_tab = 35
	//--- แก้ไขราคา id_tab = 65
	if( tab == 'discount' ){
		var initialData = {
			"title" : 'อนุมัติแก้ไขส่วนลด',
			"id_tab" : 35,  //--- แก้ไขวันที่เอกสาร
			"field" : "", //--- add/edit/delete ถ้าอันไหนเป็น 1 ถือว่ามีสิทธิ์ /// ถ้าต้องการเฉพาะให้ระบุเป็น  add, edit หรือ delete
			"callback" : function(){ updateDiscount();  }
		}
	}
	
	if( tab == 'price' ){
		var initialData = {
			"title" : 'อนุมัติแก้ไขราคาขาย',
			"id_tab" : 35,  //--- แก้ไขวันที่เอกสาร
			"field" : "", //--- add/edit/delete ถ้าอันไหนเป็น 1 ถือว่ามีสิทธิ์ /// ถ้าต้องการเฉพาะให้ระบุเป็น  add, edit หรือ delete
			"callback" : function(){ updatePrice();  }
		}
	}	
	showValidateBox(initialData);
}
	
</script>