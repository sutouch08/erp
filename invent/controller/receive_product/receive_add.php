<?php
$sc = TRUE;
$po 			= new po();
$pd 			= new product();
$zn			  = new zone();
$cs 			= new receive_product();
$st			  = new stock();
$mv			  = new movement();
$cost     = new product_cost();

$poCode		= trim( $_GET['po'] );

if( $po->hasPO($poCode) === TRUE )
{
  $date_add	= dbDate( $_GET['date'], TRUE );
  $id_supplier	= $po->getSupplierId($poCode);
  $invoice		= trim( $_GET['invoice'] );
  $remark		= trim( $_GET['remark'] );
  $id_zone		= $_GET['id_zone'];
  $id_wh		= $zn->getWarehouseId($id_zone);
  $bookcode 	= getConfig('BOOKCODE_BI');
  $ds			= $_POST['receive'];
  $data			= array();
  foreach( $ds as $id => $val )
  {
    if( is_numeric($val) )
    {
      $data[$id]	= $val;
    }
  }
  if( count( $data ) > 0 )
  {
    startTransection();
    $reference	= $cs->getNewReference($date_add);
    if( $_POST['approvKey'] == "" )
    {
      $arr = array(
                  "bookcode"	=> $bookcode,
                  "reference"	=> $reference,
                  "id_supplier"	=> $id_supplier,
                  "po" 			=> $poCode,
                  "invoice" 		=> $invoice,
                  "date_add" 	=> $date_add,
                  "id_employee" => getCookie('user_id'),
                  "remark"		=> $remark
                );
    }
    else
    {
      $arr = array(
                  "bookcode"	=> $bookcode,
                  "reference"	=> $reference,
                  "id_supplier"	=> $id_supplier,
                  "po" 			=> $poCode,
                  "invoice" 		=> $invoice,
                  "date_add" 	=> $date_add,
                  "id_employee" => getCookie('user_id'),
                  "remark"		=> $remark,
                  "approver"	=> $_POST['id_emp'],
                  "approvKey"	=> $_POST['approvKey']
                );
    }



      if( $cs->add($arr) !== TRUE )
      {
        $sc = FALSE;
        $message = 'เพิ่มเอกสารไม่สำเร็จ';
      }
      else
      {
        $id_receive_product = $cs->get_id($reference);

        if( $id_receive_product != FALSE )
        {
          foreach( $data as $id_pd => $qty )
          {
            $arr = array(
                    "id_receive_product"	=> $id_receive_product,
                    "id_style"					=> $pd->getStyleId($id_pd),
                    "id_product"				=> $id_pd,
                    "qty"						=> $qty,
                    "cost"              => $po->getPrice($poCode, $id_pd),
                    "id_warehouse"			=> $id_wh,
                    "id_zone"					=> $id_zone
                    );

            //------ เพิ่มรายการรับเข้า
            if($cs->insertDetail($arr) !== TRUE)
            {
              $sc = FALSE;
              $message = 'เพิ่มรายการรับเข้าไม่สำเร็จ';
            }

            //------ ปรับยอดสต็อก
            if($st->updateStockZone($id_zone, $id_pd, $qty) !== TRUE )
            {
              $sc = FALSE;
              $message = 'ปรับยอดสต็อกเข้าโซนไม่สำเร็จ';
            }

            //---- เพิ่มรายการต้นทุนสินค้าใน tbl_product_cost
            if($cost->addCostList($id_pd, $po->getProductPrice($poCode, $id_pd), $qty, $date_add)  !== TRUE)
            {
              $sc = FALSE;
              $message = 'บันทึกต้นทุนสินค้ารับเข้าไม่สำเร็จ';
            }

            //---- บันทึก movement
            if($mv->move_in( $reference, $id_wh, $id_zone, $id_pd, $qty, dbDate($_GET['date'], TRUE) ) !== TRUE)
            {
              $sc = FALSE;
              $message = 'บันทึก movement ไม่สำเร็จ';
            }

            //--- บันทึกยอดรับใน PO
            if($po->received($poCode, $id_pd, $qty) !== TRUE)
            {
              $sc = FALSE;
              $message = 'ปรับปรุงยอดรับแล้วในใบสั่งซื้อไม่สำเร็จ';
            }

          }//--- foreach data

        } //--- endif $cs->add


        if( $sc === TRUE )
        {
          commitTransection();
        }
        else
        {
          dbRollback();
        }
      }


      endTransection();
  }
  else //-- if count
  {
    $sc = FALSE;
    $message = "ไม่พบรายการรับเข้า";

  }//--- if count
}
else //---- if hasPO
{
  $sc = FALSE;
  $message = "ใบสั่งซื้อไม่ถูกต้อง ถูกปิด หรือ ถูกยกเลิก";
}//--- if hasPO

echo $sc === TRUE ? 'success | '.$id_receive_product : 'fail | '.$message;

 ?>
