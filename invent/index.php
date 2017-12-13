<?php
require_once '../library/config.php';
require_once '../library/functions.php';
require_once 'function/tools.php';
if( !getConfig('CLOSED') )
{
	checkUser();
	$user_id = $_COOKIE['user_id'];

	$id_profile = getProfile($user_id);

	$viewStockOnly = isViewStockOnly($id_profile);

	$id_tab = '';

	$fast_qc = getConfig('FAST_QC');

	$content = 'main.php';

	$page = ( isset($_GET['content'] ) && $_GET['content'] != '' ) ? $_GET['content'] : '' ;

switch($page){

//**********  ระบบคลังสินค้า  **********//
		case 'test_run' :
			$content = 'test.php';
			$pageTitle = 'แสดงผลการทดสอบระบบ';
			break;


		case 'syncData' :
			$content = 'syncData.php';
			$pageTitle = 'นำเข้าข้อมูลจาก formula';
			break;

		case 'receive_product':
			$content = 'receive_product.php';
			$pageTitle = 'รับสินค้าเข้า';
			break;

		case 'receive_transform' :
			$content 		= 'receive_transform.php';
			$pageTitle	= 'รับเข้าจากการแปรสภาพ';
			break;

		case 'return_order':
			$content = 'return_order.php';
			$pageTitle = 'รับคืนสินค้าจากการขาย(ลดหนี้)';
			break;

		case 'sponsor_return':
			$content = 'sponsor_return.php';
			$pageTitle = 'รับคืนสินค้าสปอนเซอร์';
			break;

		case 'support_return':
			$content = 'support_return.php';
			$pageTitle = 'รับคืนสินค้าอภินันท์';
			break;

		case 'order_transform' :
			$content	= 'order_transform.php';
			$pageTitle	= 'เบิกแปรสภาพ';
			break;

		case 'order_lend';
			$content = 'order_lend.php';
			$pageTitle = 'ยืมสินค้า';
			break;

		case 'move':
			$content = 'move.php';
			$pageTitle = 'ย้ายพื้นที่จัดเก็บ';
			break;

		case 'adjust':
			$content = 'adjust.php';
			$pageTitle = 'ปรับปรุงสต็อก';
			break;

		case 'transfer':
			$content = 'transfer.php';
			$pageTitle = 'โอนคลัง';
			break;

//**********  ระบบขาย  **********//
		case 'order':
			$content = 'order.php';
			$pageTitle= 'ออเดอร์';
			break;

		case 'order_online' :
			$content = 'order_online.php';
			$pageTitle = 'online Sale';
			break;

		case 'order_sponsor';
			$content = 'order_sponsor.php';
			$pageTitle = 'สปอนเซอร์สโมสร';
			break;

		case 'order_support' :
			$content = 'order_support.php';
			$pageTitle = 'เบิกอภินันทนาการ';
			break;

		case 'order_consign';
			$content = 'order_consign.php';
			$pageTitle = 'ฝากขาย [ใบกำกับภาษี]';
			break;

		case 'order_consignment';
			$content = 'order_consignment.php';
			$pageTitle = 'ฝากขาย [โอนคลัง]';
			break;

		case 'prepare':
			$content = 'prepare.php';
			$pageTitle = 'จัดสินค้า';
			break;

		case 'qc':
			$content = 'qc.php';
			$pageTitle = 'ตรวจสินค้า';
			break;

		case 'bill':
			$content = 'bill.php';
			$pageTitle = 'รายการรอเปิดบิล';
			break;

		case 'order_closed' :
			$content = 'order_closed.php';
			$pageTitle = 'รายการเปิดบิลแล้ว';
			break;

//**********  ระบบบัญชี  **********//

		case 'consign':
			$content = 'consign.php';
			$pageTitle = 'ตัดยอดฝากขาย';
			break;

		case 'payment_order' :
			$content 	= 'payment_order.php';
			$pageTitle	= 'ตรวจสอบยอดชำระ';
			break;


//**********  ระบบซื้อ  **********//
		case 'po' :
			$content		= 'po.php';
			$pageTitle	= 'สั่งซื้อ';
			break;

//**********  รายงาน  **********//


//**********  การตั้งค่า  **********//
		case 'config';
			$content = 'setting.php';
			$pageTitle = 'การตั้งค่า';
			break;

		case 'popup' :
			$content = 'popup.php';
			$pageTitle = 'การแจ้งเตือน';
			break;

		case 'securable':
			$content = 'securable.php';
			$pageTitle = 'กำหนดสิทธิ์';
			break;

		case 'discount_policy' :
			$content = 'discount_policy.php';
			$pageTitle = 'เพิ่ม/แก้ไข นโยบายส่วนลด';
			break;

//**********  ฐานข้อมูล  **********//
	//*****  สินค้า  *****//
		case 'product':
			$content = 'product.php';
			$pageTitle = 'รายการสินค้า';
			break;

		case 'style' :
			$content = 'style.php';
			$pageTitle = 'เพิ่ม/แก้ไข รุ่นสินค้า';
			break;

		case 'kind' :
			$content = 'product_kind.php';
			$pageTitle = 'เพิ่ม/แก้ไข ประเภทสินค้า';
			break;

		case 'type' :
			$content = 'product_type.php';
			$pageTitle = 'เพิ่ม/แก้ไข ชนิดสินค้า';
			break;

		case 'category':
			$content = 'product_category.php';
			$pageTitle = 'หมวดหมู่สินค้า';
			break;

		case 'product_group' :
			$content = 'product_group.php';
			$pageTitle = 'เพิ่ม/แก้ไข กลุ่มสินค้า';
			break;

		case 'product_sub_group' :
			$content = 'product_sub_group.php';
			$pageTitle = 'เพิ่ม/แก้ไข กลุ่มย่อยสินค้า';
			break;

		case 'product_tab' :
			$content = 'product_tab.php';
			$pageTitle = 'เพิ่ม/แก้ไข แถบแสดงสินค้า';
			break;

		case 'unit' :
			$content = 'unit.php';
			$pageTitle = 'หน่วยนับ';
			break;

		case 'color':
			$content = 'color.php';
			$pageTitle = 'เพิ่ม/แก้ไข สี';
			break;

		case 'color_group':
			$content = 'color_group.php';
			$pageTitle = 'กลุ่มสี';
			break;

		case 'size':
			$content = 'size.php';
			$pageTitle = 'เพิ่ม/แก้ไข ขนาดสินค้า';
			break;

		case 'brand' :
			$content 	= 'brand.php';
			$pageTitle = 'เพิ่ม/แก้ไข ยี่ห้อสินค้า';
			break;

		case 'barcode' :
			$content = 'barcode.php';
			$pageTitle = 'บาร์โค้ด';
			break;

	//*****  คลังสินค้า  *****//
		case 'warehouse':
			$content = 'warehouse.php';
			$pageTitle = 'เพิ่ม/แก้ไข คลังสินค้า';
			break;

		case 'zone':
			$content = 'zone.php';
			$pageTitle = 'เพิ่ม/แก้ไข โซนสินค้า';
			break;


	//*****  ลูกค้า  *****//
		case 'customer';
			$content='customer.php';
			$pageTitle='ข้อมูลลูกค้า';
			break;

		case 'customer_address':
			$content = 'customer_address.php';
			$pageTitle = 'เพิ่ม/แก้ไข ที่อยู่สำหรับจัดส่ง';
			break;

		case 'customer_group':
			$content = 'customer_group.php';
			$pageTitle = 'เพิ่ม/แก้ไข กลุ่มลูกค้า';
			break;

		case 'customer_kind':
			$content = 'customer_kind.php';
			$pageTitle = 'เพิ่ม/แก้ไข ประเภทลูกค้า';
			break;

		case 'customer_type':
			$content = 'customer_type.php';
			$pageTitle = 'เพิ่ม/แก้ไข ชนิดลูกค้า';
			break;

		case 'customer_class':
			$content = 'customer_class.php';
			$pageTitle = 'เพิ่ม/แก้ไข เกรดลูกค้า';
			break;

		case 'customer_area' :
			$content = 'customer_area.php';
			$pageTitle = 'เพิ่ม/แก้ไข เขตลูกค้า';
			break;

		case 'customer_credit' :
			$content = 'customer_credit.php';
			$pageTitle = 'วงเงินเครดิตคงเหลือ';
			break;

		case 'sponsor' :
			$content = 'sponsor.php';
			$pageTitle = 'เพิ่ม/แก้ไข รายชื่อสปอนเซอร์';
			break;

	//*****  พนักงาน  *****//
		case 'Employee':
			$content = 'employee.php';
			$pageTitle = 'พนักงาน';
			break;

		case 'sale_group' :
			$content = 'sale_group.php';
			$pageTitle = 'ทีมขาย';
			break;

		case 'sale';
			$content = 'sale.php';
			$pageTitle = 'พนักงานขาย';
			break;

		case 'Profile':
			$content = 'profile.php';
			$pageTitle = 'โปรไฟล์';
			break;

		case 'support' :
			$content = 'support.php';
			$pageTitle = 'เพิ่ม/แก้ไข รายชื่ออภินันทนาการ';
			break;

		//******** ผู้จำหน่าย  *******//
		case 'supplier' :
			$content 		= 'supplier.php';
			$pageTitle	= 'ผู้จำหน่าย';
		break;

		case 'supplier_group' :
			$content 		= 'supplier_group.php';
			$pageTitle	= 'กลุ่มผู้จำหน่าย';
			break;

	//*****  อื่นๆ  *****//
		case 'channels' :
			$content = 'channels.php';
			$pageTitle = 'ช่องทางการขาย';
			break;

		case 'payment_method' :
			$content = 'payment_method.php';
			$pageTitle = 'ช่องทางการชำระเงิน';
			break;

		case 'branch' :
			$content = 'branch.php';
			$pageTitle = 'สาขา';
			break;

		case 'sender' :
			$content = 'sender.php';
			$pageTitle = 'ผู้ให้บริการจัดส่ง';
			break;

		case 'transport' :
			$content = 'transport.php';
			$pageTitle = 'เพิ่มการจัดส่ง';
			break;

		case 'bank_account' :
			$content = 'bank_account.php';
			$pageTitle = 'บัญชีธนาคาร';
			break;


		//-------	รายงานระบบขาย
		case 'sale_deep_analyz' :
			$content = 'report/sales/sale_deep_analyz.php';
			$pageTitle = 'รายงานวิเคราะห์ขายแบบละเอียด';
			break;

		default:
			$content = 'main.php';
			$pageTitle = 'Smart Inventory';
			break;




}

if( $viewStockOnly === TRUE )
{
	$content = 'view_stock.php';
	$pageTitle = 'View Stock';
}
require_once 'template.php';
}
else
{
	require_once 'maintenance.php';
}
?>
