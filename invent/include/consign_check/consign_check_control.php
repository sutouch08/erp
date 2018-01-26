<!--- Control ---->
<hr/>
<div class="row">
  <div class="col-sm-2 padding-5 first">
    <label>บาร์โค้ดกล่อง</label>
    <input type="text" class="form-control input-sm text-center box" id="txt-box-barcode" />
  </div>
  <div class="col-sm-1 padding-5">
    <label>จำนวน</label>
    <input type="number" class="form-control input-sm text-center item" id="txt-qty" value="1" />
  </div>
  <div class="col-sm-2 padding-5">
    <label>บาร์โค้ดสินค้า</label>
    <input type="text" class="form-control input-sm text-center item" id="txt-pd-barcode" />
  </div>
  <div class="col-sm-1 padding-5">
    <label class="display-block not-show">ok</label>
    <button type="button" class="btn btn-sm btn-default btn-block item" id="btn-check" onclick="checkItem()">
      <i class="fa fa-check"></i> ตกลง
    </button>
  </div>

  <div class="col-sm-1 col-1-harf padding-5">
    <label class="display-block not-show">change</label>
    <button type="button" class="btn btn-sm btn-info btn-block item" id="btn-change-box" onclick="changeBox()">
      <i class="fa fa-refresh"></i> เปลี่ยนกล่อง
    </button>
  </div>

  <div class="col-sm-2 col-2-harf">

  </div>
  <div class="col-sm-2 padding-5 last">
    <div class="title middle text-center" style="height:55px; background-color:black; color:white; padding-top:20px; margin-top:0px;">
      <h4 id="box-qty" class="inline text-center">0</h4>
    </div>
  </div>

</div>

<input type="hidden" id="id_box" value="" />
<!--/ Control --->
