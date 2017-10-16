<style>
body .modal-dialog { /* Width */
	max-width: 100%;
	width: auto !important;
	display: inline-block;
}
.modal{
	text-align: center;
}

</style>

<link href="<?php echo base_url(); ?>shop/assets/css/product_detail.css" rel="stylesheet">
<?php if( $product !== FALSE ) : ?>
	
	<?php $pd = $product[0] ?>
	<section class="section-product-info" style="background-color:white;">
		<div class="container-1000 container   main-container product-details-container">
			<div class="row">
				<!-- left column -->
				<?php if( isset( $images ) && $images !== FALSE ) : ?>            
					<!--/ left column end -->
					<div class="col-lg-8 col-md-8 col-sm-7 col-xs-12" style="margin-bottom:20px">
						<section class="gallery">
							<div class="carousel">
								<?php $i = 1; ?>
								<?php foreach ($images as $img): ?>
									<input type="radio" id="image<?= $i ?>" name="gallery-control" >
									<?php $i++; ?>
								<?php endforeach ?>

								<input type="checkbox" id="fullscreen" name="gallery-fullscreen-control"/>

								<div class="thumbnails">
									<div class="slider">
										<div class="indicator"></div>
									</div>
									<?php $j = 1; ?>
									<?php foreach ($images as $img): ?>
										<label for="image<?= $j ?>" class="thumb" style="background-image: url('<?php echo get_image_path(@$img->id, 4); ?>');">
										</label>
										<?php $j++; ?>
									<?php endforeach ?>
								</div>

								<div class="wrap">
									<?php $o = 1; ?>
									<?php foreach ($images as $img): ?>
										<figure>
											<label for="fullscreen">
												<img src="<?php echo get_image_path(@$img->id, 4); ?>" alt="image<?= $o ?>"/>
											</label>
										</figure>
										<?php $o++; ?>
									<?php endforeach ?>
								</div> <!-- warp -->
							</div> <!-- carousel -->
						</section>
					</div>

				<?php endif; ?>            
				<!-- left column -->
				<!-- end part of image   -->


				<div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
		<!-- <pre>
		<?php 
		print_r($pd);
		 ?>
		</pre> -->
		<div class="product-details-info-wrapper">
			<h2 class="product-details-product-title"> <?php echo $pd->style_code; ?></h2>
			<span><strong><?php echo $pd->style_name; ?></strong></span>

			<div class="price">
				<?php if(  $pd->discount_percent > 0 || $pd->discount_amount > 0  ) : ?>
					<span class="old-price"><?php echo number_format($pd->product_price,2,'.',''); ?>  <?php echo getCurrency(); ?></span>
				<?php endif; ?><br/>
				<span><?php echo sell_price($pd->product_price, $pd->discount_amount,$pd->discount_percent); ?><?php echo getCurrency(); ?></span> 
			</div>

			<!--  *************************************************************  -->
			<!--  *********************** show on web *************************  -->

			<!-- part  of color-->
			<?php 	$colors 	= get_product_colors($pd->style_id); ?>


			<div class="product-details-product-color hidden-xs">
				<span class="selected-color">

					<strong>Color </strong> 
					<?php if( $colors !== FALSE ) : ?> 

						<?php 
						$id 	= [];
						$color  = [];	
						foreach ($colors as $c) {
							if (!in_array($c->id,$id)) {
								array_push($id,$c->id);
								array_push($color,["color_name"=>$c->name,"code_color"=>$c->code,"id_color"=>$c->id]);
								}//if
							}//foreach

							?>

							<?php $i = count($color);?>   
							<?php 	foreach( $color as $c ) : ?>
								<span class="color-value" style="padding-right:10px; display:inline-block;color:<?php print_r($c['code_color'])?>"><?php print_r($c['color_name']); ?></span>
								<?php if( $i >1 ) : ?> |  <?php endif; ?>
								<?php $i--; ?> 
							<?php 	endforeach; ?>                   
						<?php endif; ?>                                
					</span>
				</div>

				<!-- part  of size-->

				<?php 	$sizes	= get_product_sizes($pd->style_id); ?>
				 
				<div class="product-details-product-color hidden-xs">
					<span class="selected-color">
						<strong>Size </strong> 
						<?php if( $sizes !== FALSE ) : ?>  
							
							<?php 
							$id 	= [];
							$size  = [];	

							foreach ($sizes as $s) {
								if (!in_array($s->id,$id)) {
									array_push($id,$s->id);
									array_push($size,["size_id"=>$s->id,"size_name"=>$s->name]);
								}//if
							}//foreach
						
							?>   
							<?php $i = count($size) ;  ?>
							<?php foreach( $size as $s ) : ?>
								<span class="color-value" style="padding-right:10px; display:inline-block">
									<?php echo $s['size_name']; ?>
								</span><?php if( $i >1 ) : ?>|  <?php endif; ?>
							<?php $i--; ?>                                
							<?php 	endforeach; ?>                                
						<?php endif; ?>                                
					</span>
				</div>


				<!--  ***************************************************************  -->
				<!--  ********************** show on mobile *************************  -->

				<?php 	$colors 	= get_product_colors($pd->style_id); ?>
				<div class="product-details-product-color row-cart-actions clearfix hidden-lg hidden-md hidden-sm">
					<form>
						<strong>Select Color</strong> 
						<?php if( $colors !== FALSE ) : ?> 
							<?php 
							$id 	= [];
							$color  = [];	
							foreach ($colors as $c) {
								if (!in_array($c->id,$id)) {

									array_push($id,$c->id);
									array_push($color,["color_name"=>$c->name,"code_color"=>$c->code,"id_color"=>$c->id]);
								}//if
							}//foreach
							?>  <div class="form-group">
								<select class="form-control" id="color_select">
									<?php 	foreach( $color as $c ) : ?>
										<option value="<?php print_r($c['id_color']); ?>"><?php print_r($c['color_name']); ?></option>

									<?php 	endforeach; ?>  
								</select>
							</div>                 
						<?php endif; ?>                                

						<!-- part  of size-->

						<div class="product-details-product-color hidden-lg hidden-md hidden-sm">

							<strong>Select Size </strong>
							<div class="form-group">
								<select class="form-control" name="size_select" id="size_select">

								</select>
							</div>                      
						</div>
					</form>
				</div>
				<div class="row row-cart-actions clearfix hidden-xs" style="margin-top: 20px">
					<div class="col-sm-12 "><button type="button" class="btn btn-block btn-dark" onClick="getOrderGrid(<?php echo $pd->style_id; ?>)">ต้องการสั่งซื้อ</button></div>
				</div>
				<div class="row row-cart-actions clearfix hidden-md hidden-lg hidden-sm" style="margin-top: 20px">
					<div class="col-sm-12 "><button type="button" class="btn btn-block btn-dark" >ต้องการสั่งซื้อ</button></div>
				</div>

				<!-- <div class="clear"></div> -->

				<div class="product-share clearfix">
					<p> SHARE </p>
					<div class="socialIcon">
						<a name="fb_share" data-href="#"> <i class="fa fa-facebook"></i></a>
						<a class="twitter-share-button"  href="๒"><i class="fa fa-twitter"></i></a>
						<a href="#"> <i class="fa fa-google-plus"></i></a>
						<a href="#"> <i class="fa fa-pinterest"></i></a></div>
						<br>
					</div>
				</div>
			</div>

			<!--/ right column end -->

		</div>
		<!--/.row-->
		<!--  <div style="clear:both"></div> -->
	</div>
	<!-- /.product-details-container -->
</section>
<?php endif; ?>



