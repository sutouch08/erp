<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";


if( isset( $_GET['deleteGroup'] ) )
{
	$sc = "success";
	$id = $_POST['id'];
	$sp = new supplier_group();
	$code = $sp->getGroupCode($id);
	if( $code !== FALSE )
	{
		if( $sp->delete($id) === FALSE )
		{
			$sc = "ลบกลุ่มไม่สำเร็จ";	
		}
	}
	else
	{
		$sc = "ไม่พบกลุ่มที่ต้องการลบ";	
	}
	echo $sc;	
}




if( isset( $_GET['deleteSupplier'] ) )
{
	$sc = 'success';
	$id = $_POST['id'];
	$emp	= getCookie('user_id');
	$sp = new supplier();
	$rs = $sp->delete($id, $emp);
	if( $rs === FALSE )
	{
		$sc = 'ลบผู้ขายไม่สำเร็จ';
	}
	echo $sc;
}



if( isset( $_GET['unDelete'] ) )
{
	$sc = "success";
	$id = $_POST['id'];
	$emp = getCookie('user_id');
	$sp = new supplier();
	if( $sp->unDelete($id, $emp) === FALSE )
	{
		$sc = "ยกเลิกการลบไม่สำเร็จ";
	}
	echo $sc;
}




if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('spCode');
	deleteCookie('spName');
	deleteCookie('spGroup');
	echo "success";	
}


if( isset( $_GET['add_new'] ) )
{	
	$qs = dbQuery("INSERT INTO tbl_supplier (code, name, credit_term, active) VALUES ('".$_POST['code']."', '".$_POST['name']."', ".$_POST['credit_term'].", ".$_POST['status'].")");
	if($qs)
	{
		$data = json_encode(array("id"=>dbInsertId(), "code"=>$_POST['code'], "name"=>$_POST['name'], "credit_term"=>$_POST['credit_term'], "status"=>$_POST['status'] == 1? "เปิดใช้งาน" : "ปิดใช้งาน"));
	}else{
		$data = "fail";
	}
	echo $data;
}

if( isset( $_GET['update'] ) && isset( $_POST['id'] ) )
{
	$qs = dbQuery("UPDATE tbl_supplier SET code = '".$_POST['code']."', name = '".$_POST['name']."', credit_term = ".$_POST['credit_term'].", active = ".$_POST['status']." WHERE id = ".$_POST['id'] );
	if( $qs )
	{
		echo "success";
	}else{
		echo "fail";
	}
}

if( isset($_GET['delete']) && isset($_POST['id']) )
{
	$rs = "fail";
	$qs = dbQuery("DELETE FROM tbl_supplier WHERE id = ".$_POST['id']);
	if($qs)
	{
		$rs = "success";
	}
	echo $rs;
}

if( isset($_GET['check_code']) )
{
	$rs = "ok";
	$qs = dbQuery("SELECT code FROM tbl_supplier WHERE code = '".$_POST['code']."' AND id != ".$_POST['id'] );
	if( dbNumRows($qs) > 0 )
	{
		$rs = "fail";
	}
	echo $rs;
}

if( isset( $_GET['check_duplicate'] ) )
{
	$rs = "ok";
	$qs = dbQuery("SELECT code FROM tbl_supplier WHERE code = '".$_POST['code']."'");
	if( dbNumRows($qs) > 0 )
	{
		$rs = "fail";
	}
	echo $rs;
}

if(isset($_GET['clear_filter']))
{
	setcookie("supplier_search_text", $text, time() - 3600, "/");
	header("location: ../index.php?content=supplier");
}

?>
