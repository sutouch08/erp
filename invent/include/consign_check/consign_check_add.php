<?php
$id = isset($_GET['id_consign_check']) ? $_GET['id_consign_check'] : FALSE;
$cs = $id === FALSE ? new consign_check() : new consign_check($id);
?>
<div class="row top-row">
  <div class="col-sm-6 top-col">
    <h4 class="title"><i class="fa fa-check"></i> <?php echo $pageTitle; ?></h4>
  </div>
  <div class="col-sm-6">
    <p class="pull-right top-p">
      <?php echo goBackButton(); ?>
    </p>
  </div>
</div>
<hr/>

<div class="row">
  <div class="col-sm-1 col-1-harf padding-5 first">
    <label>เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm text-center header-box" value="<?php echo $cs->reference; ?>" />
  </div>
  <div class="col-sm-1 padding-5">
    <label>วันที่</label>
    <input type="text" class="form-control input-sm text-center header-box" id="dateAdd" value="<?php echo thaiDate($cs->date_add); ?>" />
  </div>
</div>

<script>


</script>
<?php
if($cs->id == '')
{
  include 'include/'
}
 ?>
