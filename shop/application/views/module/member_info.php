<?php include_once("ga.php") ?>
<?php if (empty($_SESSION['id_customer'])): ?>
<CENTER>
	<div class="well col-lg-12" style="margin-top:100px">
		<h3 style="color:red; font-size:30px;font-weight:bold;">access denine !</h3>
		<h3 style="color:red; font-size:22px;font-weight:bold;">Please Login !</h3>
	</div>
</CENTER>
<?php else: ?>

<div class="container main-container head-offset">
	<div class="row featuredPostContainer globalPadding style2">
		<h3 class="section-title style2 text-center header-main"><span>Member Info</span></h3>
		<div class="well">
			<div class="panel" style="padding:10px;margin-bottom:5px">
				<span style="font-size:18px;font-weight: bold;">MEMBER INFO : </span>
			</div>
			
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="row">
						<div class="col-md-1"><label for="fname" >ชื่อ:</label></div>
						<div class="form-group col-md-5">
							<input type="text" class="form-control" id="fname" value="<?php print_r($_SESSION['first_name']) ?>" readonly="">
						</div>
						<div class="col-md-1"><label for="lname" >สกุล:</label></div>
						<div class="form-group col-md-5">
							<input type="text" class="form-control" id="lname" value="<?php print_r($_SESSION['last_name']) ?>" readonly="">
						</div>
					</div>

					<div class="row">
						<div class="col-md-1"><label for="email" >Email:</label></div>
						<div class="form-group col-md-5">
							<input type="text" class="form-control" id="email" value="<?php  print_r(@$_SESSION['email']) ?>" readonly="">
						</div>
						<div class="col-md-1"><label for="tel" >Tel:</label></div>
						<div class="form-group col-md-5">
							<input type="text" class="form-control" id="tel" value="<?php print_r($_SESSION['tel'])  ?>" readonly="">
						</div>
					</div>
					<legend></legend>
					
					<div class="table-responsive">
						<table class="table table-striped table-hover">
							<thead>
								<tr>
									<th>เลขที่</th>
									<th>ตำบล</th>
									<th>อำเภอ</th>
									<th>จังหวัด</th>
									<th>รหัสไปรษณีย์</th>
									<th style="width:150px">ACTION</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($address as $addr): ?>
								<tr>
									<td><?php print_r($addr->address_no) ?></td>
									<td><?php print_r($addr->DISTRICT_NAME) ?></td>
									<td><?php print_r($addr->AMPHUR_NAME) ?></td>
									<td><?php print_r($addr->PROVINCE_NAME) ?></td>
									<td><?php print_r($addr->postcode) ?></td>
									<td>
										<button class="btn btn-xs btn-info" data-toggle="modal" data-target="#edit_addr_modal">แก้ใข</button>
										<button class="btn btn-xs btn-warning">ลบ</button>
									</td>
								</tr>
							<?php endforeach ?>
							</tbody>
						</table>
					</div> 
				</div>			
			</div>
			<div class="panel" style="padding:10px;margin-bottom:5px">
				<span style="font-size:18px;font-weight: bold;">ORDER</span>
			</div>
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover">
							<thead>
								<tr>
									<th>No.</th>
									<th>DATE</th>
									<th>AMOUNT</th>
									<th>STATUS</th>
									<th style="width:150px">ACTION</th>
								</tr>
							</thead>
							<tbody>
							<?php $n = 1 ?>
							<?php foreach ($order_detail as $order): ?>
								<tr>
									<td><?php echo $n ?></td>
									<td><?php print_r($order->date_upd) ?></td>
									<td> <?php print_r($order->total_amount) ?> บาท</td>
									<td><?= 'status here ' ?></td>
									<td>
										<button class="btn btn-xs btn-success" data-toggle="modal" data-target="#view_order_modal">ดูข้อมูล</button>
									</td>
								</tr>
								<?php $n++ ?>
							<?php endforeach ?>
							</tbody>
						</table>
					</div> 

				</div>
			</div>

		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal signUpContent fade" id="edit_addr_modal" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h3 class="modal-title-site text-center"> EDIT INFO </h3>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<div>
						<label for="fname">ชื่อ</label>
						<input name="fname" id="fname" class="form-control input" size="20" placeholder="First Name" type="text">
					</div>
				</div>

				<div class="form-group">
					<div>
						<label for="lname">สกุล</label>
						<input name="lname" id="lname" class="form-control input" size="20" placeholder="Last Name" type="text">
					</div>
				</div>
				<div class="form-group">
					<div>
						<label for="city">จังหวัด</label>
						<input name="city" id="city" class="form-control input" size="20" placeholder="จังหวัด" type="text">
					</div>
				</div>
				<div class="form-group">
					<div>
						<label for="distic">อำเภอ</label>
						<input name="distic" id="distic" class="form-control input" size="20" placeholder="อำเภอ" type="text">
					</div>
				</div>
				<div class="form-group">
					<div>
						<label for="sub_distic">ตำบล</label>
						<input name="sub_distic" class="form-control input" size="20" placeholder="ตำบล" type="text">
					</div>
				</div>
				<div class="form-group">
					<div>
						<label for="postcode">รหัสไปรษณีย์</label>
						<input name="postcode" id="postcode" class="form-control input" size="20" placeholder="รหัสไปรษณีย์" type="text">
					</div>
				</div>

				<div class="form-group">
					<div>
						<label>เพศ : </label>
						<label class="radio-inline">
							<input type="radio" name="male">ชาย</label>
							<label class="radio-inline">
								<input type="radio" name="female">หญิง</label>
							</div>
						</div>
						<!-- <div class="form-group">
							<div>
								<label for="addr">ที่อยุ่</label>
								<textarea name="addr" id="addr" class="form-control input" rows="3"></textarea>
							</div>
						</div> -->

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>

			</div>
		</div>
		<!-- End modal edit address -->

		<!-- Modal -->
		<div class="modal fade" id="view_order_modal" role="dialog">
			<div class="modal-dialog">

				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h3 class="modal-title-site text-center"> ORDER INFO </h3>
					</div>
					<div class="modal-body">
						<p><h4>No.: WO-XXXX-XXXX 
							<span class="label label-primary btn-status">กำลังจัดสินค้า</span>
							<span class="label label-success btn-status">จัดส่งแล้ว</span>
							<span class="label label-warning btn-status">กำลังจัดส่ง</span>
							<span class="label label-danger btn-status">ไม่มีผู้รับ</span></h4>
						</p>
						
						<p><h4>DATE : <?= ($order_detail[0]->date_upd) ?></h4></p>
						<p><h4> <?= "คุณ ".$address[0]->first_name." ".$address[0]->last_name; ?></h4></p>
						<p><h4></h4></p>
						<div class='table-responsive'>
							<table class='table table-bordered'>
								<thead>
									<tr style='font-size: 12px;'>
										<th >ลำดับ</th>
										<th style='width:10%; text-align:center;'>รูป</th>
										<th >บาร์โค้ด</th>
										<th >สินค้า</th>
										<th >ราคา</th>
										<th >จำนวน</th>
										<th >ส่วนลด</th>
										<th >มูลค่า</th>
									</tr>
								</thead>
								<tbody>
									<?php $n = 1; ?>
									<?php $item_qty=0; ?>
									<?php $amount = 0; ?>
									<?php foreach( $order_detail as $item ) : ?>
										<tr>
											<td><?= $n; ?></td>
											<td>
											<img src="<?php echo get_image_path(get_id_cover_image($item->id_product), 3); ?>" alt="img" class="img-responsive">
											</td>
											<td><?= $item->barcode ?></td>
											<td><?= $item->product_reference." ".$item->product_name; ?></td>
											<td><?= $item->product_price;  ?></td>
											<td><?= $item->product_qty;  ?></td>
											<td>
											<?php 
											if($item->reduction_amount=='0.00' && $item->reduction_percent >'0.00'){
												echo $item->reduction_percent."%";
											}else{
												echo number_format($item->reduction_amount);
											}
											?>		
											</td>
											<td><?= number_format($item->total_amount);  ?></td>
											
										</tr>
                      				<?php $n++; ?>
                      				<?php $item_qty += $item->product_qty; ?>
                      				<?php $amount += $item->total_amount;  ?>
          							<?php endforeach; ?> 
									<tr>
										<td colspan='5'></td>
										<td><h4>สินค้าทั้งหมด</h4></td>
										<td ><?= $item_qty; ?></td>
										<td><h4>ชิ้น<h4></td>
									</tr>
									<tr>
										<td colspan='5'></td>
										<td><h4>ราคา</h4></td>
										<td ><?= number_format($amount); ?></td>
										<td><h4>บาท<h4></td>
									</tr>
								</tbody>
							</table>	
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

<?php endif ?>
		<style>
			@keyframes color-animation {
			    0% {
			       background: #ad1457;
			    }
			    10% {
			       background: #F3F781;
			    }
			    20% {
			       background: #FE2E2E;
			    }
			    30% {
			       background: #A9F5F2;
			    }
			    40% {
			       background: #A901DB;
			    }
			    50% {
			       background: #6a1b9a;
			    }
			    60% {
			       background: #F2F2F2;
			    }
			    70% {
			       background: #A9F5E1;
			    }
			    80% {
			       background: #BFFF00;
			    }
			    90% {
			       background: #FA58D0;
			    }
			    100% {
			       background: #bbdefb
			    } 
			 }

			.btn-status {
			   animation: color-animation 10s infinite linear alternate;
			}
			.header-sub{
				color: #7c795d; 
				font-size: 18px; 
				font-weight: normal; 
				line-height:20px; 
				margin-left:20px; 
			}
			
		</style>

