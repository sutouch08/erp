<?php
/******
//----- Step -----//
1. add mv document
2. add mv details
3. add movement
*******/
$sc = TRUE;
$erro = "";
$id = $_POST['id'];
$consign = new consign($id);

if(!empty($consign->id))
{
  //--- ตรวจสอบเอกสารซ้ำ
  if(! is_consign_exists_in_ix($consign->reference))
  {
    //--- 1. add document
    $zone = new zone($consign->id_zone);
    if(!empty($zone))
    {
      $ix_zone = get_ix_zone($zone->barcode_zone);
      if(!empty($ix_zone))
      {
        $customer = new customer($consign->id_customer);
        if(!empty($customer))
        {
          $ix_cust = get_ix_customer($customer->code);

          if(is_null($ix_cust))
          {
            $sc = FALSE;
            $error = "add order error : ไม่พบลูกค้า {$customer->code} ในระบบ IX กรุณาตรวจสอบ";
          }
          else if(is_numeric($ix_cust))
          {
            $sc = FALSE;
            $error = "add order error : รหัสเก่าลูกค้า {$customer->code} ใน IX มี {$ix_cust} คน";
          }
          else if(is_object($ix_cust))
          {
            startTransection2();
            $arr = array(
              'code' => $consign->reference,
              'role' => 'D',
              'bookcode' => 'WD',
              'customer_code' => $ix_cust->code,
              'customer_name' => $ix_cust->name,
              'zone_code' => $ix_zone->code,
              'zone_name' => $ix_zone->name,
              'warehouse_code' => $ix_zone->warehouse_code,
              'remark' => $consign->remark,
              'date_add' => $consign->date_add,
              'status' => 0
            );

            if(add_ix_wm($arr) === TRUE)
            {
              //--- new add details
              $details = $consign->get_details($consign->id);
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
                    $ds = array(
                      'consign_code' => $consign->reference,
                      'style_code' => $item->style_code,
                      'product_code' => $item->code,
                      'product_name' => $item->name,
                      'cost' => $rs->cost,
                      'price' => $rs->price,
                      'qty' => $rs->qty,
                      'discount' => $rs->discount,
                      'discount_amount' => $rs->discount_amount,
                      'amount' => $rs->total_amount,
                      'status' => 0,
                      'input_type' => $rs->input_type
                    );

                    if(! add_ix_wm_detail($ds))
                    {
                      $sc = FALSE;
                      $error = "Add detail error : เพิ่มรายการ  {$rs->product_code}  ไม่สำเร็จ";
                    }

                  }
                  else
                  {
                    $sc = FALSE;
                    $error = "Add details error : ไม่พบรหัสสินค้า {$rs->product_code} ในระบบ IX";
                  }
                }
              }
              else
              {
                $sc = FALSE;
                $error = 'add details error : ไม่พบรายการในเอกสาร';
              }
            }
            else
            {
              $sc = FALSE;
              $error = "Document error : เพิ่มเอกสาร {$consign->reference} ไม่สำเร็จ";
            }

            //---- if everything fine commit transection
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
        }
        else
        {
          $sc = FALSE;
          $error = "add order error : ไม่พบลูกค้า {$consign->id_customer} ในระบบ Ecom กรุณาตรวจสอบ";
        }

      }
      else
      {
        $sc = FALSE;
        $error = "Document error : ไม่พบรหัสคลัง {$wh->code} ในระบบ IX";
      }
    }
    else
    {
      $sc = FALSE;
      $error = "ไม่พบโซน {$consign->id_zone}";
    }
  }

  if($sc === TRUE)
  {
    dbQuery("UPDATE tbl_consign SET ix = 1 WHERE id = {$consign->id}");
  }
  else
  {
    dbQuery("UPDATE tbl_consign SET ix = 3, ix_error = '{$error}' WHERE id = {$consign->id}");
  }
}
else
{
  $sc = FALSE;
  $error = "ไม่พบ Consign ID ";
}

echo $sc === TRUE ? 'success' : $error;
 ?>
