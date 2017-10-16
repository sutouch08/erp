
<!-- <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.16.0/jquery.validate.js"></script> -->

<div class="container main-container headerOffset" id="body">
  <div class="row">
    <div class="col-lg-9 col-md-9 col-sm-7 col-xs-5 text-center-xs hidden-sm hidden-xs">
      <h1 class="section-title-inner"><span><i class="fa fa-shopping-basket"></i> ตะกร้าสินค้า </span></h1>
    </div>
  </div>
  <?php $total_discount = 0; $total_price = 0; $total_amount = 0; ?>
  <div class="row">
    <div class="col-lg-9 col-md-9 col-sm-7  hidden-sm hidden-xs">
      <div class="row userInfo">
        <div class="col-xs-12 col-sm-12">
          <?php if (empty($item_in_cart)): ?>
            <div class="well text-center" style="margin-top:10%;margin-left:10%">
              <h1 style="color:red">ไม่มีสินค้าในตระกร้า</h1>
            </div>
          <?php endif ?>
          <?php if( isset( $item_in_cart ) && $item_in_cart !== FALSE ) : ?>	  
            <div class="cartContent w100">
              <table class="cartTable table-responsive" id="cart-table" style="width:100%">
                <tbody>
                  <tr class="CartProduct cartTableHeader">
                    <td style="width:20%">สินค้า</td>
                    <td style="width:20%"></td>
                    <td style="width:15%">จำนวน</td>
                    <td style="width:10%">ลด/ชิ้น</td>
                    <td style="width:10%">ลด(%)/ชิ้น</td>
                    <td style="width:10%">ส่วนลดรวม</td>
                    <td style="width:10%">ราคารวม</td>
                    <td style="width:5%;" class="delete">&nbsp;</td>
                  </tr>
                  
                  <?php foreach( $item_in_cart as $item ) : ?>	

                    <?php 
                    
                    $available_qty = apply_stock_filter($this->product_model->getAvailableQty($item->id)); 

                    ?>									
                    <tr class="CartProduct" id="row_<?= $item->id; ?>" style="font-size:12px;">
                      <td >
                        <div>
                          <img src="<?= get_image_path( get_id_image($item->id), 2); ?>" alt="img">
                        </div>
                      </td>
                      <td>
                        <div class="price">
                          <strong>
                            <h4>
                             <?= $item->code."-".$item->color_code."-".$item->size_name; ?>
                           </h4>
                         </strong>
                         <h5><?= $item->name; ?></h5>
                       </div>
                       <?php if( $item->discount_percent > 0 || $item->discount_amount > 0) : ?>
                        <span class="old-price"><?php echo number_format($item->price, 2, '.', '') ?>  <?= getCurrency(); ?></span>
                      <?php endif; ?>
                      <span><br>
                        <strong><h3><?php $sell_price =  sell_price($item->price, $item->discount_amount,$item->discount_percent); ?><?= number_format( $sell_price); ?> <?= getCurrency(); ?></h3></strong>
                      </span>  
                    </td>
                    <td >
                     <div class="input-group" style="">
                      <span class="input-group-btn">
                        <button class="btn btn-xs decrease-btn" type="button"  onClick="decreaseQty(<?= $item->id; ?>, 1)"><i class="fa fa-minus"></i></button>
                      </span>
                      <span id="Qty_<?= $item->id; ?>" class="form-control" style="text-align:center; height:36px;">
                        <?= $item->qty; ?>  
                      </span>
                      <span class="input-group-btn">
                        <button class="btn btn-xs increase-btn" type="button" onClick="increaseQty(<?= $item->id; ?>, <?= $available_qty; ?>)"><i class="fa fa-plus"></i>
                        </button>
                      </span>
                    </div><!-- /input-group -->
                    <span class="stock-label">คงเหลือ <?= $available_qty ?>  ในสต็อก</span>
                  </td>
                  <?php 
                  $discount = number_format((($item->price*($item->discount_percent/100))+$item->discount_amount)*$item->qty);
                  $total_price = number_format(($item->qty*$item->price)-$discount); 
                  ?>
                  <td style="color:#1a1aff;font-size:18;font-weight:700"><?= number_format($item->discount_amount, 2); ?> <?= getCurrency(); ?></td>
                  <td style="color:#1a1aff;font-size:18;font-weight:700"><?= number_format($item->discount_percent, 2);  ?> % </td>
                  <td style="color:red;font-size:18;font-weight:700">
                   <?= $discount; ?> <?= getCurrency(); ?>   
                 </td>
                 <td style="color:#1aff1a;font-size:18;font-weight:700">
                  <?= $total_price; ?> <?= getCurrency(); ?>
                </td>
                <td>
                  <a title="Delete" onClick="deleteCartRow(<?= $item->id; ?>)"><i class="fa fa-times fa-lg"></i></a>
                </td>
              </tr>
            <?php endforeach; ?>

          </tbody>
        </table>
      </div>          
    <?php endif; ?>		                    

  </div>
