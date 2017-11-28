<?php
//--- ไว้ตรวจสอบความถูกต้อง
$sc = TRUE;

//--- ไอดีเอกสาร
$id_consign  = $_POST['id_consign'];

//--- ข้อมูลเอกสาร
$cs = new consign($id_consign);

//--- ลูกค้าตามเอกสาร
$id_customer = $_POST['id_customer'];

//--- โซนของลูกค้าที่จะตัดยอดออก
$id_zone    = $_POST['id_zone'];


//--- ตรวจสอบโซนที่ส่งมาว่าตรงกับที่บันทึกไว้หรือไม่
if( $cs->id_zone != $id_zone )
{
  $sc = FALSE;
  $message = 'โซนที่ต้องการบันทึกไม่ตรงกับโซนในระบบ';
}

//--- ตรวจสอบลูกค้าที่ส่งมาตรงกับที่บันทึกไว้หรือไม่
if( $cs->id_customer != $id_customer)
{
  $sc = FALSE;
  $message = 'ลูกค้าที่ต้องการบันทึกไม่ตรงกับลูกค้าในระบบ';
}


//--- ถ้าโซนและลูกค้าตรงกับที่บันทึกไว้
if( $sc === TRUE )
{
  //--- รายการสินค้า
  $products = $_POST['product'];

  //--- จำนวนที่ตัดยอด
  $qtys     = $_POST['qty'];

  //--- ราคาที่บันทึก
  $prices   = $_POST['price'];

  //--- ส่วนลดเป็นเปอร์เซ็นต์
  $p_disc   = $_POST['p_disc'];

  //--- ส่วนลดเป็นจำนวนเงิน
  $a_disc   = $_POST['a_disc'];

  //--- ตรวจสอบว่าข้อมูลที่ส่งมามีรายการหรือไม่
  if(empty($products))
  {
    $sc = FALSE;
    $message = 'ไม่พบรายการที่จะตัดยอด';
  }
  else
  {
    startTransection();

    $employee = new employee($cs->id_employee);
    $role     = new order_role(8);
    $vat      = getConfig('VAT');

    //--- ข้อมูลลูกค้า
    $customer = new customer($cs->id_customer);
    $customer_type  = new customer_type($customer->id_type);
    $customer_kind  = new customer_kind($customer->id_kind);
    $customer_class = new customer_class($customer->id_class);
    $customer_group = new customer_group($customer->id_group);
    $customer_area  = new customer_area($customer->id_area);



    //--- ข้อมูลสินค้า
    $pd       = new product();
    $color    = new color();
    $size     = new size();
    $pd_group = new product_group();
    $style    = new style();
    $category = new category();
    $kind     = new kind();
    $type     = new type();
    $brand    = new brand();

    //--- ไว้จัดการสต็อก
    $stock    = new stock();
    $movement = new movement();
    $zone     = new zone($id_zone);

    //---
    $order = new order();

    //--- ถ้ามีรายการ
    foreach($products as $id_pd => $value)
    {
      //--- ดึงข้อมูลสินค้า
      $pd->getData($id_pd);

      //--- จำนวนที่จะตัดยอด
      $qty = $qtys[$id_pd];

      //--- ราคาที่จะบันทึกขาย
      $price = $prices[$id_pd];

      //--- ส่วนลดที่ระบุมา (เป็นเปอร์เซ็นหรือยอดเงิน เช่น 10 % หรือ 10)
      $discLabel = getDiscountLabel($p_disc[$id_pd], $a_disc[$id_pd]);

      //--- ส่วนลดเป็นมูลค่าเงิน
      $discount = getDiscountAmount($p_disc[$id_pd], $a_disc[$id_pd], $price, $qty);

      //--- ส่วนลดเป็นมูลค่าเงิน x จำนวนที่ระบุมา
      $discount_amount = $discount * $qty;

      //--- มูลค่ารวม
      $total_amount = ($price - $discount) * $qty;

      //--- เตรียมข้อมูลสำหร้บเพิ่มรายการในตาราง tbl_consign_detail
      $arr = array(
        'id_consign' => $id_consign,
        'id_style'  => $pd->id_style,
        'id_product'  => $pd->id,
        'product_code' => $pd->code,
        'product_name'  => $pd->name,
        'cost'    => $pd->cost,
        'price'   => $price,
        'qty'     => $qty,
        'discount'  => $discLabel,
        'discount_amount' => $discount_amount,
        'total_amount'  => $total_amount
      );

      //--- บันทึกรายการ
      if( $cs->addDetail($arr) !== TRUE )
      {
        $sc = FALSE;
        $message = 'บันทึกรายการไม่สำเร็จ';
      }

      //--- ข้อมูลสำหรับบันทึกยอดขาย
      $ds = array(
              'id_order'        => 0,
              'reference'       => $cs->reference,
              'id_role'         => $role->id,
              'role_name'       => $role->name,
              'id_payment'      => 0,
              'payment'         => $role->name,
              'id_channels'     => 0,
              'channels'        => $role->name,
              'id_product'      => $pd->id,
              'product_code'    => $pd->code,
              'product_name'    => $pd->name,
              'id_color'        => $pd->id_color,
              'color'           => $color->getCode($pd->id_color),
              'color_group'     => $color->getGroupCode($pd->id_color),
              'id_size'         => $pd->id_size,
              'size'            => $size->getCode($pd->id_size),
              'size_group'      => $size->getGroupCode($pd->id_size),
              'id_product_style'=> $pd->id_style,
              'product_style'   => $style->getCode($pd->id_style),
              'id_product_group'=> $pd->id_group,
              'product_group'   => $pd_group->getName($pd->id_group),
              'id_product_category' => $pd->id_category,
              'product_category'=> $category->getName($pd->id_category),
              'id_product_kind' => $pd->id_kind,
              'product_kind'    => $kind->getName($pd->id_kind),
              'id_product_type' => $pd->id_type,
              'product_type'    => $type->getName($pd->id_type),
              'id_brand'        => $pd->id_brand,
              'brand'           => $brand->getName($pd->id_brand),
              'year'            => $pd->year,
              'cost_ex'         => removeVAT($pd->cost, $vat),
              'cost_inc'        => $pd->cost,
              'price_ex'        => removeVAT($price, $vat),
              'price_inc'       => $price,
              'sell_ex'         => removeVAT( ($total_amount/$qty), $vat),
              'sell_inc'        => $total_amount / $qty,
              'qty'             => $qty,
              'discount_label'  => $discLabel,
              'discount_amount' => $discount_amount,
              'total_amount_ex' => removeVAT($total_amount , $vat),
              'total_amount_inc'=> $total_amount,
              'total_cost_ex'   => removeVAT(($pd->cost * $qty), $vat),
              'total_cost_inc'  => $pd->cost * $qty,
              'margin_ex'       => removeVAT( ( $total_amount - ($pd->cost * $qty) ), $vat),
              'margin_inc'      => $total_amount - ($pd->cost * $qty),
              'id_customer'     => $customer->id,
              'customer_code'   => $customer->code,
              'customer_name'   => $customer->name,
              'customer_group'  => $customer_group->name,
              'customer_type'   => $customer_type->name,
              'customer_kind'   => $customer_kind->name,
              'customer_class'  => $customer_class->name,
              'customer_area'   => $customer_area->name,
              'province'        => $customer->province,
              'id_employee'     => $employee->id_employee,
              'employee_name'   => $employee->full_name,
              'date_add'        => dbDate($order->date_add, TRUE),
              'id_zone'         => $zone->id,
              'id_warehouse'    => $zone->id_warehouse
      );

      //--- บันทึกขาย
      if($order->sold($ds) !== TRUE)
      {
        $sc = FALSE;
        $message = 'บันทึกขายไม่สำเร็จ';
      }

      //-------- ตัดยอดสต็อกจากโซน
      //--- ตรวจสอบจำนวนคงเหลือในโซนว่าพอให้ตัดหรือไม่
      $isEnough = $stock->isEnough($zone->id, $pd->id, $qty);

      //--- ตรวจสอบว่าโซนอนุญาติให้ติดลบหรือไม่
      $allowUnderZero = $zone->allowUnderZero;

      if( $isEnough === FALSE && $allowUnderZero === FALSE)
      {
        $sc = FALSE;
        $message = 'จำนวนคงเหลือไม่เพียงพอ';
      }
      else
      {
        if( $stock->updateStockZone($zone->id, $pd->id, ($qty * -1)) !== TRUE )
        {
          $sc = FALSE;
          $message = 'ตัดยอดในโซนไม่สำเร็จ';
        }
      }

      //--- บันทึก movement
      if( $movement->move_out($cs->reference, $zone->id_warehouse, $zone->id, $pd->id, $qty, $cs->date_add) !== TRUE)
      {
        $sc = FALSE;
        $message = 'บันทึก movement ไม่สำเร็จ';
      }

    } //--- end foreach

    if( $sc === TRUE )
    {
      if( $cs->setSaved($cs->id, 1) !== TRUE)
      {
        $sc = FALSE;
        $message = 'บันทึกเอกสารไม่สำเร็จ';
      }
    }

    if( $sc === TRUE )
    {
      commitTransection();
    }
    else
    {
      dbRollback();
    }

    endTransection();

  } //--- endif empty($product)

  echo $sc === TRUE ? 'success' : $message;

}



 ?>
