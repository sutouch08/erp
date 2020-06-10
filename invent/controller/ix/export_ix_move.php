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
$mv = new move($id);

if(!empty($mv->id))
{
  //--- ตรวจสอบเอกสารซ้ำ
  if(! is_mv_exists_in_ix($mv->reference))
  {
    //--- 1. add document
    $wh = new warehouse($mv->id_warehouse);
    $warehouse = get_IX_Warehouse($wh->code);
    if(!empty($warehouse))
    {

      $arr = array(
        'code' => $mv->reference,
        'bookcode' => 'MV',
        'from_warehouse' => $warehouse->code,
        'to_warehouse' => $warehouse->code,
        'remark' => get_null($mv->remark),
        'date_add' => $mv->date_add,
        'status' => 1
      );

      startTransection2();

      if(add_ix_move($arr) === TRUE)
      {
        //--- add move details
        $details = $mv->get_details($mv->id);
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
                $error = "Move detail error : ไม่พบโซน {$rs->from_zone_code} ในระบบ IX";
                break;
              }

              if(empty($to_zone))
              {
                $sc = FALSE;
                $error = "Move detail error : ไม่พบโซน {$rs->from_zone_code} ในระบบ IX";
                break;
              }

              if($sc === TRUE)
              {
                //---- add detail data
                $ds = array(
                  'move_code' => $mv->reference,
                  'product_code' => $item->code,
                  'product_name' => $item->name,
                  'from_zone' => $from_zone->code,
                  'to_zone' => $to_zone->code,
                  'qty' => $rs->qty,
                  'valid' => $rs->valid
                );

                if(add_ix_move_detail($ds))
                {
                  //--- update movement
                  $move_out = array(
                    'reference' => $mv->reference,
                    'warehouse_code' => $warehouse->code,
                    'zone_code' => $from_zone->code,
                    'product_code' => $item->code,
                    'move_in' => 0,
                    'move_out' => $rs->qty,
                    'date_add' => $mv->date_add
                  );

                  $move_in = array(
                    'reference' => $mv->reference,
                    'warehouse_code' => $warehouse->code,
                    'zone_code' => $to_zone->code,
                    'product_code' => $item->code,
                    'move_in' => $rs->qty,
                    'move_out' => 0,
                    'date_add' => $mv->date_add
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
                  $error = "Move detail error : เพิ่มรายการ {$rs->product_code} ต้นทาง {$rs->from_zone_code} ปลายทาง {$rs->to_zone_code} ไม่สำเร็จ";
                  break;
                }
              }
            }
            else
            {
              $sc = FALSE;
              $error = "Move detail error : ไม่พบรหัสสินค้า {$rs->product_code} ในระบบ IX";
              break;
            }


          } //--- enf foreach
        }
        else
        {
          $sc = FALSE;
          $error = "Move detail error : ไม่พบรายการในเอกสาร";
        }

      }
      else
      {
        $sc = FALSE;
        $error = "Document error : เพิ่มเอกสาร {$mv->reference} ไม่สำเร็จ";
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
    else
    {
      $sc = FALSE;
      $error = "Document error : ไม่พบรหัสคลัง {$wh->code} ในระบบ IX";
    }
  }

  if($sc === TRUE)
  {
    dbQuery("UPDATE tbl_move SET ix = 1 WHERE id = {$mv->id}");
  }
  else
  {
    dbQuery("UPDATE tbl_move SET ix = 3, ix_error = '{$error}' WHERE id = {$mv->id}");
  }

}
else
{
  $sc = FALSE;
  $error = "ไม่พบ MV ID";
}

echo $sc === TRUE ? 'success' : $error;
 ?>
