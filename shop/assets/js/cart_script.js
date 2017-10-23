  function cal_trans() {
    load_in();
    console.log("calulate");
    $.ajax({
      url:"<?php echo base_url(); ?>shop/cart/calculate",
      type:"POST",
      cache: "false", 
      data:{ "user_name" : "user"},
      success: function(rs){
        console.log(rs);
        load_out();
      }
    });
  }

  function deleteCartRow(id_pd)
  {
   // console.log("del"+id_pd);
   var base_url = window.location.origin;
   $.ajax({
    url:base_url+"/invent/shop/cart/deleteCartProduct",
    type:"POST", cache: "false", 
    data:{ "id_pd" : id_pd },
    success: function(rs){
       console.log("res= "+rs);
       var rs = $.trim(rs);
       if( rs == 'success' )
       {
        console.log("del success");
        $("#row_"+id_pd).animate({opacity : 0}, 1000, function(){ $('#row_'+id_pd).remove(); recalCart(); });
        $("#m-row_"+id_pd).animate({opacity : 0}, 1000, function(){ $('#m-row_'+id_pd).remove(); recalCart(); });
        setTimeout( window.location.reload(), 2000);
       }
    }
  });
 }

 function reloadCart()
 {
   load_in();
   $('#body').animate({opacity: 0.1}, 1000, function(){ window.location.reload(); });	
 }

 function decreaseQty(id_pd, min_qty)
 {
   // console.log("decreaseQty"+id_pd);
   var qty = parseInt(removeCommas($('#Qty_'+id_pd).text()));
   var min_qty = parseInt(min_qty);
   if( qty > min_qty)
   {
    qty -= 1;
    $('#Qty_'+id_pd).text(qty);
    $("#mQty_"+id_pd).text(qty);
    updateCart_Qty(id_pd, qty);
  }		
}

function increaseQty(id_pd, max_qty)
{
 // console.log("increaseQty"+id_pd);
 var qty = parseInt(removeCommas($('#Qty_'+id_pd).text()));
 var max_qty = parseInt(max_qty);
  if( qty < max_qty )
  {
    qty += 1 ;
    $('#Qty_'+id_pd).text(qty);
    $("#mQty_"+id_pd).text(qty);
    updateCart_Qty(id_pd, qty);
  }else{
    swal("สินค้ามีไม่พอจำหน่าย");
  }

}

function updateCart_Qty(id_pd, qty)
{
  // console.log("updateCart"+id_pd);
	var id_cart = $('#id_cart').val();
  var base_url = window.location.origin;
	$.ajax({
		url:base_url+"/invent/shop/cart/updateCart",
    type:'POST', cache:'false', data:{ "id_pd" : id_pd, "qty" : qty },
    success: function(rs){
     var rs = $.trim(rs);
     if( rs != '' && rs != 'fail' )
     {
       window.location.reload();
     }else{
      console.log("update false!")
     }
  }
});
}


