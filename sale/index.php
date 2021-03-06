<?php
require_once '../library/config.php';
require_once '../library/functions.php';
require_once "../invent/function/tools.php";

//---	Check login
if( getCookie('sale_id') === FALSE)
{
	header('Location: login.php');
	exit;
}


//---	Logout
if(isset($_GET['logout']))
{
	deleteCookie('user_id');
	deleteCookie('sale_id');
	deleteCookie('UserName');
	header('Location: index.php');
	exit;
}



if( ! getConfig("CLOSED") )
{

	$content = '';


	$page = (isset($_GET['content'])&& $_GET['content'] !='')?$_GET['content']:'';
	switch($page){
		case "order":
			$content = "order.php";
			$pageTitle = "ออเดอร์";
			break;

		case 'check_stock' :
			$content = 'check_stock.php';
			$pageTitle = 'เช็คสต็อก';
			break;

		case 'category':
			$content = 'category.php';
			$pageTitle = 'สินค้า';
			break;

		case 'product':
			$content = 'product.php';
			$pageTitle = 'สินค้า';
			break;

		case "cart":
			$content = "cart.php";
			$pageTitle = "สรุปรายการ";
			break;

		case "dashboard":
			$content = "dashboard.php";
			$pageTitle = "สรุปยอดขาย (ไม่รวม VAT)";
			break;

		case "tracking":
			$content = "order_tracking.php";
			$pageTitle = "ติดตามออเดอร์";
			break;

		case "request" :
			$content = "request/index.php";
			$pageTitle = "สั่งจองสินค้า";
			break;

		case "Employee":
			$content= "employee.php";
			$pageTitle = "reset password";
			break;

		default:
			$content = "main.php"; //'home.php';
			$pageTitle = 'Sale';
			break;
	}

	require_once 'template.php';
}
else
{
	require '../invent/maintenance.php';
}
?>
