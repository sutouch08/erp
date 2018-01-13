<?php

//--- function getFilter in function/tools.php
$sCode 	= getFilter('sCode', 'sOrderCode', '');	//---	reference
$sCus	 	= getFilter('sCus', 'sOrderCus', '' );	//---	customer

//$sPaymet	= getFilter('sPayment', 'sOrderPaymentMethod', '' ); //--- Payment Method
//$sChannels	= getFilter('sChannels', 'sOrderChannels', '' ); 	//---	Sales Channels
$fromDate	= getFilter('fromDate', 'fromDate', '' );
$toDate	= getFilter('toDate', 'toDate', '' );

?>
<div class="row top-row">
	<div class="col-sm-6 col-xs-6 top-col">
    <h4 class="title" style="padding-bottom:0px;"><?php echo $pageTitle; ?></h4>
  </div>

  <div class="col-sm-6 col-xs-6">
		<p class="pull-right top-p">
			<button type="button" class="btn btn-sm btn-success" onclick="goAdd()"><i class="fa fa-plus"></i> เพิ่มใหม่</button>
		</p>
  </div>
</div>

<hr class="margin-bottom-15" />


<form id="searchForm" method="post">
<div class="row">

	<div class="col-sm-3 col-xs-12">
    <label class="hidden-xs">เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm text-center search" name="sCode" id="sCode" value="<?php echo $sCode; ?>" placeholder="ค้นเลขที่เอกสาร" />
  </div>

  <div class="col-sm-3 col-xs-12">
    <label class="hidden-xs">ลูกค้า</label>
    <input type="text" class="form-control input-sm text-center search" name="sCus" id="sCus" value="<?php echo $sCus; ?>" placeholder="ค้นชื่อลูกค้า" />
  </div>

  <div class="col-sm-3 col-xs-12">
  	<label class="display-block hidden-xs">วันที่</label>
    <input type="text" class="form-control input-sm text-center input-discount" name="fromDate" id="fromDate" value="<?php echo $fromDate; ?>" placeholder="เริ่มต้น" />
    <input type="text" class="form-control input-sm text-center input-unit" name="toDate" id="toDate" value="<?php echo $toDate; ?>" placeholder="สิ้นสุด" />
  </div>

  <div class="col-sm-3 col-xs-12">
  	<label class="display-block not-show hidden-xs">Apply</label>
		<div class="btn-group width-100">
    	<button type="button" class="btn btn-sm btn-primary width-50" onClick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
			<button type="button" class="btn btn-sm btn-warning width-50 display-inline" onClick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
		</div>
  </div>



</div>
</form>

<hr class="margin-top-10 margin-bottom-10"/>

<?php
	$where = "WHERE id_sale = '".getCookie('sale_id')."' ";
	//--- Reference
	if( $sCode != "" )
	{
		createCookie('sOrderCode', $sCode);
		$where .= "AND reference LIKE '%".$sCode."%' ";
	}

	//--- Customer
	if( $sCus != "" )
	{
		createCookie('sOrderCus', $sCus);
		$where .= "AND id_customer IN(".getCustomerIn($sCus).") "; //--- function/customer_helper.php
	}



	if( $fromDate != "" && $toDate != "" )
	{
		createCookie('fromDate', $fromDate);
		createCookie('toDate', $toDate);
		$where .= "AND date_add >= '".fromDate($fromDate)."' AND date_add <= '". toDate($toDate)."' ";
	}

	$where .= "ORDER BY reference DESC";

	$paginator	= new paginator();
	$get_rows	= get_rows();
	$paginator->Per_Page('tbl_order', $where, $get_rows);
	//$paginator->display($get_rows, 'index.php?content=order');
	$qs = dbQuery("SELECT * FROM tbl_order " . $where." LIMIT ".$paginator->Page_Start.", ".$paginator->Per_Page);

