
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.16.0/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>shop/assets/plugins/icheck-1.x/icheck.min.js"></script> 

<div class="container main-container head-offset">
	<div class="row featuredPostContainer globalPadding style2">
		<h3 class="section-title style2 text-center header-main"><span>REGISTER</span></h3>
		<div class="well">
			<div class="panel panel-default">
				<div class="panel-body">
					<?php if (isset($regis_status)): ?>
						<div class="form-group well" style="background-color:#BCF5A9">
							<center>
							<h3 style="color:#FF0000">
								<?php echo @$regis_status ?>
							</h3>
							</center>
						</div>
					<?php endif ?>

					<div class="containner" style="margin-top:20px">
						<?php $attributes = array('id' => 'form');
						 echo form_open("shop/Register/register" , $attributes); ?>
						<!-- <form id="form"> -->
						<div class="col-lg-7 col-md-9 col-xs-11 col-lg-offset-3">
							<div class="form-group">
								<input name="userName" id="userName" class="form-control input" value="<?= set_value("userName") ?>" size="20" placeholder="Enter Username" type="text" >
							</div>

							<div class="form-group">
								<div>
									<input name="email" id="email" class="form-control input" value="<?= set_value("email") ?>" placeholder="Enter Email" type="email">
								</div>
							</div>

							<div class="form-group">
								<div>
									<input name="password" id="cpassword" class="form-control input" placeholder="Password" type="password" >
								</div>
							</div>

							<div class="form-group">
								<div>
									<input name="re_password" id="re_password" class="form-control input" placeholder="Confirm Password" type="password" >
								</div>
							</div>

							<legend></legend>

							<div class="form-group">
								<div>
									<label for="fname">ชื่อ</label>
									<input name="fname" id="fname" class="form-control input" value="<?= set_value("fname") ?>" placeholder="First Name" type="text">
								</div>
							</div>

							<div class="form-group">
								<div>
									<label for="lname">สกุล</label>
									<input name="lname" id="lname" class="form-control input" value="<?= set_value("lname") ?>" placeholder="Last Name" type="text">
								</div>
							</div>

							<div class="form-group" style="margin-top:35px;margin-bottom:35px">
									<div>
										<label>เพศ :  </label>
										<label class="radio-inline">
										<label for="male">
										  <input  type="radio" id="male" value="m" name="sex" />
										  ชาย
										</label>
										<label for="female">
										  <input  type="radio" id="female" value="f" name="sex"/>
										  หญิง
										</label>
										<label for="sex" class="error"></label>

									</div>

									<div class="form-group" style="margin-top:20px">
										<div>
											<label>วันเกิด </label>
											<div class="input-group date" data-provide="datepicker">
												<input type="text" id="birthDate" name="birthDate" class="form-control datepicker" value="<?= set_value("birthDate") ?>">
												<div class="input-group-addon">
													<span class="fa fa-calendar"></span>
												</div>
											</div>
										</div>
										<label for="birthDate" class="error"></label>
									</div>

									<div class="form-group">
										<div>
											<label for="tel">โทร</label>
											<input name="tel" id="tel" class="form-control input" value="<?= set_value("tel") ?>" placeholder="tel" type="tel">
										</div>
									</div>

									<div class="form-group">
										<div>
											<label for="addr">ที่อยุ่</label>
											<textarea name="addr" id="addr" class="form-control input" rows="3" ></textarea>
										</div>
									</div>

									<div class="form-group">
										<div>
											<label >จังหวัด</label>
											<select name="Proviance" id="Proviance" style="width:150px">
												<option value=\"0\" selected=\"selected\">---เลือกจังหวัด---</option>
											</select>
											<input type="text" name="ProID" id="ProID" hidden="" />
										</div>
										<label for="Proviance" class="error"></label>
									</div>

									<div class="form-group">
										<div>
											<label >อำเภอ</label>
											<select name="District" id="District" style="width:150px">
												<option value=\"0\" selected=\"selected\">---เลือกจังอำเภอ---</option>
											</select>
											<input type="text" name="DisID" id="DisID" hidden="" />
										</div>
									</div>

									<div class="form-group">
										<div>
											<label for="Subdistrict">ตำบล</label>
											<select name="Subdistrict" id="Subdistrict" style="width:150px">
												<option value=\"0\" selected=\"selected\">---เลือกจังตำบล---</option>
											</select>

											<input type="text" name="SubID" id="SubID" hidden="" />
										</div>
									</div>

									<div class="form-group">
										<div>
											<label for="Postcode">รหัสไปรษณีย์</label>
											<select name="Postcode" id="Postcode" style="width:150px">
												<option value=\"0\" selected=\"selected\">---เลือกรหัสไปรษณีย์---</option>
											</select>
											<input type="text" name="PostID" id="PostID" hidden="" />
										</div>
									</div>
									
									
									<div class="row" style="margin-top:50px">
										<button class="btn btn-success pull-right" value="submit" id="submit" > สมัคร </button>
									</div>
									<?php echo form_close(); ?>
								</div>
							</div>
						</div>			
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
	// JavaScript Document
	$(document).ready(function(){

	// $(".datepicker").datepicker();
//************ for address all input ****************

	$("#Proviance").change(function(){
		$.ajax({
			url:"register/getdata",
			type:"GET",
			cache:true, 
			data: {"ID" : $(this).val(),"TYPE" : "District"}, 
			dataType: "JSON", 
			success: function(jd) { 
				var opt="";
				$.each(jd, function(key, val){
					opt +="<option value='"+ val["AMPHUR_ID"] +"'>"+val["AMPHUR_NAME"]+"</option>"
				});
							$("#District").html( opt );
			},error: function(e) {
				console.log("error");
				}
			});	
		$("#ProID").val($(this).val()); 
	});
	
	$("#District").change(function(){
		$("#Subdistrict").empty();
		$("#Postcode").empty();
		$("#SubID").val("");
		$("#PostID").val("");
		$.ajax({
			url: "Register/getData",
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
			}
		});
		$("#DisID").val($(this).val());
	});
	
	$("#Subdistrict").change(function(){
		$("#PostID").val("");
		$.ajax({
			url: "Register/getData",
			type: "GET",
			data: ({ID : $("#District").val(),TYPE : "Postcode"}),
			dataType: "JSON",
			success: function(jd) {
				var opt="<option value=\"0\" selected=\"selected\">---เลือกรหัสไปรษณีย์---</option>";
				$.each(jd, function(key, val){
					opt +="<option value='"+ val["POST_CODE"] +"'>"+val["POST_CODE"]+"</option>"
				});
				$("#Postcode").html( opt );
			}
		});
		$("#SubID").val($("#Subdistrict").val());
	});
	
	$("#Postcode").change(function(){
		$("#PostID").val($(this).val());
	});
});

