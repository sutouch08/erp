<?php $qs = $cs->getDetails($cs->id); ?>
<?php if(dbNumRows($qs) > 0) : ?>
<?php  $no = 1; ?>
<?php  $sumStock = 0; ?>
<?php  $sumCount = 0; ?>
<?php  $bc = new barcode(); ?>
<hr/>
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
      <tbody>
<?php while($rs = dbFetchObject($qs)) : ?>
        <tr class="font-size-12">
          <td class="middle text-center"><?php echo $no; ?></td>
          <td class="middle text-center"><?php echo $bc->getBarcode($rs->id_product); ?></td>
          <td class="middle"><?php echo $rs->product_code; ?></td>
          <td class="middle text-center"><?php echo $rs->stock_qty; ?></td>
          <td class="middle text-center"><?php echo $rs->qty; ?></td>
          <td class="middle text-center"><?php echo ($rs->stock_qty - $rs->qty); ?></td>
          <td class="middle text-right"></td>
        </tr>
<?php  $no++; ?>
<?php  $sumStock += $rs->stock_qty; ?>
<?php  $sumCount += $rs->qty; ?>
<?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<?php else : ?>

<?php endif; ?>
