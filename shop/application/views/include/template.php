<?php
$this->load->view("include/header"); 
$this->load->view($view);
$this->load->view("include/footer");
?>

<?php //this is session for modal grid ?>
</script>
<div class="modal fade" id="orderGrid" >
	<div class="modal-dialog" id="mainGrid">
		<div class="modal-content">
			<div class="modal-header" style="background-color:#585858">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h5 class="modal-title text-center" id="productCode" style="size:26px;margin-top:10px;font-weight: bold;">
				</h5>
			</div>
			<div class="modal-body" id="orderContent">
				<form class="form-horizontal">
					<!-- <div class="table-responsive" style="width:auto;"> -->
						<table class="table table-bordered table-striped table-highlight"  id="tableOrder">
							<thead >
								<tr id="tableOrder_th">
									<th></th>
								</tr>
							</thead>
							<tbody id="tableOrder_bd"> 

							</tbody>
						</table>
						<!-- </div> -->
					</form>
				</div>
				<div class="modal-footer">
					<!-- <input type="hidden" name="id_product" id="id_product" value="<?php echo $pd->product_id; ?>" /> -->
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" id="addBtn" onClick="addToCart()">Add to cart</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->



<script>
$(document).ready(function(){

	$(window).scroll(function() {
		if ($(this).scrollTop()) {
			$('#promo').show('4000', function() {

			});
		} else {
			$('#promo').hide('4000', function() {

			});
		}
	});

	(function tableWidth(){

		$('#modal').on('show.bs.modal', function () {
			$(this).find('.modal-body').css({
	              width:'auto', //probably not needed
	              height:'auto', //probably not needed 
	              'max-height':'100%'
	        });
		});

	}());


	$("#color_select").change(function(){
		$.ajax({
			url:"<?php echo base_url(); ?>shop/product/fetchSize",
			type:"POST",
			cache:true, 
			data: {
				"color_select" : $(this).val(),
				"id_style": $("#style_id").val()
			}, 
			success: function(rs) { 
				var opt = "";
				var res = $.parseJSON(rs);
				$.each(res, function(key, val){
					opt +="<option value='"+ val +"'>"+val["size_name"]+"</option>"
				});
				$("#size_select").html( opt );
				console.log(res);
			},error: function(e) {
				console.log("error");
			}
		});	
	});

});//document ready

	function addToCart()
	{
		load_in();
		var key = [];
		var value = [];
		var attributes = [];

		var i = 0 ;
		$.each($("#tableOrder_bd").find("tr"), function(){
			key = $(this).find("span").attr('id');
			value = $(this).find("input[name='inputQty[]']").val();   
			if(value==''){ value = 0; }
			var res = key.split("_");
			
		    attributes[i] = {
		    					'id_style':$("#id_style").val(),
		    					'id_color':res[0],
		    					'id_size':res[1],
		    					'qty':value
							};
		    i++;
		});
		// console.log(attributes);
		$.ajax({
			url:"<?php echo base_url(); ?>shop/cart/addToCart",
			type:"POST",
			cache: "false",
			data: {
				'dataChoosed':attributes,
			},
			success: function(rs){
				console.log(rs);
				// if( rs == 'success' )
				// {
				// 	 $('#orderGrid').modal('toggle');
				// 	swal({ title: 'Success', title : 'Add to cart successfully', timer: 2000, type: 'success' });
					
				// }
				// else
				// {
				// 	 $('#orderGrid').modal('toggle');
				// 	swal({ title: 'ไม่สำเร็จ', title : 'เพิ่มสินค้าลงตะกร้าไม่สำเร็จ กรุณาลองใหม่อีกครั้ง', type: 'error' });	
				// }
			},error: function(XMLHttpRequest, textStatus, errorThrown) {
				swal({ title: 'ไม่สำเร็จ', title : 'การเชื่อมต่อกับฐานข้อมูลมีปัณหา กรุณาลองใหม่ !!', type: 'error' });	
				load_out();
			}
		});
	}

	function updateCart()
	{
		var id_cart = $('#id_cart').val();
		$.ajax({
			url:"<?php echo base_url(); ?>shop/cart/getCartQty",
			type: "POST", cache: "false", data:{ "id_cart" : id_cart },
			success: function(rs){
				var rs = $.trim(rs);
				var rs = parseInt(rs);
				if( !isNaN(rs) )
				{
					$("#cartLabel").text(rs);
					$('#cartLabel').css('visibility', 'visible');
					$("#cartMobileLabel").text(rs);
					$('#cartMobileLabel').css('visibility', 'visible');
				}
			},error: function(XMLHttpRequest, textStatus, errorThrown) {
				swal({ title: 'ไม่สำเร็จ', title : 'การเชื่อมต่อกับฐานข้อมูลมีปัณหา กรุณาลองใหม่ !!', type: 'error' });	
				load_out();
			}
		});
	}


	function validQty(el, qty)
	{
		var input = parseInt(el.val());
		el.val(input);
		if( el.val() !== '' && isNaN(input) ){ swal('ตัวเลขไม่ถูกต้อง', 'กรุณาป้อนเฉพาะตัวเลขเท่านั้น', 'warning'); el.val(''); return false; }
		var qty = parseInt(qty);
		if( input > qty)
		{
			swal('สินค้าไม่พอ', 'มีสินค้าในสต็อก '+qty+' เท่านั้น', 'warning');
			el.val('');
		}
	}


	function getOrderGrid(id_style)
	{
		load_in();
		// console.log("id product = "+id_style);
		$.ajax({
			url:"<?php echo base_url(); ?>shop/product/orderGrid",
			type:"POST", 
			cache: "false", 
			data: { "id_style" : id_style },
			success: function(rs){
				load_out();
				// console.log(JSON.parse(rs));
				
				var arr = Object.keys(JSON.parse(rs)).map(function(k) { 
					return JSON.parse(rs)[k] 
				});
				
				$("#productCode").html(arr[0]['code'] +"  _  "+ arr[0]['name']);

				
				var c = [];
				$.each(arr, function(key, value) {
					var x = $.inArray(value['color_name'],c);
					if(x<0){
						c.push(value['color_name']);
					}
				});

				// console.log(c);

				var s = [];
				$.each(arr, function(key, value) {
					var x = $.inArray(value['size_name'],s);
					if(x<0){
						s.push(arr[key]);
					}
				});

				// console.log(s);
				$("#tableOrder_th").html("<th></th>");
				$("#tableOrder_bd").html("");

				
				$.each(c, function( key,value ) 
				{
					$("#tableOrder_th").append("<th style='text-align: center;'>"+value+"</th>");
				});
				// console.log(s);
				$dataAppend = "<tr>";

				$.each(s, function( key,value ) 
				{

					$dataAppend += "<td style='padding-top:6%'>"+value['size_name']+"</td>";
				
					$.each(c, function( k, v ) 
					{
						// $("#tableOrder_bd").append("<td>"+value['size_name']+"</td>");
						if(value['color_name'] == v)
						{

							$dataAppend += "<td ><input type='text' name='inputQty[]'  style='margin-bottom:0px;'><span name='av[]' id='"+value['color_id']+'_'+value['size_id']+"' style='font-size:10px;color:#DA631D'></span></td>";
							// av(id_style,value['id_color'],value['id_size']);
							// $('span[name=av[]')append(av);
						}else{
							$dataAppend += "<td></td>";
						}

					});
					$dataAppend  += "</tr>";
				});
				
				$("#tableOrder_bd").append($dataAppend);
				$("#tableOrder_bd").append("<input class='hidden' name='id_style' id='id_style' value='"+id_style+"' >");

				$("#orderGrid").modal('show');

				load_out();
			},error: function(XMLHttpRequest, textStatus, errorThrown) {
				console.log(errorThrown);
				load_out();
			}
		});
	}

	function av(id,id_color,id_size){
		$.ajax({
			url:"<?php echo base_url(); ?>shop/product/getAvailable_qty",
			type:"POST",
			cache: "false",
			data: {
				"id_style":id,
				"id_color":id_color,
				"id_size":id_size
			},
			success: function(rs){
				// console.log(rs);
				$('#'+id_color+'_'+id_size).html("เหลือ "+rs+" ชิ้น");
				if(rs<=0){
					$('#addBtn').addClass("disabled");
				}else{
					$('#addBtn').removeClass("disabled");
				}
				// console.log(rs);
				return rs;
				},
			error: function(XMLHttpRequest, textStatus, errorThrown){
				console.log(errorThrown);
			}
		});
	}

</script>
<style>
.modal-dialog { /* Width */
	max-width: 100%;
	width: auto !important;
	display: inline-block;
}
.modal-body { 
	max-height: 300px; 
	padding: 10px; 
	overflow-y: auto; 
	-webkit-overflow-scrolling: touch; 
}
.modal{
	text-align: center;
}

th,td  {
	max-width: 100px;
	word-wrap: break-word;
}

input{
	width: calc(100% - 10px);
}

.promo_field{
	z-index:999;
	color:#fff;
	position: absolute;
	bottom:50px;
	font-size: 18px;
	width:100%;
	height:100px;
	opacity:0.4;
	background-color:#2E2E2E;
}
input:-webkit-autofill {
    -webkit-box-shadow: 0 0 0 30px white inset;
}
</style>