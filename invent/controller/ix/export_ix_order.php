<?php
$sc = TRUE;
$error = "";
$id = $_POST['id'];
$order = new order($id);

if(!empty($order->id))
{
  if(!is_exists_in_ix($order->reference))
  {
    $cs = new customer();
    $customer = $cs->getDataById($order->id_customer);
    //----- เอารหัสลูกค้าไปดึงข้อมูลลูกค้าใน IX โดยใช้ field old_code
    $sql = db2Query("SELECT * FROM customers WHERE old_code = '{$customer->code}' OR code = '{$customer->code}'");
    $rows = db2NumRows($sql);
    if($rows == 0)
    {
      $sc = FALSE;
      $error = "add order error : ไม่พบลูกค้า {$customer->code} ในระบบ IX กรุณาตรวจสอบ";
    }
    else if($rows > 1)
    {
      $sc = FALSE;
      $error = "add order error : รหัสเก่าลูกค้า {$customer->code} ใน IX มี {$rows} คน";
    }
    else if($rows == 1)
    {
      startTransection2();
      $ixCustomer = db2FetchObject($sql);
      $payment = get_IX_payment($order->id_payment);
      $zone_code = NULL;
      $warehouse_code = NULL;
      if($order->role == 2)
      {
        $consign = new order_consign($order->id);
        $zone = new zone($consign->id_zone);
        $ix_zone = get_ix_zone($zone->barcode_zone);
        if(!empty($ix_zone))
        {
          $zone_code = $ix_zone->code;
          $warehouse_code = $ix_zone->warehouse_code;
        }
      }

      //---- 1. add order to IX
      $ds = array(
        'code' => $order->reference,
        'role' => get_IX_role($order->role, $order->is_so),
        'bookcode' => $order->bookcode,
        'reference' => $order->ref_code,
        'customer_code' => $ixCustomer->code,
        'customer_ref' => $order->online_code,
        'channels_code' => get_IX_channels_code($order->id_channels),
        'payment_code' => (empty($payment) ? NULL : $payment->code),
        'sale_code' => NULL,
        'state' => 7,
        'is_term' => (empty($payment) ? 1 : $payment->has_term),
        'bDiscText' => $order->bDiscText,
        'bDiscAmount' => $order->bDiscAmount,
        'shipping_code' => $order->shipping_code,
        'shipping_fee' => $order->shipping_fee,
        'service_fee' => $order->service_fee,
        'user' => NULL,
        'gp' => $order->gp,
        'status' => $order->status,
        'remark' => $order->remark,
        'date_add' => $order->date_add,
        'warehouse_code' => $warehouse_code,
        'zone_code' => $zone_code,
        'order_id' => $order->id
      );

      if(add_ix_order($ds))
      {
        $details = $order->get_details($order->id);
        if(!empty($details))
        {
          foreach($details as $rs)
          {
            if($sc === FALSE)
            {
              break;
            }

            $item = get_ix_item($rs->product_code);
            if(!empty($item))
            {
              //--- insert detail row
              $arr = array(
                'order_code' => $order->reference,
                'style_code' => $item->style_code,
                'product_code' => $item->code,
                'product_name' => $item->name,
                'cost' => $rs->cost,
                'price' => $rs->price,
                'qty' => $rs->qty,
                'discount1' => $rs->discount,
                'discount2' => $rs->discount2,
                'discount2' => $rs->discount3,
                'discount_amount' => $rs->discount_amount,
                'total_amount' => $rs->total_amount,
                'valid' => $rs->valid,
                'is_count' => $rs->isCount
              );

              if(add_ix_order_detail($arr) === FALSE)
              {
                $sc = FALSE;
                $error = "add details error : เพิ่มรายการสินค้า {$rs->product_code} ในเอกสารไม่สำเร็จ";
                break;
              }
            }
            else
            {
              $sc = FALSE;
              $error = "add details error : ไม่พบรหัสสินค้า {$rs->product_code}  ใน IX กรุณาตรวจสอบ";
              break;
            }

          } //--- end foreach details

          //--- do prepare add buffer
          if($sc === TRUE)
          {
            $prep = new prepare();
            $prepared = $prep->get_details($order->id);
            if(!empty($prepared))
            {
              foreach($prepared as $rs)
              {
                if($sc === FALSE)
                {
                  break;
                }

                $item = get_ix_item($rs->product_code);

                if(empty($item))
                {
                  $sc = FALSE;
                  $error = "prepare error : ไม่พบรหัสสินค้า {$rs->product_code} ในระบบ IX";
                  break;
                }

                $zone = get_ix_zone($rs->zone_code);
                if(empty($zone))
                {
                  $sc = FALSE;
                  $error = "prepare error : ไม่พบรหสโซน {$rs->zone_code} ในระบบ IX";
                  break;
                }

                if($sc === TRUE)
                {
                  $arr = array(
                    'order_code' => $order->reference,
                    'product_code' => $item->code,
                    'zone_code' => $zone->code,
                    'qty' => $rs->qty
                  );

                  if(! add_ix_prepare($arr))
                  {
                    $sc = FALSE;
                    $error = "prepare error : จัดสินค้า {$rs->product_code} โซน {$rs->zone_code} ไม่สำเร็จ";
                    break;
                  }
                }

                //--- ถ้าจัดสำเร็จ
                //--- เพิ่มรายการเข้า buffer
                if($sc === TRUE)
                {
                  $arr = array(
                    'order_code' => $order->reference,
                    'product_code' => $item->code,
                    'warehouse_code' => $zone->warehouse_code,
                    'zone_code' => $zone->code,
                    'qty' => $rs->qty
                  );

                  if(! add_ix_buffer($arr))
                  {
                    $sc = FALSE;
                    $error = "buffer error : เพิ่มรายการ {$rs->product_code} เข้า Buffer ไม่สำเร็จ";
                    break;
                  }
                }
              } //--- end foreach


              //--- ตรวจสินค้า
              if($sc === TRUE)
              {
                $id_box = get_ix_box_id('0000', $order->reference);
                $id_box = $id_box === FALSE ? 1 : $id_box;
                $qc = new qc();
                $qc_data = $qc->get_details($order->id);
                if(!empty($qc_data))
                {
                  foreach($qc_data as $rs)
                  {
                    if($sc === FALSE)
                    {
                      break;
                    }

                    $item = get_ix_item($rs->product_code);
                    if(!empty($item))
                    {
                      $arr = array(
                        'order_code' => $order->reference,
                        'product_code' => $item->code,
                        'qty' => $rs->qty,
                        'box_id' => $id_box
                      );

                      if(! add_ix_qc($arr))
                      {
                        $sc = FALSE;
                        $error = "qc error : ตรวจสินค้า {$rs->product_code} ไม่สำเร็จ";
                        break;
                      }
                    }
                    else
                    {
                      $sc = FALSE;
                      $error = "qc error : ไม่พบรหัสสินค้า {$rs->product_code} ในระบบ IX";
                      break;
                    }
                  } //--- end foreach qc
                } //--- end qc

              } //---- end qc
            } //--- end prepare

          } //--- end prepare
        }
        else
        {
          $sc = FALSE;
          $error = "add detail error : ไม่พบรายการในเอกสาร";
        }
      }
      else
      {
        $sc = FALSE;
        $error = "add order error : สร้างเอกสารไม่สำเร็จ";
      }

      if($sc === TRUE)
      {
        commitTransection2();
      }
      else
      {
        dbRollback2();
      }

      endTransection2();
    }
  } //--- end if exists_in_ix


//--- ถ้าส่งข้อมูลเรียบร้อยแล้ว อัพเดตออเดอร์ที่
if($sc === TRUE)
{
  dbQuery("UPDATE tbl_order SET ix = 1 WHERE id = {$order->id}");
}
else
{
  dbQuery("UPDATE tbl_order SET ix = 3, ix_error = '{$error}' WHERE id = {$order->id}");
}

} //--- end if ! empty($order->id)

echo $sc === TRUE ? 'success' : $error;
 ?>
