<div class="container">

  <div class="row top-row">
    <div class="col-sm-6 top-col">
      <h4 class="title"><i class="fa fa-bar-chart"></i>&nbsp; <?php echo $pageTitle; ?></h4>
    </div>
    <div class="col-sm-6">
      <p class="pull-right top-p">
        <button type="button" class="btn btn-sm btn-success" onclick="getReport()"><i class="fa fa-list"></i> รายงาน</button>
        <button type="button" class="btn btn-sm btn-info" onclick="doExport()"><i class="fa fa-file-excel-o"></i> ส่งออก</button>
        <button type="button" class="btn btn-sm btn-warning" onclick="exportToCheck()"><i class="fa fa-file-excel-o"></i> ส่งออกยอดตั้งต้น</button>
      </p>
    </div>
  </div>

<hr class="margin-bottom-10" />

<div class="row">
  <div class="col-sm-2 padding-5 first">
    <label class="display-block">สินค้า</label>
    <div class="btn-group width-100">
      <button type="button" class="btn btn-sm btn-primary width-50" id="btn-product-all" onclick="toggleProduct('all')">ทั้งหมด</button>
      <button type="button" class="btn btn-sm width-50" id="btn-product-range" onclick="toggleProduct('range')">เลือกเป็นช่วง</button>
    </div>
  </div>

  <div class="col-sm-2 padding-5">
    <label class="display-block not-show">range</label>
    <input type="text" class="form-control input-sm text-center pd-box" id="txt-pdFrom" placeholder="เริ่มต้น" disabled />
  </div>

  <div class="col-sm-2 padding-5">
    <label class="display-block not-show">range</label>
    <input type="text" class="form-control input-sm text-center pd-box" id="txt-pdTo" placeholder="สิ้นสุด" disabled />
  </div>

  <div class="col-sm-2 padding-5">
    <label>คลังสินค้า</label>
    <input type="text" class="form-control input-sm text-center" id="txt-warehouse" placeholder="ทุกคลัง" />
  </div>

  <div class="col-sm-2 padding-5">
    <label class="display-block">โซน</label>
    <div class="btn-group width-100">
      <button type="button" class="btn btn-sm btn-primary width-50" id="btn-zone-all" onclick="toggleZone('all')">ทั้งหมด</button>
      <button type="button" class="btn btn-sm width-50" id="btn-zone-sp" onclick="toggleZone('sp')">เฉพาะ</button>
    </div>
  </div>

  <div class="col-sm-2 padding-5 last">
    <label class="not-show">zone</label>
    <input type="text" class="form-control input-sm text-center zone-box" id="txt-zone" placeholder="เลือกโซน" disabled />
  </div>

  <input type="hidden" id="allProduct" value="1" />
  <input type="hidden" id="allZone" value="1" />
  <input type="hidden" id="id_warehouse" value="" />
  <input type="hidden" id="id_zone" value="" />

</div>



<hr style='border-color:#CCC; margin-top: 10px; margin-bottom:10px;' />
<div class="row">
	<div class="col-sm-12" id="rs">

    </div>
</div>
</div>

<script id="template" type="text/x-handlebars-template">
  <table class="table table-bordered table-striped">
    <tr>
      <th colspan="5" class="text-center">========== รายงานสินค้าคงเหลือแยกตามโซน =============</th>
    </tr>
    <tr class="font-size-12">
      <th class="width-5 middle text-center">ลำดับ</th>
      <th class="width-30 middle">โซน</th>
      <th class="width-20 middle">รหัส</th>
      <th class="width-30 middle">สินค้า</th>
      <th class="width-15 text-right middle">คงเหลือ</th>
    </tr>
{{#each this}}
  {{#if nodata}}
    <tr>
      <td colspan="5" align="center"><h4>-----  ไม่พบสินค้าคงเหลือตามเงื่อนไขที่กำหนด  -----</h4></td>
    </tr>
  {{else}}
    {{#if @last}}
    <tr class="font-size-14">
      <td colspan="4" class="text-right">รวม</td>
      <td class="text-right">{{ totalQty }}</td>
    </tr>
    {{else}}
    <tr class="font-size-12">
      <td class="middle text-center">{{no}}</td>
      <td class="middle">{{ zone }}</td>
      <td class="middle">{{ reference }}</td>
      <td class="middle">{{ productName }}</td>
      <td class="middle text-right">{{ qty }}</td>
    </tr>
    {{/if}}
  {{/if}}
{{/each}}
  </table>
</script>

<script src="script/report/stock/stock_balance_by_zone.js"></script>
