

<div class="tab-pane fade" id="customer">

	<div class="row">
        <div class="col-sm-8 top-col">
            <h4 class="title">กำหนดเงื่อนไขตามคุณสมบัติลูกค้า</h4>
        </div>
				<div class="col-sm-4">
					<p class="pull-right top-p">
						<button type="button" class="btn btn-sm btn-success" onclick="saveCustomer()"><i class="fa fa-save"></i> บันทึก</button>
					</p>
				</div>
        <div class="divider margin-top-5"></div>
        <div class="col-sm-2">
					<span class="form-control left-label text-right">ลูกค้าทั้งหมด</span>
				</div>
        <div class="col-sm-2">
          <div class="btn-group width-100">
          	<button type="button" class="btn btn-sm width-50 btn-primary" id="btn-cust-all-yes" onclick="toggleAllCustomer('Y')">YES</button>
						<button type="button" class="btn btn-sm width-50" id="btn-cust-all-no" onclick="toggleAllCustomer('N')">NO</button>
          </div>
        </div>
				<div class="divider-hidden"></div>


        <div class="col-sm-2">
					<span class="form-control left-label text-right">ระบุลูกค้า</span>
				</div>
        <div class="col-sm-2">
					<div class="btn-group width-100">
						<button type="button" class="not-all btn btn-sm width-50" id="btn-cust-id-yes" onclick="toggleCustomerId('Y')" disabled>YES</button>
						<button type="button" class="not-all btn btn-sm width-50 btn-primary" id="btn-cust-id-no" onclick="toggleCustomerId('N')" disabled>NO</button>
					</div>
        </div>
				<div class="col-sm-3 padding-5">
					<input type="text" class="option form-control input-sm text-center" id="txt-cust-id-box" placeholder="ค้นหาชื่อลูกค้า" disabled />
				</div>
				<div class="col-sm-1 padding-5">
					<button type="button" class="option btn btn-sm btn-info btn-block" id="btn-cust-id-add" onclick="addCustId()" disabled><i class="fa fa-plus"></i> เพิ่ม</button>
				</div>
				<div class="divider-hidden"></div>



				<div class="col-sm-2">
					<span class="form-control left-label text-right">กลุ่มลูกค้า</span>
				</div>
        <div class="col-sm-2">
					<div class="btn-group width-100">
						<button type="button" class="not-all btn btn-sm width-50" id="btn-cust-group-yes" onclick="toggleCustomerGroup('Y')" disabled>YES</button>
						<button type="button" class="not-all btn btn-sm width-50 btn-primary" id="btn-cust-group-no" onclick="toggleCustomerGroup('N')" disabled>NO</button>
					</div>
        </div>
				<div class="col-sm-2 padding-5">
					<button type="button" class="option btn btn-sm btn-info btn-block" id="btn-select-cust-group" onclick="showCustomerGroup()" disabled>เลือกกลุ่มลูกค้า</button>
				</div>
				<div class="divider-hidden"></div>



				<div class="col-sm-2">
					<span class="form-control left-label text-right">ชนิดลูกค้า</span>
				</div>
        <div class="col-sm-2">
					<div class="btn-group width-100">
						<button type="button" class="not-all btn btn-sm width-50" id="btn-cust-type-yes" onclick="toggleCustomerType('Y')" disabled>YES</button>
						<button type="button" class="not-all btn btn-sm width-50 btn-primary" id="btn-cust-type-no" onclick="toggleCustomerType('N')" disabled>NO</button>
					</div>
        </div>
				<div class="col-sm-2 padding-5">
					<button type="button" class="option btn btn-sm btn-info btn-block" id="btn-select-cust-type" onclick="showCustomerType()" disabled>เลือกชนิดลูกค้า</button>
				</div>
				<div class="divider-hidden"></div>



				<div class="col-sm-2">
					<span class="form-control left-label text-right">ประเภทลูกค้า</span>
				</div>
        <div class="col-sm-2">
					<div class="btn-group width-100">
						<button type="button" class="not-all btn btn-sm width-50" id="btn-cust-kind-yes" onclick="toggleCustomerKind('Y')" disabled>YES</button>
						<button type="button" class="not-all btn btn-sm width-50 btn-primary" id="btn-cust-kind-no" onclick="toggleCustomerKind('N')" disabled>NO</button>
					</div>
        </div>
				<div class="col-sm-2 padding-5">
					<button type="button" class="option btn btn-sm btn-info btn-block" id="btn-select-cust-kind" onclick="showCustomerKind()" disabled>เลือกประเภทลูกค้า</button>
				</div>
				<div class="divider-hidden"></div>


				<div class="col-sm-2">
					<span class="form-control left-label text-right">เขตลูกค้า</span>
				</div>
        <div class="col-sm-2">
					<div class="btn-group width-100">
						<button type="button" class="not-all btn btn-sm width-50" id="btn-cust-area-yes" onclick="toggleCustomerArea('Y')" disabled>YES</button>
						<button type="button" class="not-all btn btn-sm width-50 btn-primary" id="btn-cust-area-no" onclick="toggleCustomerArea('N')" disabled>NO</button>
					</div>
        </div>
				<div class="col-sm-2 padding-5">
					<button type="button" class="option btn btn-sm btn-info btn-block" id="btn-select-cust-area" onclick="showCustomerArea()" disabled>เลือกเขตลูกค้า</button>
				</div>
				<div class="divider-hidden"></div>


				<div class="col-sm-2">
					<span class="form-control left-label text-right">เกรดลูกค้า</span>
				</div>
        <div class="col-sm-2">
					<div class="btn-group width-100">
						<button type="button" class="not-all btn btn-sm width-50" id="btn-cust-class-yes" onclick="toggleCustomerClass('Y')" disabled>YES</button>
						<button type="button" class="not-all btn btn-sm width-50 btn-primary" id="btn-cust-class-no" onclick="toggleCustomerClass('N')" disabled>NO</button>
					</div>
        </div>
				<div class="col-sm-2 padding-5">
					<button type="button" class="option btn btn-sm btn-info btn-block" id="btn-select-cust-class" onclick="showCustomerClass()" disabled>เลือกเกรดลูกค้า</button>
				</div>
				<div class="divider-hidden"></div>

    </div>

		<input type="hidden" id="all_customer" value="Y" />
		<input type="hidden" id="customer_id" value="N" />
		<input type="hidden" id="customer_group" value="N" />
		<input type="hidden" id="customer_type" value="N" />
		<input type="hidden" id="customer_kind" value="N" />
		<input type="hidden" id="customer_area" value="N" />
		<input type="hidden" id="customer_class" value="N" />

</div><!--- Tab-pane --->
