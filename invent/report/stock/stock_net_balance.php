<div class="container">

  <div class="row top-row">
    <div class="col-sm-6 top-col">
      <h4 class="title"><i class="fa fa-bar-chart"></i>&nbsp; <?php echo $pageTitle; ?></h4>
    </div>
    <div class="col-sm-6">
      <p class="pull-right top-p">
        <button type="button" class="btn btn-sm btn-success" onclick="getReport()"><i class="fa fa-list"></i> รายงาน</button>
        <button type="button" class="btn btn-sm btn-info" onclick="doExport()"><i class="fa fa-file-excel-o"></i> ส่งออก</button>
      </p>
    </div>
  </div>

<hr class="margin-bottom-10" />

<div class="row">
  <div class="col-sm-2 padding-5 first">
    <label class="display-block">การเลือกสินค้า</label>
    <div class="btn-group width-100">
      <button type="button" class="btn btn-sm btn-primary width-50" id="btn-item" onclick="toggleResult(1)">รายการสินค้า</button>
      <button type="button" class="btn btn-sm width-50" id="btn-style" onclick="toggleResult(0)">รุ่นสินค้า</button>
    </div>
  </div>
  <div class="col-sm-1 col-1-harf padding-5">
    <label class="display-block">สินค้า</label>
    <div class="btn-group width-100">
      <button type="button" class="btn btn-sm btn-primary width-50" id="btn-pd-all" onclick="toggleProduct(1)">ทั้งหมด</button>
      <button type="button" class="btn btn-sm width-50" id="btn-pd-range" onclick="toggleProduct(0)">ระบุ</button>
    </div>
  </div>
  <div class="col-sm-2 padding-5">
    <label class="display-block not-show">Start</label>
    <input type="text" class="form-control input-sm text-center pd-box item" id="txt-pd-from" placeholder="เริ่มต้น" disabled />
    <input type="text" class="form-control input-sm text-center pd-box style hide" id="txt-style-from" placeholder="เริ่มต้น" disabled />
  </div>
  <div class="col-sm-2 padding-5">
    <label class="display-block not-show">End</label>
    <input type="text" class="form-control input-sm text-center pd-box item" id="txt-pd-to" placeholder="สิ้นสุด" disabled />
    <input type="text" class="form-control input-sm text-center pd-box style hide" id="txt-style-to" placeholder="สิ้นสุด" disabled />
  </div>

  <div class="col-sm-2 padding-5">
    <label class="display-block">คลังสินค้า</label>
    <div class="btn-group width-100">
      <button type="button" id="btn-whAll" class="btn btn-sm btn-primary width-50" onclick="toggleWarehouse(1)">ทั้งหมด</button>
        <button type="button" id="btn-whList" class="btn btn-sm width-50" onclick="toggleWarehouse(0)">ระบุคลัง</button>
    </div>
  </div>

  <input type="hidden" id="allProduct" value="1" />
  <input type="hidden" id="allWhouse" value="1" />
  <input type="hidden" id="showItem" value="1" />

</div>



<hr style='border-color:#CCC; margin-top: 10px; margin-bottom:10px;' />
<div class="row">
	<div class="col-sm-12" id="rs">

    </div>
</div>
</div>


<div class="modal fade" id="wh-modal" tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
	<div class='modal-dialog' id='modal' style="width:500px;">
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='modal-title' id='modal_title'>เลือกคลัง</h4>
            </div>
            <div class='modal-body' id='modal_body'>

		<?php $qs = dbQuery("SELECT * FROM tbl_warehouse WHERE role IN(1,3,4,5,6) ORDER BY code ASC"); ?>
        <?php if( dbNumRows($qs) > 0 ) : ?>
        <?php	while( $rs = dbFetchObject($qs) ) : ?>
        		<div class="col-sm-12">
                	<label>
                    <input type="checkbox" class="chk" id="<?php echo $rs->id; ?>" value="<?php echo $rs->id; ?>" style="margin-right:10px;" />
                    <?php echo $rs->code; ?>  |  <?php echo $rs->name; ?>
                  </label>
                </div>
		<?php 	endwhile; ?>
        <?php endif; ?>
        		<div class="divider" ></div>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-default btn-block' data-dismiss='modal'>ตกลง</button>
            </div>
        </div>
    </div>
</div>

<script id="template" type="text/x-handlebars-template">
  <table class="table table-bordered table-striped">
    <tr>
      <th colspan="6" class="text-center">รายงานสินค้าคงเหลือ ณ วันที่ {{ reportDate }} (หักยอดจอง)</th>
    </tr>
    <tr>
      <th colspan="6" class="text-center"> คลัง : {{ whList }} </th>
    </tr>
    <tr>
      <th colspan="6" class="text-center"> สินค้า : {{ productList }} </th>
    </tr>
    <tr class="font-size-12">
      <th class="width-5 middle text-center">ลำดับ</th>
      <th class="width-15 middle text-center">รหัส</th>
      <th class="width-30 middle text-center">สินค้า</th>
      <th class="width-10 middle text-right">ทุน</th>
      <th class="width-10 text-right middle">คงเหลือ</th>
      <th class="width-15 text-right middle">มูลค่า</th>
    </tr>
{{#each bs}}
  {{#if nodata}}
    <tr>
      <td colspan="6" align="center"><h4>-----  ไม่พบสินค้าคงเหลือตามเงื่อนไขที่กำหนด  -----</h4></td>
    </tr>
  {{else}}
    {{#if @last}}
    <tr class="font-size-14">
      <td colspan="4" class="text-right">รวม</td>
      <td class="text-right">{{ totalQty }}</td>
      <td class="text-right">{{ totalAmount }}</td>
    </tr>
    {{else}}
    <tr class="font-size-12">
      <td class="middle text-center">{{no}}</td>
      <td class="middle">{{ pdCode }}</td>
      <td class="middle">{{ pdName }}</td>
      <td class="middle text-right">{{ cost }}</td>
      <td class="middle text-right">{{ qty }}</td>
      <td class="middle text-right">{{ amount }}</td>
    </tr>
    {{/if}}
  {{/if}}
{{/each}}
  </table>
</script>

<script src="script/report/stock/stock_net_balance.js?token=<?php date('Ymd'); ?>"></script>
