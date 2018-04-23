<?php
//---	ไว้ตรวจสอบผลลัพภ์
$sc	= TRUE;

//---
$id = $_POST['id_receive_product'];

//---	เลขที่ใบสั่งซื้อ
$poCode = trim($_POST['poCode'] );

$po = new po($poCode);

//---	เลขที่ใบรับสินค้า (ไม่บังคับ)
$invoice	= trim(addslashes($_POST['invoice'] ));

//--- id zone
$id_zone = $_POST['id_zone'];

//--- approve key สำหรับอนุมัติรับเกินใบสั่งซื้อ
$approvKey = $_POST['approvKey'];

//--- id emp คนอนุมัติ
$id_emp = $_POST['id_emp'];



$cs = new receive_product($id);

if($cs->isSaved == 1)
{
  $sc = FALSE;
  $message = 'เอกสารถูกบันทึกไปแล้วโดยผู้อื่น';
}
else
{

  startTransection();
  //--- update Document
  $arr = array(
    'id_supplier' => $po->id_supplier,
    'po' => $poCode,
    'invoice' => $invoice,
    'approver' => $id_emp,
    'approvKey' => $approvKey,
    'isSaved' => 1
  );


  if($cs->update($cs->id, $arr) !== TRUE)
  {
    $sc = FALSE;
    $message = 'ปรับปรุงข้อมูลเอกสารไม่สำเร็จ';
  }
  else
  {
    $product	 = new product();
    $zone      = new zone();
    $st			   = new stock();
    $mv			   = new movement();
    $cost      = new product_cost();

    if($po->status != 3)
    {

      //---	ไอดีของคลังที่จะรับเข้า
      $id_wh		= $zone->getWarehouseId($id_zone);

      //---	รายการที่มีการคีย์ยอดรับเข้ามา
      $data				= $_POST['receive'];

      //--- วันที่เอกสาร
      $date_add = $cs->date_add;

      //--- เลขที่เอกสาร
      $reference = $cs->reference;


      if( count( $data ) > 0 )
      {
        foreach( $data as $id_pd => $qty )
        {
          if($sc === FALSE)
          {
            break;
          }

          $pdCost = $po->getPrice($poCode, $id_pd);
          $arr = array(
                  "id_receive_product"	=> $id,
                  "id_style"	=> $product->getStyleId($id_pd),
                  "id_product" => $id_pd,
                  "qty"	=> $qty,
                  "cost" => $pdCost,
                  "id_warehouse" => $id_wh,
                  "id_zone"	=> $id_zone
                );

              //------ เพิ่มรายการรับเข้า
          if( $cs->insertDetail($arr) !== TRUE)
          {
            $sc = FALSE;
            $message = 'เพิ่มรายการรับเข้าไม่สำเร็จ';
            break;
          }

          //---	บันทึกยอดสต็อกเข้าโซนที่รับสินค้าเข้า
          if( $st->updateStockZone($id_zone, $id_pd, $qty) !== TRUE )
          {
            $sc = FALSE;
            $message = 'บันทึกยอดสต็อกเข้าโซนไม่สำเร็จ';
            break;
          }

          $cost->addCostList($id_pd, $pdCost, $qty, $date_add);

          //---	บันทึก movement เข้าโซนที่รับสินคาเข้า
          if( $mv->move_in( $reference, $id_wh, $id_zone, $id_pd, $qty, $date_add ) !== TRUE)
          {
            $sc = FALSE;
            $message = 'บันทึก movement ไม่สำเร็จ';
            break;
          }

          //--- บันทึกยอดรับใน PO
          if($po->received($poCode, $id_pd, $qty) !== TRUE)
          {
            $sc = FALSE;
            $message = 'ปรับปรุงยอดรับแล้วในใบสั่งซื้อไม่สำเร็จ';
            break;
          }

        }	//--- foreach data

      }
      else //-- if count
      {
        $sc = FALSE;
        $message = "ไม่พบรายการรับเข้า";

      }//--- if count

    }
    else
    {
      $sc = FALSE;
      $message = "ใบสั่งซื้อไม่ถูกต้อง ถูกปิด หรือ ถูกยกเลิก";

    }

  } //--- end if update


  if( $sc === TRUE )
  {
    commitTransection();
  }
  else
  {
    dbRollback();
  }

  endTransection();


  //--- ถ้ารับเข้าครบ PO แล้ว
  if($po->isCompleted($poCode) === TRUE && getConfig('PO_AUTO_CLOSE') == 1)
  {
    $po->close($po->bookcode, $poCode);
  }


} //-- end if isSaved


echo $sc === TRUE ? 'success' : $message;

 ?>
