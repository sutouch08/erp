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
$tr = new transfer($id);

if(!empty($tr->id) && $tr->isSaved == 1 && $tr->isCancle == 0)
{
  $is_complete = $tr->is_complete($tr->id);
  if(! $is_complete)
  {
    $sc = FALSE;
    $error = "Document error : เอกสารยัง {$tr->reference} ไม่สมบูรณ์";
  }

  //--- ตรวจสอบเอกสารซ้ำ
  if($sc === TRUE && ! is_tr_exists_in_ix($tr->reference))
  {

    //--- 1. add document
    $wh = new warehouse();
    $fromWhCode = $wh->getCode($tr->from_warehouse);
    $toWhCode = $wh->getCode($tr->to_warehouse);

    $from_warehouse = get_IX_Warehouse($fromWhCode);
    $to_warehouse = get_IX_Warehouse($toWhCode);

    if(empty($from_warehouse))
    {
      $sc = FALSE;
      $error = "Document error : ไม่พบคลัง {$fromWhCode} ในระบบ IX";
    }

    if(empty($to_warehouse))
    {
      $sc = FALSE;
      $error = "Document error : ไม่พบคลัง {$toWhCode} ในระบบ IX";
    }

    if($sc === TRUE)
    {
      $arr = array(
        'code' => $tr->reference,
        'bookcode' => 'WW',
        'from_warehouse' => $from_warehouse->code,
        'to_warehouse' => $to_warehouse->code,
        'remark' => get_null($tr->remark),
        'date_add' => $tr->date_add,
        'status' => 1
      );

      startTransection2();

      if(add_ix_transfer($arr) === TRUE)
      {
        //--- add move details
        $details = $tr->get_details($tr->id);
        if(! empty($details))
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

              $from_zone = get_ix_zone($rs->from_zone_code);
              $to_zone = get_ix_zone($rs->to_zone_code);

              if(empty($from_zone))
              {
                $sc = FALSE;
                $error = "add detail error : ไม่พบโซน {$rs->from_zone_code} ในระบบ IX";
                break;
              }

              if(empty($to_zone))
              {
                $sc = FALSE;
                $error = "add detail error : ไม่พบโซน {$rs->to_zone_code} ในระบบ IX";
                break;
              }

              if($sc === TRUE)
              {
                //---- add detail data
                $ds = array(
                  'transfer_code' => $tr->reference,
                  'product_code' => $item->code,
                  'product_name' => $item->name,
                  'from_zone' => $from_zone->code,
                  'to_zone' => $to_zone->code,
                  'qty' => $rs->qty,
                  'valid' => $rs->valid
                );

                if(add_ix_transfer_detail($ds))
                {
                  //--- update movement
                  $move_out = array(
                    'reference' => $tr->reference,
                    'warehouse_code' => $from_warehouse->code,
                    'zone_code' => $from_zone->code,
                    'product_code' => $item->code,
                    'move_in' => 0,
                    'move_out' => $rs->qty,
                    'date_add' => $tr->date_add
                  );

                  $move_in = array(
                    'reference' => $tr->reference,
                    'warehouse_code' => $to_warehouse->code,
                    'zone_code' => $to_zone->code,
                    'product_code' => $item->code,
                    'move_in' => $rs->qty,
                    'move_out' => 0,
                    'date_add' => $tr->date_add
                  );

                  if(! add_ix_move_out($move_out))
                  {
                    $sc = FALSE;
                    $error = "movement error : เพิ่ม movement out ({$item->code}) ไม่สำเร็จ";
                    break;
                  }

                  if(! add_ix_move_in($move_in))
                  {
                    $sc = FALSE;
                    $error = "movement error : เพิ่ม movement in ({$item->code}) ไม่สำเร็จ";
                    break;
                  }

                }
                else
                {
                  $sc = FALSE;
                  $error = "add detail error : เพิ่มรายการ {$rs->product_code} ต้นทาง {$rs->from_zone_code} ปลายทาง {$rs->to_zone_code} ไม่สำเร็จ";
                  break;
                }
              }
            }
            else
            {
              $sc = FALSE;
              $error = "add detail error : ไม่พบรหัสสินค้า {$rs->product_code} ในระบบ IX";
              break;
            }


          } //--- enf foreach
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
        $error = "Document error : เพิ่มเอกสาร {$tr->reference} ไม่สำเร็จ";
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

  if($sc === TRUE)
  {
    dbQuery("UPDATE tbl_transfer SET ix = 1, ix_error = NULL WHERE id = {$tr->id}");
  }
  else
  {
    dbQuery("UPDATE tbl_transfer SET ix = 3, ix_error = '{$error}' WHERE id = {$tr->id}");
  }

}
else
{
  $sc = FALSE;
  $error = "ไม่พบ TR ID";
}

echo $sc === TRUE ? 'success' : $error;
 ?>