?>
<div class="row">
	<div class="col-sm-8 padding-5 first">
		<?php $paginator->display($get_rows, 'index.php?content=order'); ?>
	</div>
	<div class="col-sm-3 padding-5">
		<input type="text" class="form-control input-sm text-center margin-top-10" id="pd-search-box" placeholder="ค้นหารหัสรุ่นสินค้า" />
	</div>
	<div class="col-sm-1 padding-5 last">
		<button type="button" class="btn btn-sm btn-block btn-primary margin-top-10" onclick="getStockGrid()">เช็คสต็อก</button>
	</div>
	<div class="col-xs-12 visible-xs">&nbsp;</div>
	<input type="hidden" id="id_style" />
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="table-responsive" style="min-height:400px;">
    	<table class="table table-bordered">
        	<thead>
            	<tr class="font-size-10">
                <th class="width-10 text-center">ลำดับ</th>
                <th class="width-15 text-center">เลขที่เอกสาร</th>
                <th class="text-center">ลูกค้า</th>
                <th class="width-10 text-center">ยอดเงิน</th>
                <th class="width-10 text-center">ช่องทางขาย</th>
                <th class="width-10 text-center">การชำระเงิน</th>
                <th class="width-10 text-center">สถานะ</th>
                <th class="width-10 text-center">วันที่</th>
              </tr>
            </thead>
            <tbody>
<?php if( dbNumRows($qs) > 0 ) : ?>
<?php	$no 		= row_no();			?>
<?php	$cs 		= new customer(); ?>
<?php	$order 	= new order(); ?>
<?php	$ch 		= new channels(); ?>
<?php	$pm		= new payment_method(); ?>
<?php	while( $rs = dbFetchObject($qs) ) : ?>

			<tr class="font-size-10" <?php echo stateColor($rs->state, $rs->status); //--- order_help.php ?>>
        <td class="middle text-cennter pointer text-center" onclick="goEdit(<?php echo $rs->id; ?>)"><?php echo $no; ?></td>

        <td class="middle pointer text-center" onclick="goEdit(<?php echo $rs->id; ?>)"><?php echo $rs->reference; ?></td>

        <td class="middle pointer" onclick="goEdit(<?php echo $rs->id; ?>)">
          <?php echo customerName($rs->id_customer); ?>
          <?php if( $pm->hasTerm($rs->id_payment) === FALSE && $rs->isPaid == 0 ) : ?>
                    <span class="red font-size-14 padding-10" style="margin-left:10px; background-color:#FFF;">ยังไม่ชำระเงิน</span>
          <?php endif; ?>
        </td>


				<td class="middle pointer text-center" onclick="goEdit(<?php echo $rs->id; ?>)"><?php echo number_format($order->getTotalAmount($rs->id), 2); ?></td>

				<td class="middle pointer text-center" onclick="goEdit(<?php echo $rs->id; ?>)"><?php echo $ch->getName($rs->id_channels); ?></td>

				<td class="middle pointer text-center" onclick="goEdit(<?php echo $rs->id; ?>)"><?php echo $pm->getName($rs->id_payment); ?></td>

				<td class="middle pointer text-center" onclick="goEdit(<?php echo $rs->id; ?>)"><?php echo stateName($rs->state, $rs->status); ?></td>

				<td class="middle pointer text-center" onclick="goEdit(<?php echo $rs->id; ?>)"><?php echo thaiDate($rs->date_add); ?></td>

        </tr>
<?php		$no++; ?>
<?php		endwhile; ?>
<?php else : ?>
			<tr>
            	<td colspan="9" class="text-center"><h4>ไม่พบรายการ</h4></td>
            </tr>
<?php endif; ?>
            </tbody>
        </table>
			</div>
    </div>
</div>

<div class="modal fade" id="orderGrid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" id="modal">
		<div class="modal-content">
  			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="modalTitle">title</h4>
          <center><span style="color: red;">ใน ( ) = ยอดคงเหลือทั้งหมด   ไม่มีวงเล็บ = สั่งได้ทันที</span></center>
			 </div>
			 <div class="modal-body" id="modalBody"></div>
			 <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
			 </div>
		</div>
	</div>
</div>

<script src="script/order/sale_order_list.js"></script>
<script src="script/order/sale_order_add.js"></script>
