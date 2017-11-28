<?php
$id = isset($_GET['id_consign']) ? $_GET['id_consign'] : FALSE;
$cs = new consign($id);
$disabled = $id === FALSE ? '' : 'disabled';
$zone = new zone($cs->id_zone);
$shop = new shop($cs->id_shop);
$event = new event($cs->id_event);
$customer = new customer();
$allowUnderZero = $zone->allowUnderZero === TRUE ? 1 : 0;
 ?>

<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-check-square-o"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
<?php echo goBackButton(); ?>
<?php if( $cs->is_so == 1) : ?>
      <button type="button" class="btn btn-sm btn-info" onclick="exportConsignSold(<?php echo $cs->id; ?>)">
        <i class="fa fa-send"></i> ส่งข้อมูลไป formula
      </button>
<?php endif; ?>
    </p>
  </div>
</div>

<hr/>

<?php
include 'include/consign/consign_view_header.php';
include 'include/consign/consign_view_detail.php';
 ?>

<script src="script/consign/consign_add.js"></script>
<script src="script/consign/consign_edit.js"></script>
<script src="script/consign/consign_control.js"></script>
<script src="script/consign/consign_detail.js"></script>
