
<div class="row top-row">
	<div class="col-sm-6 top-col">
    	<h4 class="title" ><i class="fa fa-download"></i>&nbsp;<?php echo $pageTitle; ?></h4>
	</div>
    <div class="col-sm-6">
      	<p class="pull-right top-p">
			<button type="button" class="btn btn-sm btn-warning" onclick="leave()"><i class="fa fa-arrow-left"></i> กลับ</button>
<?php 	if( $add ) : ?>
				<button type="button" class="btn btn-sm btn-success" onclick="checkLimit()"><i class="fa fa-save"></i> บันทึก</button>
<?php	endif; ?>
        </p>
    </div>
</div>
<hr />

<div class="row">
  <div class="col-sm-2">
  	<label>เลขที่เอกสาร</label>
      <input type="text" class="form-control input-sm text-center" id="reference" />
  </div>

	<div class="col-sm-1 col-1-harf">
  	<label>วันที่เอกสาร</label>
      <input type="text" class="form-control input-sm text-center"  id="dateAdd" placeholder="ระบุวันที่เอกสาร" value="<?php echo date('d-m-Y'); ?>" />
      <span class="help-block red" id="date-error"></span>
  </div>

	<div class="col-sm-2">
  	<label>ใบเบิกสินค้า</label>
      <input type="text" class="form-control input-sm text-center" id="poCode" placeholder="ระบุใบเบิกสินค้า" />
      <span class="help-block red" id="po-error"></span>
  </div>

  <div class="col-sm-2">
  	<label>ใบส่งสินค้า</label>
      <input type="text" class="form-control input-sm text-center" id="invoice" placeholder="อ้างอิงใบส่งสินค้า" />
      <span class="help-block red" id="invoice-error"></span>
  </div>

	<div class="col-sm-3">
  	<label>ชื่อโซน</label>
      <input type="text" class="form-control input-sm text-center zone" name="zoneName" id="zoneName" placeholder="ค้นหาชื่อโซน"  />
      <span class="help-block red" id="zone-error"></span>
  </div>

  <div class="divider-hidden margin-top-5 margin-bottom-5"></div>


  <div class="col-sm-12">
  	<label>หมายเหตุ</label>
      <input type="text" class="form-control input-sm" name="remark" id="remark" placeholder="ระบุหมายเหตุเอกสาร (ถ้ามี)" />
  </div>
</div>


<hr class="margin-top-15"/>


<div class="row">
	<div class="col-sm-1">
    	<label>จำนวน</label>
        <input type="text" class="form-control input-sm text-center" id="qty" value="1.00" />
    </div>
    <div class="col-sm-3 ">
    	<label>บาร์โค้ดสินค้า</label>
        <input type="text" class="form-control input-sm text-center" id="barcode" placeholder="ยิงบาร์โค้ดเพื่อรับสินค้า" autocomplete="off"  />
    </div>
    <div class="col-sm-1">
    	<label class="display-block not-show">ok</label>
        <button type="button" class="btn btn-sm btn-primary" onclick="receiveProduct()"><i class="fa fa-check"></i> ตกลง</button>
    </div>

    <input type="hidden" name="id_zone" id="id_zone" />
		<input type="hidden" name="id_order" id="id_order" />
    <input type="hidden" name="id_receive_transform" id="id_receive_transform" />


</div>
<hr class="margin-top-15 margin-bottom-15"/>

<form id="receiveForm">
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
      <tbody id="receiveTable">
			</tbody>
    </table>
    </div>
</div>
<input type="hidden" name="id_emp" id="id_emp" />
<input type="hidden" name="approvKey" id="approvKey" />
</form>


<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog input-xlarge">
    	<div class="modal-content">
        	<div class="modal-header">
            	<button type='button' class='close' data-dismiss='modal' aria-hidden='true'> &times; </button>
				<h4 class='modal-title-site text-center' > ผู้มีอำนาจอนุมัติรับสินค้าเกิน </h4>
            </div>
            <div class="modal-body">
            	<div class="col-sm-12">
                	<input type="password" class="form-control input-sm text-center" id="sKey" />
                </div>
            </div>
            <div class="modal-footer">
            	<span class="help-block red text-center not-show" id="approvError">รหัสไม่ถูกต้องหรือไม่มีอำนาจในการอนุมัตินี้</span>
            </div>
        </div>
    </div>
</div>

<script id="template" type="text/x-handlebarsTemplate">
{{#each this}}
	{{#if @last}}
        <tr>
            <td colspan="4" class="middle text-right"><strong>รวม</strong></td>
            <td class="middle text-center">{{qty}}</td>
            <td class="middle text-center">{{backlog}}</td>
            <td class="middle text-center"><span id="total-receive">0</span></td>
        </tr>
    {{else}}
        <tr class="font-size-12">
            <td class="middle text-center">
			{{ no }}
			</td>
            <td class="middle barcode" id="barcode_{{barcode}}">{{barcode}}</td>
            <td class="middle">{{pdCode}}</td>
            <td class="middle">{{pdName}}</td>
            <td class="middle text-center" id="qty_{{barcode}}">
				{{qty}}
				<input type="hidden" id="limit_{{barcode}}" value="{{limit}}"/>
			</td>
            <td class="middle text-center">{{backlog}}</td>
            <td class="middle text-center">
                <input type="text" class="form-control input-sm text-center receive-box pdCode" name="receive[{{id_pd}}]" id="receive-{{barcode}}" />
            </td>
        </tr>
    {{/if}}
{{/each}}
</script>

<script src="script/receive_transform/receive_transform_add.js"></script>
<script src="script/validate.js"></script>
