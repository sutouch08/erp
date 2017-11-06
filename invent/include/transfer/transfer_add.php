<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-random"></i> <?php echo $pageTitle; //--- index.php ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
    <?php echo goBackButton(); ?>

    <?php if( isset($_GET['id_transfer']) && isset( $_GET['barcode'])) : ?>
      <button type="button" class="btn btn-sm btn-primary" onclick="goUseKeyboard()">คีย์มือ</button>
    <?php endif; ?>

    <?php if( isset( $_GET['id_transfer'] ) && ! isset( $_GET['barcode'] ) ) : ?>
      <button type="button" class="btn btn-sm btn-primary" onclick="goUseBarcode()">ใช้บาร์โค้ด</button>
    <?php endif; ?>

    <?php if( isset( $_GET['id_transfer'] ) && ($add OR $edit) ) : ?>
      <button type="button" class="btn btn-sm btn-success" onclick="save()"><i class="fa fa-save"></i> บันทึก</button>
    <?php endif; ?>
    </p>
  </div>
</div>
<hr/>

<?php
  //----  import หัวเอกสาร
  include 'include/transfer/header_add.php';

  if( isset($_GET['id_transfer']))
  {
    //--- import controller สำหรับคีย์ข้อมูล
    include 'include/transfer/input_control.php';

    if( isset($_GET['barcode']))
    {
      //--- import transfer detail table for barcode input
      include 'include/transfer/transfer_detail_barcode.php';
    }
    else
    {
      //--- import transfer detail table for normal input
      include 'include/transfer/transfer_detail.php';
    }

  }

?>



<script src="script/transfer/transfer_add.js"></script>
<script src="script/transfer/transfer_control.js"></script>
<script src="script/transfer/transfer_detail.js"></script>
