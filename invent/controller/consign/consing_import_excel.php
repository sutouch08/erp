<?php
$sc = TRUE;
$id_consign = $_POST['id_consign'];
$cs = new consign($id_consign);
if($cs->isSaved == 0 && $cs->isCancle == 0)
{
  $file = isset( $_FILES['uploadFile'] ) ? $_FILES['uploadFile'] : FALSE;
  $file_path 	= "../../upload/consign/";
  $upload	= new upload($file);

  $zone = new zone($cs->id_zone);
  $st = new stock();

  //--- ไว้ตรวจสอบว่ามียอดต่างบ้างหรือไม่
  //--- จำนวนจะถูกเพิ่มเมื่อรายการมียอดต่าง
  $totalDiff = 0;


  $upload->file_new_name_body = $cs->reference.'-'.date('YmdHis');
  $upload->file_overwrite     = TRUE;
  $upload->auto_create_dir    = FALSE;

  $upload->process($file_path);

  if( ! $upload->processed)
  {
    $sc = FALSE;
    $message = $upload->error;
  }
  else
  {
    $reader = new PHPExcel_Reader_Excel2007();
    $reader->setReadDataOnly(TRUE);
    $excel = $reader->load($upload->file_dst_pathname);
    $collection	= $excel->getActiveSheet()->toArray(NULL, TRUE, TRUE, TRUE);

    $pd = new product();
    $mv = new movement();
    $st = new stock();
    $zone = new zone();
    $wh = new warehouse();

    $reference = 'บันทึกยอดยกมา';
    $date_upd = date('Y-m-d H:i:s');

      $i = 1;
      foreach($collection as $rs)
      {
        if($i > 1)
        {
          //--- เตรียมข้อมูลสำหรับนำเข้ายอดต่าง
          $qty = $rs['C'];

          //--- เอาเฉพาะที่มียอดต่าง และ ยอดต่างต้องไม่ติดลบ
          if($qty > 0)
          {
            //--- สินค้าคงเหลือในโซนพอตัดหรือไม่
            $isEnough = $st->isEnough($id_zone, $rs->id_product, $qty);

            //--- ถ้าสินค้าคงเหลือในโซนพอตัด หรือ คลังนี้สามารถติดลบได้
            if( $isEnough === TRUE OR $zone->allowUnderZero === TRUE)
            {
              $pd = new product($rs->id_product);
              //--- ดึง GP จากเอกสารฝากขายล่าสุด
              $gp = $cs->getProductGP($rs->id_product, $id_zone);

              //--- แปลงเป็นส่วนลด (Label)
              $disc = $gp > 0 ? $gp.' %' : 0;

              //--- แปลงเป็นส่วนลดรวม (ยอดเงิน)
              $discAmount = $gp > 0 ? (($gp * 0.01) * $pd->price) * $diff : 0;

              //--- มูลค่าหลังหักส่วนลด (ยอดเงิน)
              $totalAmount = ($diff * $pd->price) - $discAmount;

              $arr = array(
                'id_consign' => $cs->id,
                'id_style' => $pd->id_style,
                'id_product' => $pd->id,
                'product_code' => $pd->code,
                'product_name' => $pd->name,
                'cost' => $pd->cost,
                'price' => $pd->price,
                'qty' => $diff,
                'discount' => $disc,
                'discount_amount' => $discAmount,
                'total_amount' => $totalAmount,
                'id_consign_check_detail' => $rs->id,
                'input_type' => 1 //--- 0 = hand, 1 = load diff, 2 = excel
              );

              if($cs->addDetail($arr) === FALSE)
              {
                $sc = FALSE;
                $message = 'เพิ่มรายการ '.$pd->code.' จำนวน '.$diff.' ไม่สำเร็จ';
              }
              else
              {
                $totalDiff += $diff;
              }

            }
            else
            {
              $sc = FALSE;
              $message =  $rs->product_code.' ยอดในโซนไม่พอตัด';
            } //--- End if isEnough

          } //--- end if diff > 0

          ////-------
          $import++;
          $id_zone = $zone->getId($rs['A']);
          $id_wh = $id_zone === FALSE ? FALSE : $zone->getWarehouseId($id_zone);
          $id_pd = $pd->getId($rs['B']);
          $qty = $rs['C'];

          if( $id_zone === FALSE OR $id_wh === FALSE)
          {
            $noZone++;
            writeErrorLogs('Zone Error' ,$rs['A'].' : '.$rs['B'].' : '.$qty);
          }
          else if($id_pd === FALSE)
          {
            $noProduct++;
            writeErrorLogs('Product Error' ,$rs['A'].' : '. $rs['B'].' : '.$qty);
          }
          else
          {

            //--- import stock
            $stockImported++;
            if($st->updateStockZone($id_zone, $id_pd, $qty) !== TRUE)
            {
              $stockImportError++;
              writeErrorLogs('Import Stock Error', $rs['A'].' : '.$rs['B'] .' : '. $qty.' : '.$st->error);
            }
            else
            {
              $stockImportSuccess++;

              //--- import movement
              $movementImported++;
              if($mv->move_in($reference, $id_wh, $id_zone, $id_pd, $qty, $date_upd) !== TRUE)
              {
                $sc = FALSE;
                $movementImportError++;
                writeErrorLogs('Import Movement Error', $rs['A'].' : '.$rs['B'] .' : '. $mv->error);
              }
              else
              {
                $movementImportSuccess++;
              }

            }

          }

        }

        $i++;
      }

    }
    $upload->clean();
  }
  else
  {
    $sc = FALSE;
    if($cs->isSaved == 1)
    {
      $message = 'ไม่สามารถโหลดยอดต่างเข้าเอกสารที่บันทึกแล้ว';
    }
    else if($cs->isCancle == 1)
    {
      $message = 'ไม่สามารถโหลดยอดต่างเข้าเอกสารที่ยกเลิกแล้ว';
    }
    else if($cs->id_consign_check != 0)
    {
      $message = 'สามารถโหลดยอดต่างได้เพียง 1 เอกสารเท่านั้น';
    }
  }

 ?>
