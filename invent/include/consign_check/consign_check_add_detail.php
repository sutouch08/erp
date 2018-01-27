<?php $qs = $cs->getDetails($cs->id); ?>
<?php if(dbNumRows($qs) > 0) : ?>
<?php  $no = 1; ?>
<?php  $sumStock = 0; ?>
<?php  $sumCount = 0; ?>
<?php  $sumDiff = 0; ?>
<?php  $bc = new barcode(); ?>
<hr/>
<style>
  #detail-table > tr:first-child {
    color:blue;
  }
</style>
<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped border-1">
      <thead>
        <tr>
          <th class="width-5 text-center">No.</th>
          <th class="width-10 text-center">บาร์โค้ด</th>
          <th class="">รหัสสินค้า</th>
          <th class="width-10 text-center">ยอดในโซน</th>
          <th class="width-10 text-center">ยอดตรวจนับ</th>
          <th class="width-10 text-center">ยอดต่าง</th>
          <th class="width-10 text-center"></th>
        </tr>
      </thead>
      <tbody id="detail-table">
<?php while($rs = dbFetchObject($qs)) : ?>
<?php  $barcode = $bc->getBarcode($rs->id_product); ?>
<?php  $diff = $rs->stock_qty - $rs->qty; ?>
<?php  $hide = $rs->qty == 0 ? 'hide' : ''; ?>
        <tr class="font-size-12" id="row-<?php echo $barcode; ?>">
          <td class="middle text-center"><?php echo $no; ?></td>
          <td class="middle text-center"><?php echo $barcode; ?></td>
          <td class="middle"><?php echo $rs->product_code; ?></td>
          <td class="middle text-center">
            <span id="stock-qty-<?php echo $barcode; ?>">
            <?php echo $rs->stock_qty; ?>
            </span>
          </td>
          <td class="middle text-center">
            <span id="check-qty-<?php echo $barcode; ?>">
            <?php echo $rs->qty; ?>
            </span>
          </td>
          <td class="middle text-center">
            <span id="diff-qty-<?php echo $barcode; ?>">
            <?php echo $diff; ?>
            </span>
          </td>
          <td class="middle text-right">
            <button type="button" class="btn btn-xs btn-primary <?php echo $hide; ?>" id="btn-<?php echo $barcode; ?>" onclick="showEditDetail('<?php echo $barcode; ?>')">
              <i class="fa fa-pencil"></i> แก้ไข
            </button>
          </td>
        </tr>
<?php  $no++; ?>
<?php  $sumStock += $rs->stock_qty; ?>
<?php  $sumCount += $rs->qty; ?>
<?php  $sumDiff  += $diff; ?>
<?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
<input type="hidden" id="sumStock" value="<?php echo $sumStock; ?>" />
<input type="hidden" id="sumCount" value="<?php echo $sumCount; ?>" />
<input type="hidden" id="sumDiff" value="<?php echo $sumDiff; ?>" />
<?php else : ?>

<?php endif; ?>
