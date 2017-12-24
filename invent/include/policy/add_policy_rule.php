<?php if( ! isset($_GET['id_policy'])) : ?>
<?php  include 'include/page_error.php'; ?>
<?php else : ?>
<div class="row">
<div class="col-sm-2 padding-right-0" style="padding-top:15px;">
<ul id="myTab1" class="setting-tabs">
        <li class="li-block"><a href="#discount" data-toggle="tab">ส่วนลด</a></li>
        <li class="li-block active"><a href="#customer" data-toggle="tab">ลูกค้า</a></li>
        <li class="li-block"><a href="#product" data-toggle="tab">สินค้า</a></li>
        <li class="li-block"><a href="#channels" data-toggle="tab">ช่องทางการขาย</a></li>
        <li class="li-block"><a href="#payments" data-toggle="tab">ช่องทางการชำระเงิน</a></li>
</ul>
</div>
<div class="col-sm-10" style="padding-top:15px; border-left:solid 1px #ccc; min-height:600px; max-height:1000px;">
<div class="tab-content">
        <?php include 'include/policy/rule/discount_rule.php'; ?>
        <?php include 'include/policy/rule/customer_rule.php'; ?>
        <?php include 'include/policy/rule/product_rule.php'; ?>
        <?php include 'include/policy/rule/channels_rule.php'; ?>
        <?php include 'include/policy/rule/payments_rule.php'; ?>
</div>
</div><!--/ col-sm-9  -->
</div><!--/ row  -->
<script src="script/policy/rule/customer_tab.js"></script>
<?php endif; ?>
