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
<?php if( ($add OR $edit) && $id !== FALSE && $cs->isSaved == 0 && $cs->isCancle == 0 ) : ?>
      <button type="button" class="btn btn-sm btn-success" onclick="saveConsign()"><i class="fa fa-save"></i> บันทึก</button>
<?php endif; ?>

    </p>
  </div>
</div>

<hr/>
<?php
include 'include/consign/consign_add_header.php';

//--- ต้องเพิ่มเอกสารแล้ว และ ต้องยังไม่บันทึกหรือยกเลิกเอกสาร
if( $id !== FALSE && $cs->isSaved == 0 && $cs->isCancle == 0)
{
  include 'include/consign/consign_add_control.php';
  include 'include/consign/consign_add_detail.php';
}


 ?>

<script src="script/consign/consign_add.js"></script>
<script src="script/consign/consign_edit.js"></script>
<script src="script/consign/consign_control.js"></script>
<script src="script/consign/consign_detail.js"></script>
