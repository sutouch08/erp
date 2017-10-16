<?php include_once("ga.php") ?>
<div class="container main-container head-offset">
	<div class="row featuredPostContainer globalPadding style2">
		<h3 class="section-title style2 text-center header-main"><span>FORGOT PASSWORD</span></h3>

		<div class="well">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="container">
						<div class="row">
							<div class="col-md-6 col-md-offset-3">
								<div class="panel panel-login">
									<CENTER>
										<img class="img-responsive" src="<?php echo base_url(); ?>shop/assets/ico/warning.ico"></span>
										<span>
											<h3 class="btn-status">ระบบจะแจ้งรหัส Reset Password พร้อมลิ้งเพื่อตั้งค่ารหัสผ่านใหม่ทาง Email ที่ท่านกรอก !</h3>
										</span>
									</CENTER>
									<div class="panel-body">
										<div class="row">
											<div class="col-lg-12">
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon" id="basic-addon1">@</span>
															<input type="text" name="email" id="email" tabindex="1" class="form-control" placeholder="Email" value="">
														</div>
													</div>

													<div class="form-group" style="padding-bottom:40px">
														<div class="row">
															<div class="col-sm-5 col-sm-offset-3">
																<button name="submit" id="submit" class="form-control btn btn-success button-sent-email" onclick="" >ตกลง</button>
															</div>
														</div>
													</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>			
			</div>
		</div>
	</div>
</div>
<script>
	
</script>
<style>
	@keyframes color-animation{
		from { color: red; }
		to { color: yellow; }
	}

	.btn-status {
		animation: color-animation 2s infinite linear alternate;
	}

	.button-sent-email {
		transform-style: preserve-3d;
	}

	.button-sent-email:after {
		top: -100%;
		left: 0px;
		width: 100%;
		padding:8px;
		position: absolute;
		background: #2ecc61;
		border-radius: 3px;
		transform-origin: left bottom;
		transform: rotateX(90deg);
		font-family: FontAwesome;
		content:"\f003 \00a0 \00a0 Send Email";
	}
	.button-sent-email:hover {
		transform-origin: center bottom;
		transform: rotateX(-92deg) translateY(100%);
		-webkit-transition-duration: 0.5s; 
    	transition-duration: 0.5s;
	}

</style>



