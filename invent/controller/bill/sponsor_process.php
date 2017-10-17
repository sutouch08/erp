<?php
  $bill = new bill();
  $bd = new sponsor_budget($order->id_budget);

  //--- ใช้งาน ทรานเซ็คชั่น
  startTransection();

  //---	เปลี่ยนสถานะออเดอร์เป็นเปิดบิลแล้ว
  $ra = $order->stateChange($order->id, 8);


  //--- ดึงเครดิตที่ถูกใช้ไปของออเดอร์นี้มาตั้งไว้ก่อน
  //--- บันทึกขายแล้วค่อยตัดออก ส่วนที่เหลือให้คืนกลับให้ลูกค้า
  $useCredit = $order->getTotalAmount($order->id);

  //--- รายละเอียดการเปิดบิล เปรียบเทียบกันระหว่างสั่งซื้อ กับ ตรวจสินค้า
  $qs = $bill->getBillDetail($order->id);

  if( dbNumRows($qs) > 0)
  {
    $customer = new customer($order->id_customer);
    $employee = new employee($order->id_employee);

    $role     = new order_role($order->role);
    $vat      = getConfig('VAT');

    $customer_type  = new customer_type($customer->id_type);
    $customer_kind  = new customer_kind($customer->id_kind);
    $customer_class = new customer_class($customer->id_class);
    $customer_group = new customer_group($customer->id_group);
    $customer_area  = new customer_area($customer->id_area);

    //$sale = new sale($order->id_sale);

    $stock    = new stock();

    $movement = new movement();
    $buffer   = new buffer();
    $prepare  = new prepare();
    $zone     = new zone();

    $product  = new product();
    $color    = new color();
    $size     = new size();
    $pd_group = new product_group();
    $style    = new style();
    $category = new category();
    $kind     = new kind();
    $type     = new type();
    $brand    = new brand();




    while( $rs = dbFetchObject($qs))
    {
      //--- ถ้ายอดตรวจ น้อยกว่า หรือ เท่ากับ ยอดสั่ง ใช้ยอดตรวจในการตัด buffer
      //--- ถ้ายอดตวจ มากกว่า ยอดสั่ง ให้ใช้ยอดสั่งในการตัด buffer (บางทีอาจมีการแก้ไขออเดอร์หลังจากมีการตรวจสินค้าแล้ว)
      $sell_qty = ($rs->order_qty >= $rs->qc) ? $rs->qc : $rs->order_qty;


      //--- ดึงข้อมูลสินค้าที่จัดไปแล้วตามสินค้า
      $qa = $buffer->getDetails($order->id, $rs->id_product);


      if( dbNumRows($qa) > 0)
      {
        while( $rm = dbFetchObject($qa) )
        {

          //--- ถ้ายอดใน buffer น้อยกว่าหรือเท่ากับยอดสั่งซื้อ (แยกแต่ละโซน น้อยกว่าหรือเท่ากับยอดสั่ง (ซึ่งควรเป็นแบบนี้))
            $buffer_qty = $rm->qty <= $sell_qty ? $rm->qty : $sell_qty;

            //--- ทำยอดให้เป็นลบเพื่อตัดยอดออก เพราะใน function  ใช้การบวก
            $qty = $buffer_qty * (-1);

            //--- 1. ตัดยอดออกจาก buffer
            //--- นำจำนวนติดลบบวกกลับเข้าไปใน buffer เพื่อตัดยอดให้น้อยลง

            $rb = $buffer->update($rm->id_order, $rm->id_product, $rm->id_zone, $qty);

            //--- ลดยอด sell qty ลงตามยอด buffer ทีลดลงไป
            $sell_qty += $qty;


            //--- 2. update movement
            $rc = $movement->move_out($order->reference, $rm->id_warehouse, $rm->id_zone, $rm->id_product, $buffer_qty, dbDate($order->date_add, TRUE));


            $product->getData($rm->id_product);

            $ds = $order->getDetail($order->id, $product->id); //---  return as object of order_detail row


            //--- ข้อมูลสำหรับบันทึกยอดขาย
            $arr = array(
                    'id_order'  => $order->id,
                    'reference' => $order->reference,
                    'id_role'   => $order->role,
                    'role_name' => $role->name,
                    'payment'   => 'sponsor',
                    'channels'  => 'sponsor',
                    'id_product'  => $product->id,
                    'product_code'  => $product->code,
                    'product_name'  => $product->name,
                    'color'         => $color->getCode($product->id_color),
                    'color_group'   => $color->getGroupCode($product->id_color),
                    'size'          => $size->getCode($product->id_size),
                    'size_group'    => $size->getGroupCode($product->id_size),
                    'product_style' => $style->getCode($product->id_style),
                    'product_group' => $pd_group->getName($product->id_group),
                    'product_category'  => $category->getName($product->id_category),
                    'product_kind'  => $kind->getName($product->id_kind),
                    'product_type'  => $type->getName($product->id_type),
                    'brand'         => $brand->getName($product->id_brand),
                    'year'          => $product->year,
                    'cost_ex' => removeVAT($product->cost, $vat),
                    'cost_inc'  => $product->cost,
                    'price_ex'  => removeVAT($product->price, $vat),
                    'price_inc' => $product->price,
                    'sell_ex'   => removeVAT( ($ds->total_amount/$ds->qty), $vat),
                    'sell_inc'  => $ds->total_amount / $ds->qty,
                    'qty'       => $buffer_qty,
                    //'discount_label'  => $ds->discount,
                    //'discount_amount' => $ds->discount_amount,
                    'total_amount_ex' => removeVAT( ($ds->total_amount / $ds->qty) * $buffer_qty, $vat),
                    'total_amount_inc'  => ($ds->total_amount / $ds->qty) * $buffer_qty,
                    'total_cost_ex'   => removeVAT(($product->cost * $buffer_qty), $vat),
                    'total_cost_inc'  => $product->cost * $buffer_qty,
                    'margin_ex'   => removeVAT( ( ( ($ds->total_amount / $ds->qty) * $buffer_qty) - ($product->cost * $buffer_qty) ), $vat),
                    'margin_inc'  => ( ($ds->total_amount / $ds->qty) * $buffer_qty) - ($product->cost * $buffer_qty),
                  //  'id_policy'   => $policy->id,
                  //  'policy_code' => $policy->reference,
                  //  'policy_name' => $policy->name,
                  //  'id_rule'     => $rule->id,
                  //  'rule_code'   => $rule->code,
                  //  'rule_name'   => $rule->name,
                    'id_customer' => $customer->id,
                    'customer_code' => $customer->code,
                    'customer_name' => $customer->name,
                    'customer_group'  => $customer_group->name,
                    'customer_type'   => $customer_type->name,
                    'customer_kind'   => $customer_kind->name,
                    'customer_class'  => $customer_class->name,
                    'customer_area'   => $customer_area->name,
                    //'province'        => $order->isOnline == 1 ? $online_address->getProvince($order->online_code) : $customer->province,
                    //'id_sale'    => $sale->id,
                    //'sale_code'   => $sale->code,
                    //'sale_name'   => $sale->name,
                    'id_employee' => $employee->id_employee,
                    'employee_name' => $employee->full_name,
                    'date_add'  => dbDate($order->date_add, TRUE),
                    'id_zone' => $rm->id_zone,
                    'id_warehouse'  => $rm->id_warehouse
            );

            //--- 3. บันทึกยอดขาย
            $rd = $order->sold($arr);

            //--- ถ้ามีการใช้เครดิต ตัดยอดเครดิตใช้ไป
            if( $useCredit > 0 )
            {
              $useCredit -= $arr['total_amount_inc'];
            }

            if( $rb === FALSE OR $rc === FALSE OR $rd === FALSE )
            {
              $sc = FALSE;
            }
        } //--  end while
      } //--- end if
    } //--- End while

    //--- เคลียร์ยอดค้างที่จัดเกินมาไปที่ cancle หรือ เคลียร์ยอดที่เป็น 0
    //--  function/bill_helper.php
    if( clearBuffer($order->id) === FALSE)
    {
      $sc = FALSE;
    }

  }
  else
  {
    $sc = FALSE;
  }


  //--- ถ้าเป็นออเดอร์ที่มีการใช้เครดิต และ ออเดอร์ได้ของไม่ครบตามที่สั่ง
  //--- ให้คืนยอดใช้ไปให้ลูกค้า
  if( $useCredit > 0)
  {
    $bd->decreaseUsed($order->id_budget, $useCredit);
  }


  if( $sc === TRUE )
  {
    commitTransection();
    //dbRollback();
  }
  else
  {
    dbRollback();
    $message = 'ทำรายการไม่สำเร็จ';
  }

  endTransection();




 ?>
