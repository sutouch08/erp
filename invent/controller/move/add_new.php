<?php
  $sc = TRUE;
  $error = "";
  $cs = new move();
  $id_warehouse = trim($_POST['id_warehouse']);
  if(empty($id_warehouse))
  {
    $sc = FALSE;
    $error = "รหัสคลังไม่ถูกต้อง";
  }
  else
  {
    $warehouse = new warehouse($id_warehouse);
    if(!empty($warehouse->id))
    {
      //--- เตรียมข้อมูลสำหรับเพิ่มเอกสาร
      $arr = array(
        'reference'      => $cs->getNewReference(),
        'id_warehouse'   => $id_warehouse,
        'id_employee'    => getCookie('user_id'),
        'remark'         => $_POST['remark']
      );

      //--- เพิ่มเอกสาร ถ้าสำเร็จจะได้ ไอดีกลับมา ถ้าไม่สำเร็จจะได้ FALSE;
      $rs = $cs->add($arr);

      //--- ถ้าสำเร็จ
      if( $rs !== FALSE )
      {
        //--- เตรียมส่งไอดีกลับ
        $id = $rs;
      }
      else
      {
        $sc = FALSE;
        $error = 'เพิ่มเอกสารไม่สำเร็จ';
      }
    }
    else
    {
      $sc = FALSE;
      $error = "คลังสินค้าไม่ถูกต้อง";
    }
  }


  echo $sc === TRUE ? $id : $error;

 ?>
