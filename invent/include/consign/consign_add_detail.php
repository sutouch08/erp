<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped table-bordered">
      <thead>
        <tr class="font-size-12">
          <th class="width-5 text-center">ลำดับ</th>
          <th class="width-15 text-center">บาร์โค้ด</th>
          <th class="width-30 text-center">สินค้า</th>
          <th class="width-10 text-center">ราคา</th>
          <th class="width-8 text-center">ส่วนลด(%)</th>
          <th class="width-8 text-center">ส่วนลด(เงิน)</th>
          <th class="width-8 text-center">จำนวน</th>
          <th class="width-10 text-center">มูลค่า(หักส่วนลด)</th>
          <th class="width-5"></th>
        </tr>
      </thead>
      <tbody id="detail-table">
        <tr id="total-row">
          <td colspan="6" class="middle text-right"><strong>รวม</strong></td>
          <td id="total-qty" class="middle text-center">0</td>
          <td id="total-amount" colspan="2" class="middle text-center">0</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<script id="row-template" type="text/x-handlebarsTemplate">
<tr class="font-size-12 rox" id="row-{{id_product}}">
  <td class="middle text-center no"></td>
  <td class="middle text-center">{{barcode}}</td>
  <td class="middle">{{product}}</td>
  <td class="middle text-center">
    <input type="number" class="form-control input-xs text-center padding-5 price" min="0" id="price-{{id_product}}" value="{{price}}" onKeyup="reCal('{{id_product}}')" onChange="reCal('{{id_product}}')" />
  </td>
  <td class="middle text-center">
    <input type="number" class="form-control input-xs text-center p-disc" min="0" max="100" id="p_disc-{{id_product}}" value="{{p_disc}}" onKeyup="p_disc_recal('{{id_product}}')" onChange="p_disc_recal('{{id_product}}')" />
  </td>
  <td class="middle text-center">
    <input type="number" class="form-control input-xs text-center a-disc" min="0" id="a_disc-{{id_product}}" value="{{a_disc}}" onKeyup="a_disc_recal('{{id_product}}')" onChange="a_disc_recal('{{id_product}}')" />
  </td>
  <td class="middle text-center">
    <input type="number" class="form-control input-xs text-center qty" min="0" id="qty-{{id_product}}" value="{{qty}}" onKeyup="reCal('{{id_product}}')" onChange="reCal('{{id_product}}')" />
  </td>
  <td class="middle text-right amount" id="amount-{{id_product}}">{{ amount }}</td>
  <td class="middle text-center">
    <button type="button" class="btn btn-xs btn-danger" onclick="deleteRow('{{id_product}}', '{{product}}')"><i class="fa fa-trash"></i></button>
    <input type="hidden" class="product" id="{{barcode}}" value="{{id_product}}" />
    <input type="hidden" class="product" id="{{product}}" value="{{id_product}}" />
    <input type="hidden" class="stock" id="stock-{{id_product}}" value="{{stock}}" />
  </td>
</tr>
</script>