</div>
<!--/row end-->
</div><!-- web -->
<div class="container visible-sm visible-xs" style="margin-left:2%">

  <?php if( isset( $item_in_cart ) && $item_in_cart !== FALSE ) : ?>    
    <?php foreach ($item_in_cart as $item): ?>
      <?php 
      $discount = (($item->price*($item->discount_percent/100))+$item->discount_amount)*$item->qty;
      $total_price = ($item->qty*$item->price)-$discount; 
      $available_qty = 0;
      ?>
      <div class="row" id="row_<?= $item->id; ?>">
        <div class="col-sm-4 col-xs-4 text-center" style="padding:0px">
          <img src="<?= get_image_path( get_id_image($item->id), 2); ?>" alt="img">
        </div>
        <div class="col-sm-6 col-xs-6">
          <div class="price">
            <strong><h4><?= $item->code."-".$item->color_code."-".$item->size_name; ?></h4></strong>
            <h5><?= $item->name; ?></h5>
          </div>
          <?php if( $item->discount_percent > 0 || $item->discount_amount > 0) : ?>
            <span class="old-price"><?php echo number_format($item->price, 2, '.', '') ?>  <?= getCurrency(); ?></span>
          <?php endif; ?>
          <span><br>
            <strong><h3><?= sell_price($item->price, $item->discount_amount,$item->discount_percent); ?> <?= getCurrency(); ?></h3></strong>
          </span> 
        </div>
        <div class="col-sm-2 col-xs-2" style="margin-top:25%;">
         <a title="Delete" onClick="deleteCartRow(<?= $item->id; ?>)"><i class="fa fa-times fa-lg"></i></a>
       </div>
       <div class="row">
        <div class="col-sm-4 col-xs-4">
          <div class="input-group">
            <span class="input-group-btn">
              <button class="btn btn-xs decrease-btn" type="button"  onClick="decreaseQty(<?= $item->id; ?>, 1)"><i class="fa fa-minus"></i></button>
            </span>
            <span id="mQty_<?= $item->id; ?>" class="form-control" style="text-align:center; height:36px;">
              <?= $item->qty; ?>  
            </span>
            <span class="input-group-btn">
              <button class="btn btn-xs increase-btn" type="button" onClick="increaseQty(<?= $item->id; ?>, <?= $available_qty; ?>)"><i class="fa fa-plus"></i>
              </button>
            </span>
          </div><!-- /input-group -->
          <span class="stock-label">คงเหลือ <?= $available_qty ?>  ในสต็อก</span>
        </div><!-- col-sm-4 col-xs-4 -->
        <div class="col-sm-6 col-xs-6">
          <table id="cart-summary" class="std table">
            <thead>
              <tr>
                <th>ส่วนลดรวม</th>
                <th>ราคารวม</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="color:red;font-size:18;font-weight:700"><?= number_format($discount, 2); ?> </td>
                <td style="color:#1aff1a;font-size:18;font-weight:700"><?= number_format($total_price, 2); ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div><!-- row -->

      <legend style="padding:20px"></legend> </div>
    <?php endforeach ?>
  <?php endif ?>

</div><!-- mobile -->

<?php $total_discount = number_format(getDiscount($id_cart)); ?>
<?php $total_price    = getTotal($id_cart); ?>
<?php $total_amount   = ($total_price-$total_discount) ?>
<div class="col-lg-3 col-md-3 col-sm-5 rightSidebar">

  <div class="contentBox">
    <div class="w100 costDetails">
      <div class="table-block" id="order-detail-content">
       <div class="w100 cartMiniTable">
        <table id="cart-summary" class="std table">
          <tbody>
            <tr>
              <td>ราคาสินค้ารวม</td>
              <td class="price" id="total-price">
                <?php echo number_format($total_price, 2); ?>
              </td>
            </tr>
            <tr class="cart-total-price ">
              <td>ส่วนลด</td>
              <td class="price" id="total-discount">
                <?php echo number_format($total_discount,2); ?>
              </td>
            </tr>
            <tr class="cart-total-price ">
              <td>ส่วนลดจากรหัสโปรโมชั่น</td>
              <td class="price" id="total-discount">
                <?php echo "0.00"; ?>
              </td>
            </tr>
            <tr class="cart-total-price ">
              <td>ส่วนลดรวม</td>
              <td class="price" id="total-discount">
                <?php echo number_format($total_discount,2); ?>
              </td>
            </tr>
            <tr style="">
              <td>ค่าจัดส่ง</td>
              <td class="price" id="shipping-fee"><span id="transCost" style="color:red;font-size:14px">*** กรุณากดคำนวนค่าจัดส่ง</span></td>
            </tr>
            <tr>
              <td style="font-size:18px;font-size:700">ราคาสุทธิ</td>
              <td class="site-color" id="total_amount" style="font-size:22px; font-weight:bold;"><?php echo number_format($total_amount, 2); ?></td>
              <input type="text" class="hidden" id="trans_price" value="">
            </tr>
            <tr>
              <td colspan="2">

               <!--  <button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#transportPickerModal">เลือกช่องทางการจัดส่ง</button> -->
               <label for="">รหัสโปรโมชั่น : </label>
               <div class="input-group">

                <input type="text" class="form-control" placeholder="Promotion Code" aria-label="Search for..." id="inputCode">
                <span class="input-group-btn">
                  <button class="btn btn-success" type="button" onclick="promotionCode()">ตกลง</button>
                </span>
              </div>
              <legend></legend>
              <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#transportPickerModal">คำนวนค่าจัดส่ง</button>

              <button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#bankPickerModal">แจ้งการโอนเงิน</button>

              <button type="button" class="btn btn-warning btn-block" id="checkout-btn-bottom" onClick="goToHome()">กลับ</button>

            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>
<!-- End popular -->

</div>
<!--/rightSidebar-->
</div>
<!--/row-->
<div style="clear:both"></div>
</div>
<!-- /.main-container -->

<?php require('include/payment_modal.php'); ?>
<script>
  function promotionCode(event) {
    var x = $("#inputCode").val();
  
    console.log(x);
  };

</script>
<script src="<?php echo base_url(); ?>shop/assets/js/cart_script.js"></script>