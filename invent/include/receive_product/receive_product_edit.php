<?php if( ! isset( $_GET['id_receive_product'] ) ) : ?>
<?php	include 'include/page_error.php';	?>
<?php else : ?>
<?php
	$id_receive_product = $_GET['id_receive_product'];
	$cs = new receive_product($id_receive_product);
	$po = new po();
	$bc = new barcode();
	$pd = new product();
?>
<div class="row">
	<div class="col-sm-1 col-1-harf padding-5 first">
    	<label>เลขที่เอกสาร</label>
        <label class="form-control input-sm text-center" disabled><?php echo $cs->reference; ?></label>
    </div>
	<div class="col-sm-1 col-1-harf padding-5">
    	<label>วันที่เอกสาร</label>
        <input type="text" class="form-control input-sm text-center input-header" id="dateAdd" placeholder="ระบุวันที่เอกสาร" value="<?php echo thaiDate($cs->date_add); ?>" disabled />
    </div>
    <div class="col-sm-1 col-1-harf padding-5">
    	<label>ใบส่งสินค้า</label>
        <input type="text" class="form-control input-sm text-center input-header" id="invoice" placeholder="อ้างอิงใบส่งสินค้า" value="<?php echo $cs->invoice; ?>" disabled />
    </div>
	<div class="col-sm-1 col-1-harf padding-5">
    	<label>ใบสั่งซื้อ</label>
        <input type="text" class="form-control input-sm text-center input-header" id="poCode" placeholder="ค้นหาใบสั่งซื้อ" value="<?php echo $cs->po; ?>" disabled />
    </div>
    <div class="col-sm-4 padding-5">
    	<label>หมายเหตุ</label>
        <input type="text" class="form-control input-sm input-header" id="remark" placeholder="ระบุหมายเหตุเอกสาร (ถ้ามี)" value="<?php echo $cs->remark; ?>" disabled />
    </div>
<?php if( $add ) : ?>    
    <div class="col-sm-1 padding-5">
    	<label class="display-block not-show">button</label>
        <button type="button" class="btn btn-sm btn-warning btn-block" id="btn-edit" onClick="getEdit()"><i class="fa fa-pencil"></i> แก้ไข</button>
        <button type="button" class="btn btn-sm btn-success btn-block hide" id="btn-update" onclick="saveEdit()"><i class="fa fa-save"></i> บันทึก</button>
    </div>
<?php endif; ?>  

</div>
<hr class="margin-top-15"/>
<div class="row">
    <div class="col-sm-3 padding-5 first">
    	<label>ชื่อโซน</label>
        <input type="text" class="form-control input-sm text-center zone" id="zoneName" placeholder="ค้นหาชื่อโซน"  autofocus/>
    </div>
    <div class="col-sm-3 padding-5 first">
    	<label>สินค้า</label>
        <input type="text" class="form-control input-sm text-center" id="styleCode" placeholder="ระบุรุ่นสินค้า" disabled />
    </div>
    <div class="col-sm-1 padding-5 last">
    	<label class="display-block not-show">product</label>
        <button type="button" class="btn btn-sm btn-primary btn-block" id="btn-get-product" onClick="getProductGrid()" disabled><i class="fa fa-tags"></i> ตกลง</button>
    </div>
    <div class="col-sm-2">
    	<label class="display-block not-show">change</label>
        <button type="button" class="btn btn-sm btn-info btn-block" onClick="changeZone()"><i class="fa fa-retweet"></i> เปลี่ยนโซน</button>
    </div>
    <input type="hidden" id="id_receive_product" value="<?php echo $id_receive_product; ?>" />
    <input type="hidden" id="id_zone" />
    <input type="hidden" id="poCode" value="<?php echo $cs->po; ?>" />
</div>
<hr/>
<?php $qs = $po->getDetail($cs->po); ?>
<div class="row">
	<div class="col-sm-12">
    	<table class="table table-striped table-bordered">
        	<thead>
            	<tr class="font-size-12">
                	<th class="width-5 text-center">ลำดับ	</th>
                    <th class="width-15 text-center">บาร์โค้ด</th>
                    <th class="width-15 text-center">รหัสสินค้า</th>
                    <th class="width-35">ชื่อสินค้า</th>
                    <th class="width-10 text-center">สั่งซื้อ</th>
                    <th class="width-10 text-center">ค้างรับ</th>
                    <th class="width-10 text-center">จำนวน</th>
                </tr>
            </thead>
            <tbody>
<?php if( dbNumRows($qs) > 0 ) : ?>
<?php 	$no = 1;	?>
<?php	$totalQty = 0;		?>
<?php 	$totalBacklog = 0 ; ?>
<?php	$bc = new barcode();	?>
<?php	while( $rs = dbFetchObject($qs) ): ?>
				<tr class="font-size-12" id="row_<?php echo $rs-id; ?>">
                	<td class="middle text-center"><?php echo $no; ?></td>
                    <td class="middle text-center"><?php echo $bc->getBarcode($rs->id_product); ?></td>
                    <td class="middle"><?php echo $pd->getCode($rs->id_product); ?></td>
                    <td class="middle"><?php echo $pd->getName($rs->id_product); ?></td>
                    <td class="middle text-center"><?php echo number_format($rs->qty); ?></td>
                    <td class="middle text-center"><?php echo number_format( ( $rs->qty - $rs->received) ); ?></td>
                    <td class="middle text-center">
                    	<input type="text" class="form-control input-sm text-center receive-box" name="receive[<?php echo $rs->id_product; ?>]" id="receive-<?php echo $rs->id_product; ?>" />
                    </td>
                </tr>
<?php	$totalQty += $rs->qty; ?>
<?php	$totalBacklog += ( $rs->qty - $rs->received);	?>              
<?php		$no++;	?>
<?php	endwhile; ?>
				<tr>
                	<td colspan="3" class="middle text-right"><strong>รวม</strong></td>
                    <td class="middle text-center"><?php echo number_format($totalQty); ?></td>
                    <td class="middle text-center"><?php echo number_format($totalBacklog); ?></td>
                    <td class="middle text-center"><span id="total-receive">0</span></td>
                </tr>
<?php endif; ?> 
			</tbody>       
        </table>
    </div>
</div>


<script src="script/receive_product/receive_product_by_keyboard.js"></script>
<?php endif; ?>