// ******************************************
(function(){
	$.ajax({
		url:"register/getdata",
		type:"GET",
		cache:false, 
		data:{ "TYPE" : "Proviance"},
		success: function(jd){
			// console.log(jd);
				var opt="<option value=\"0\" selected=\"selected\">---เลือกจังหวัด---</option>";
				$.each(JSON.parse(jd), function(key, val){
					opt +="<option value='"+ val["PROVINCE_ID"] +"'>"+val["PROVINCE_NAME"]+"</option>"
				});

				$("#Proviance").html( opt );

			},error:function(e){
				console.log("error");
			}
		});	
}()); 


jQuery(function ($) {
    $('#form').validate({
        rules: {
            userName: {
                required: true,
                minlength: 4,
                maxlength: 30,
            },
            email: {
                required: true,
                minlength: 6,
                email: true,
            },
            password:{
            	required:true,
            	minlength:8,
            	maxlength:30,
            },
            re_password:{
            	equalTo:"#cpassword",
            },
            fname:{
            	required:true,
            	minlength:2,
            	maxlength:30,
            	lettersonly: true,
            },
            lname:{
            	required:true,
            	minlength:3,
            	maxlength:40,
            	lettersonly: true,
            },
            sex: {
                required: true,
            },
            birthDate:{
            	required: true,
            },
            tel:{
            	required: true,
            	minlength:9,
            	maxlength:10,
            	// phoneTH:true,
            },
            addr:{
            	required: true,
            },
            Proviance:{
            	selectcheck: true,
            }

        },
        messages:{
            userName: {
                required: "กรุณากรอก Username !",
                minlength: "Username ต้อมีความยาวม 4 ตัวอักษรขึ้นไป",
                maxlength: "Username ต้องมีความยาวไม่เกิน 30 ตัวอักษรเท่านั้น",
            },
            email: {
                required: "กรุณากรอก Email !",
                minlength: "Email ต้องมีความยาว 8 ตัวอักษรขึ้นไป",
                email: "ไม่พบ Email Address",
            },
            password:{
            	required:"กรุณากรอก Password !",
            	minlength:"Password ต้องมีความยาวอย่างน้อย 8 ตัวอักษร",
            	maxlength:"ต้องมีความย่วไม่เกิน 30 ตัวอักษร",
            },
            re_password:{
            	// required:"กรุณากรอก Password !",
            	// minlength:"Confirm Password ต้องมีความยาวอย่างน้อย 8 ตัวอักษร",
            	// maxlength:"Confirm Password ต้องมีความย่วไม่เกิน 30 ตัวอักษร",
            	equalTo:"กรุณากรอกข้อมูล Confirm Password ให้จรงกับ Password",
            },
            fname:{
            	required:"กรุณากรอกชื่อ",
            	minlength:"ชื่อต้องมีความยาว 2 ตัวอักษรขึ้นไป",
            	maxlength:"ความยาวต้องไม่เกิน 30 ตัวอักษร",
            	lettersonly:"ชื่อต้องเป็นอักษรเท่านั้น",
            },
            lname:{
            	required:"กรุณากรอกนามสกุล",
            	minlength:"นามสกุลต้องมีความยาว 2 ตัวอักษรขึ้นไป",
            	maxlength:"ความยาวต้องไม่เกิน 40 ตัวอักษร",
            	lettersonly:"นามสกุลต้องเป็นอักษรเท่านั้น",
            },
            sex:{
            	required:"กรุณาเลือกเพศ",
            },
            birthDate:{
            	required:"กรุณากรอกวัน/เดือน/ปี ที่เกิด",
            },
            tel:{
            	required:"กรุณากรอกเบอร์โทร",
            	phoneTH:"กรุณากรอกเบอร์โทรเป็นตัวเลข",
            	minlength:"เบอร์ดทรต้องมีความยาวอย่างน้อย 9 ตัวอักษร",
            	maxlength:"เบอร์โทรต้องมีความยาวไม่เกิน 10 ตัวอักษร",
            },
            addr:{
            	required:"กรุณากรอกที่อยู่",
            },
            Proviance:{
            	selectcheck:"กรุณาเลือกจังหวัด",
            }
        },

    });
});

jQuery.validator.addMethod("lettersonly", function(value, element) {
  return this.optional(element) || /^[a-z]+$/i.test(value);
}, "Letters only please"); 

jQuery.validator.addMethod('phoneTH', function(phone_number, element) {
  return this.optional(element) || phone_number.length > 9 &&
  phone_number.match(/^(\(?(0)[1-9]{1}\d{1,4}?\)?\s?\d{3,4}\s?\d{3,4})$/);
  }, 'Please specify a valid phone number'
);

jQuery.validator.addMethod('selectcheck', function (value) {
        return (value != '0');
    }, "please select");


</script>