$(document).ready(function(){

var base_url = window.location.origin;
(function getProviance(){
   $.ajax({
    url:base_url+"/invent/shop/register/getdata",
    type:"GET",
    cache:true, 
    data: {"TYPE" : "Proviance"}, 
    dataType: "JSON", 
    success: function(jd) {
      // console.log(jd);
      var opt="<option value=\"0\" selected=\"selected\">---เลือกจังจังหวัด---</option>";
      $.each(jd, function(key, val){
        opt +="<option value='"+ val["PROVINCE_ID"] +"'>"+val["PROVINCE_NAME"]+"</option>"
      });
      $("#Proviance").html( opt );
    },error: function(e) {
      console.log("Proviance error");
    }
  }); 
}());
//************ for address all input ****************

$("#Proviance").change(function(){
  console.log($(this).val());
  $.ajax({
    url:base_url+"/invent/shop/register/getdata",
    type:"GET",
    cache:true, 
    data: {"ID" : $(this).val(),"TYPE" : "District"}, 
    dataType: "JSON", 
    success: function(jd) {
      console.log(jd);
      // $("#District").empty();
      // $("#Subdistrict").empty();
      // $("#Postcode").empty();
      $("#DisID").val("");
      $("#SubID").val("");
      $("#PostID").val(""); 

      var opt="<option value=\"0\" selected=\"selected\">---เลือกอำเภอ---</option>";
      $.each(jd, function(key, val){
        opt +="<option value='"+ val["AMPHUR_ID"] +"'>"+val["AMPHUR_NAME"]+"</option>"
      });
      $("#District").html( opt );
    },error: function(e) {
      console.log(" Proviance error");
    }
  }); 
  $("#ProID").val($(this).val()); 
});

$("#District").change(function(){
  // $("#Subdistrict").empty();
  // $("#Postcode").empty();
  $("#SubID").val("");
  $("#PostID").val("");
  $.ajax({
    url:base_url+"/invent/shop/Register/getData",
    global: false,
    type: "GET",
    data: ({ID : $(this).val(),TYPE : "Subdistrict"}),
    dataType: "JSON",
    async:false,
    success: function(jd) {
      var opt="<option value=\"0\" selected=\"selected\">---เลือกตำบล---</option>";
      $.each(jd, function(key, val){
        opt +="<option value='"+ val["DISTRICT_ID"] +"'>"+val["DISTRICT_NAME"]+"</option>"
      });
      $("#Subdistrict").html( opt );
    },error: function(e) {
      console.log("District error");
    }
  });
  $("#DisID").val($(this).val());
});

$("#Subdistrict").change(function(){
  $("#PostID").val("");
  $.ajax({
    url:base_url+"/invent/shop/Register/getData",
    type: "GET",
    data: ({ID : $("#District").val(),TYPE : "Postcode"}),
    dataType: "JSON",
    success: function(jd) {
      var opt="<option value=\"0\" selected=\"selected\">---เลือกรหัสไปรษณีย์---</option>";
      $.each(jd, function(key, val){
        opt +="<option value='"+ val["POST_CODE"] +"'>"+val["POST_CODE"]+"</option>"
      });
      $("#Postcode").html( opt );
    },error: function(e) {
      console.log(" Subdistrict error");
    }
  });
  $("#SubID").val($("#Subdistrict").val());
});

  $("#Postcode").change(function(){
    $("#PostID").val($(this).val());
  });
});

function removeFile()
{
  $("#previewImg").html('');
  $("#block-image").css("opacity","0");
  $("#btn-select-file").css('display', ''); 
  $("#image").val('');
}

function selectFile()
{
  $("#image").click();  
}

function readURL(input) 
{
 if (input.files && input.files[0]) {
  var reader = new FileReader();
    reader.onload = function (e) {
      $('#previewImg').html('<img id="previewImg" src="'+e.target.result+'" width="150px" alt="รูปสลิปของคุณ" />');
      console.log(input.files[0]);
  }
  reader.readAsDataURL(input.files[0]);

  }
}

$("#image").change(function(){
  if($(this).val() != '')
  {
    var file    = this.files[0];
    var name    = file.name;
    var type    = file.type;
    var size    = file.size;
    if(file.type != 'image/png' && file.type != 'image/jpg' && file.type != 'image/gif' && file.type != 'image/jpeg' )
    {
      swal("รูปแบบไฟล์ไม่ถูกต้อง", "กรุณาเลือกไฟล์นามสกุล jpg, jpeg, png หรือ gif เท่านั้น", "error");
      $(this).val('');
      return false;
    }
    if( size > 2000000 )
    { 
      swal("ขนาดไฟล์ใหญ่เกินไป", "ไฟล์แนบต้องมีขนาดไม่เกิน 2 MB", "error"); 
      $(this).val(''); 
      return false;
    }
    readURL(this);
    $("#btn-select-file").css("display", "none");
    $("#block-image").animate({opacity:1}, 1000);
  }
});