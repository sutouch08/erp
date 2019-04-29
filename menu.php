<?php if( $viewStockOnly === FALSE ) : ?>
<ul class="nav navbar-nav">
  <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cubes"></i> ระบบคลังสินค้า</a>
    <ul class="dropdown-menu">
      <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-download"></i> รับสินค้าเข้า</a>
      	<ul class="dropdown-menu">
        	<li><a href="index.php?content=receive_product"><i class="fa fa-download"></i> รับสินค้า จากการซื้อ</a></li>
          <li><a href="index.php?content=receive_transform"><i class="fa fa-download"></i> รับสินค้า จากการแปรสภาพ</a></li>
        </ul>
      </li>
      <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-download"></i> รับคืนสินค้า</a>
      	<ul class="dropdown-menu">
        	<li><a href="index.php?content=return_order"><i class="fa fa-download"></i> รับคืนสินค้า จากการขาย(ลดหนี้)</a></li>
          <li><a href="index.php?content=support_return"><i class="fa fa-download"></i> รับคืนสินค้า จากอภินันท์</a></li>
          <li><a href="index.php?content=sponsor_return"><i class="fa fa-download"></i> รับคืนสินค้า จากสปอนเซอร์</a></li>
          <li><a href="index.php?content=lend_return"><i class="fa fa-download"></i> รับคืนสินค้า จากการยืม</a></li>
        </ul>
      </li>
      <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-upload"></i> ส่งคืนสินค้า</a>
      	<ul class="dropdown-menu">
          <li><a href="index.php?content=return_received"><i class="fa fa-upload"></i> ส่งคืนสินค้า(ลดหนี้ซื้อ)</a></li>
        </ul>
      </li>
      <li class="divider"></li>
      <li><a href="index.php?content=order_transform"><i class="fa fa-upload"></i> เบิกสินค้าเพื่อแปรสภาพ</a></li>
      <li><a href="index.php?content=order_support"><i class="fa fa-upload"></i> เบิกอภินันทนาการ</a></li>
      <li><a href="index.php?content=order_lend"><i class="fa fa-upload"></i> ยืมสินค้า</a></li>
      <li class="divider"></li>
      <li><a href="index.php?content=transfer"><i class="fa fa-recycle"></i> โอนสินค้าระหว่างคลัง</a></li>
      <li><a href="index.php?content=move"><i class="fa fa-recycle"></i> ย้ายพื้นที่จัดเก็บ</a></li>
      <li><a href="index.php?content=ProductCheck"><i class="fa fa-check-square-o"></i> ตรวจนับสินค้า</a></li>
      <li><a href="index.php?content=adjust"><i class="fa fa-magic"></i> ปรับปรุงสต็อก</a></li>
      <li><a href="index.php?content=buffer_zone"><span class="glyphicon glyphicon-tasks"></span> ตรวจสอบ BUFFER ZONE</a></li>
      <li><a href="index.php?content=cancle_zone"><span class="glyphicon glyphicon-tasks"></span> ตรวจสอบ CANCLE ZONE</a></li>
      <li><a href="index.php?content=stock_movement"><span class="glyphicon glyphicon-tasks"></span> ตรวจสอบ MOVEMENT</a></li>
      <li><a href="index.php?content=stock"><span class="glyphicon glyphicon-tasks"></span> ตรวจสอบ STOCK</a></li>
      <li><a href="index.php?content=stock_recal"><span class="glyphicon glyphicon-tasks"></span> Recal Stock</a></li>
    </ul>
  </li>


  <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-shopping-bag"></i> ระบบขาย</a>
    <ul class="dropdown-menu">
      <li><a href="index.php?content=order"><i class="fa fa-shopping-bag"></i> ออเดอร์</a></li>
      <li><a href="index.php?content=order_sponsor"><i class="fa fa-ticket"></i> สปอนเซอร์ สโมสร</a></li>
      <li><a href="index.php?content=order_consign"><i class="fa fa-cloud-upload"></i> ฝากขาย [SO]</a></li>
      <li><a href="index.php?content=order_consignment"><i class="fa fa-cloud-upload"></i> ฝากขาย [TR]</a></li>
      <li><a href="index.php?content=order_online"><i class="fa fa-desktop"></i> ขายออนไลน์</a></li>
      <li class="divider"></li>
      <li><a href="index.php?content=prepare"><i class="fa fa-shopping-basket"></i> จัดสินค้า</a></li>
      <li><a href="index.php?content=qc"><i class="fa fa-check-square-o"></i> ตรวจสินค้า</a></li>
      <li><a href="index.php?content=bill"><i class="fa fa-file-text-o"></i> ออเดอร์รอเปิดบิล</a></li>
      <li><a href="index.php?content=order_closed"><i class="fa fa-file-text-o"></i> ออเดอร์เปิดบิลแล้ว</a></li>
      <li class="divider"></li>
      <li><a href="index.php?content=order_monitor"><i class="fa fa-shopping-bag"></i> ติดตามออเดอร์</a></li>
      <li><a href="index.php?content=order_expire"><i class="fa fa-history"></i> ตรวจสอบอายุออเดอร์</a></li>
    </ul>
  </li>


  <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-credit-card"></i> ระบบบัญชี</a>
    <ul class="dropdown-menu">
      <li><a href="index.php?content=consign_check"><i class="fa fa-check-square-o"></i> กระทบยอดสินค้าฝากขาย</a></li>
      <li><a href="index.php?content=consign"><i class="fa fa-check-square-o"></i> ตัดยอดฝากขาย</a></li>
      <li class="divider"></li>
      <li><a href="index.php?content=payment_order"><i class="fa fa-check-square-o"></i> ตรวจสอบยอดชำระเงิน</a></li>
    </ul>
  </li>


  <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-archive"></i> ระบบซื้อ</a>
  	<ul class="dropdown-menu">
      <li><a href="index.php?content=po"><i class="fa fa-archive"></i> ใบสั่งซื้อ(PO)</a></li>
    </ul>
  </li>


  <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart"></i> รายงาน</a>
  	<ul class="dropdown-menu">
    	<li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart"></i> รายงาน ระบบคลังสินค้า</a>
        <ul class="dropdown-menu">
          <li><a href="index.php?content=received_by_document"><i class="fa fa-bar-chart"></i>รายงาน การรับสินค้าจากการซื้อแยกตามเอกสาร</a></li>
          <li><a href="index.php?content=received_transform_by_document"><i class="fa fa-bar-chart"></i>รายงาน การรับสินค้าแปรสภาพแยกตามเอกสาร</a></li>
          <li><a href="index.php?content=stock_balance"><i class="fa fa-bar-chart"></i>รายงาน สินค้าคงเหลือ</a></li>
          <li><a href="index.php?content=current_stock"><i class="fa fa-bar-chart"></i>รายงาน สินค้าคงเหลือปัจจุบัน</a></li>
          <li><a href="index.php?content=stock_net_balance"><i class="fa fa-bar-chart"></i> รายงาน สินค้าคงเหลือ(หักยอดจอง)</a></li>
          <li><a href="index.php?content=stock_balance_by_zone"><i class="fa fa-bar-chart"></i>รายงาน สินค้าคงเหลือแยกตามโซน</a></li>
          <li><a href="index.php?content=stock_balance_compare_warehouse"><i class="fa fa-bar-chart"></i>รายงาน สินค้าคงเหลือเปรียบเทียบคลัง</a></li>
          <li><a href="index.php?content=stock_balance_by_warehouse"><i class="fa fa-bar-chart"></i>รายงาน สินค้าคงเหลือแยกตามคลัง</a></li>
          <li><a href="index.php?content=stock_year"><i class="fa fa-bar-chart"></i>รายงาน สินค้าคงเหลือแยกตามปีสินค้า</a></li>
          <li class="divider"></li>
          <li><a href="index.php?content=stock_movement_by_warehouse"><i class="fa fa-bar-chart"></i>รายงาน ความเคลื่อนไหวสินค้าแยกตามคลังสินค้า</a></li>
          <li><a href="index.php?content=stock_movement_by_product"><i class="fa fa-bar-chart"></i>รายงาน ความเคลื่อนไหวสินค้าแยกตามสินค้า</a></li>
          <li><a href="index.php?content=stock_not_move"><i class="fa fa-bar-chart"></i>รายงาน สินค้าไม่เคลื่อนไหว</a></li>
        </ul>
      </li>

      <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart"></i> รายงาน ระบบขาย</a>
      	<ul class="dropdown-menu">
          <li><a href="index.php?content=sale_by_customer_order"><i class="fa fa-bar-chart"></i> รายงาน สรุปยอดขายแยกตามลูกค้า แสดงเลขที่เอกสาร</a></li>
          <li><a href="index.php?content=sale_by_customer_items"><i class="fa fa-bar-chart"></i> รายงาน สรุปยอดขายแยกตามลูกค้า แสดงรายการสินค้า</a></li>
          <li><a href="index.php?content=sale_deep_analyz"><i class="fa fa-bar-chart"></i> รายงาน ขายแบบละเอียด</a></li>
          <li><a href="index.php?content=sale_by_channels_show_reference"><i class="fa fa-bar-chart"></i> รายงาน ยอดขายแยกตางช่องทางการขาย แสดงเลขที่เอกสาร</a></li>
          <li><a href="index.php?content=sale_online_channels_details"><i class="fa fa-bar-chart"></i> รายงาน ออเดอร์ออนไลน์ แสดงรายละเอียดการจัดส่ง</a></li>
          <li class="divider"></li>
          <li><a href="index.php?content=consignment_by_customer"><i class="fa fa-bar-chart"></i> รายงาน ฝากขายแยกตามลูกค้า(ยอดส่ง)</a></li>
        </ul>
      </li>

       <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart"></i> รายงาน ระบบซื้อ</a>
      	<ul class="dropdown-menu">
        	<li><a href="index.php?content=po_backlog"><i class="fa fa-bar-chart"></i> รายงาน ใบสั่งซื้อค้างรับ</a></li>
          <li><a href="index.php?content=po_history_by_product"><i class="fa fa-bar-chart"></i> รายงาน สรุปยอดการสั่งซื้อ ตามรุ่นสินค้า</a></li>
          <li><a href="index.php?content=po_detail"><i class="fa fa-bar-chart"></i> รายงาน รายละเอียดใบสั่งซื้อ</a></li>
        </ul>
      </li>

      <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart"></i> รายงาน ติดตาม</a>
      	<ul class="dropdown-menu">
        	<!--<li><a href="index.php?content=stock_backlogs"><i class="fa fa-bar-chart"></i> รายงาน สินค้าค้างส่ง</a></li>-->
          <li><a href="index.php?content=lend_not_return"><i class="fa fa-bar-chart"></i> รายงาน การยืมสินค้ายังไม่ส่งคืน</a></li>
        </ul>
      </li>

      <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart"></i> รายงาน ตรวจสอบ</a>
      	<ul class="dropdown-menu">
          <li><a href="index.php?content=sponsor_summary_by_budget"><i class="fa fa-bar-chart"></i> รายงาน สรุปยอดสปอนเซอร์แยกตามผู้รับ</a></li>
          <li><a href="index.php?content=sponsor_by_customer_order"><i class="fa fa-bar-chart"></i> รายงาน สรุปยอดสปอนเซอร์แยกตามผู้รับ แสดงเลขที่เอกสาร</a></li>
          <li><a href="index.php?content=sponsor_by_customer_items"><i class="fa fa-bar-chart"></i> รายงาน สรุปยอดสปอนเซอร์แยกตามผู้รับ แสดงรายการสินค้า</a></li>
          <li class="divider"></li>
          <li><a href="index.php?content=support_summary_by_budget"><i class="fa fa-bar-chart"></i> รายงาน สรุปยอดอภินันท์แยกตามผู้รับ</a></li>
          <li><a href="index.php?content=support_by_customer_order"><i class="fa fa-bar-chart"></i> รายงาน สรุปยอดอถินันท์แยกตามผู้รับ แสดงเลขที่เอกสาร</a></li>
          <li><a href="index.php?content=support_by_customer_items"><i class="fa fa-bar-chart"></i> รายงาน สรุปยอดอภินันท์แยกตามผู้รับ แสดงรายการสินค้า</a></li>
          <li class="divider"></li>
          <li><a href="index.php?content=permission_by_employee"><i class="fa fa-bar-chart"></i> รายงาน ตรวจสอบสิทธิ์ แยกตามพนักงาน</a></li>
        </ul>
      </li>

      <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart"></i> รายงาน วิเคราะห์</a>
      	<ul class="dropdown-menu">

        </ul>
      </li>
    </ul>
  </li><!-- รายงาน -->

  <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cogs"></i> การตั้งค่า</a>
    <ul class="dropdown-menu">
    	<li><a href="index.php?content=config"><i class="fa fa-cogs"></i> การตั้งค่า</a></li>
      <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog"></i> นโยบายส่วนลด</a>
        <ul class="dropdown-menu">
          <li><a href="index.php?content=discount_policy"><i class="fa fa-cog"></i> เพิ่ม/แก้ไข นโยบายส่วนลด</a></li>
          <li><a href="index.php?content=discount_rule"><i class="fa fa-cog"></i> เพิ่ม/แก้ไข เงื่อนไขส่วนลด</a></li>
        </ul>
      </li>

      <li class="divider"></li>
      <li><a href="index.php?content=popup"><i class="fa fa-bullhorn"></i> การแจ้งข่าว</a></li>
      <li class="divider"></li>
      <li><a href="index.php?content=Profile"><i class="fa fa-folder"></i> เพิ่ม/แก้ไข โปรไฟล์</a></li>
      <li><a href="index.php?content=securable"><i class="fa fa-unlock-alt"></i> กำหนดสิทธิ์</a></li>
    </ul>
  </li>

  <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-database"></i> ฐานข้อมูล</a>
  	<ul class="dropdown-menu">
  		<li class="dropdown-submenu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-tags"></i> สินค้า</a>
        <ul class="dropdown-menu">
          <li><a href="index.php?content=product"><i class="fa fa-tags"></i> เพิ่ม/แก้ไข สินค้า</a></li>
          <li><a href="index.php?content=style"><i class="fa fa-tags"></i> เพิ่ม/แก้ไข รุ่นสินค้า</a></li>
          <li><a href="index.php?content=category"><i class="fa fa-tags"></i> เพิ่ม/แก้ไข หมวดหมู่สินค้า</a></li>
          <li><a href="index.php?content=product_group"><i class="fa fa-tags"></i> เพิ่ม/แก้ไข กลุ่มสินค้า</a></li>
          <li><a href="index.php?content=product_sub_group"><i class="fa fa-tags"></i> เพิ่ม/แก้ไข กลุ่มย่อยสินค้า</a></li>
          <li><a href="index.php?content=kind"><i class="fa fa-tags"></i> เพิ่ม/แก้ไข ประเภทสินค้า</a></li>
          <li><a href="index.php?content=type"><i class="fa fa-tags"></i> เพิ่ม/แก้ไข ชนิดสินค้า</a></li>
          <li><a href="index.php?content=brand"><i class="fa fa-tags"></i> เพิ่ม/แก้ไข ยี่ห้อสินค้า</a></li>
          <li><a href="index.php?content=product_tab"><i class="fa fa-tags"></i> เพิ่ม/แก้ไข แถบเสดงสินค้า</a></li>
          <li><a href="index.php?content=unit"><i class="fa fa-tags"></i> เพิ่ม/แก้ไข หน่วยนับ</a></li>
          <li><a href="index.php?content=color"><i class="fa fa-tags"></i> เพิ่ม/แก้ไข สี</a></li>
          <li><a href="index.php?content=size"><i class="fa fa-tags"></i> เพิ่ม/แก้ไข ไซด์</a></li>
          <li><a href="index.php?content=barcode"><i class="fa fa-tags"></i> เพิ่ม/แก้ไข บาร์โค้ด</a></li>
        </ul>
      </li>
      <li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-home"></i> คลังสินค้า</a>
        <ul class="dropdown-menu">
        	<li><a href="index.php?content=zone"><i class="fa fa-map-marker"></i> เพิ่ม/แก้ไข โซนสินค้า</a></li>
      		<li><a href="index.php?content=warehouse"><i class="fa fa-home"></i> เพิ่ม/แก้ไข คลังสินค้า</a></li>
        </ul>
      </li>
      <li class="dropdown-submenu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-users"></i> ลูกค้า</a>
        <ul class="dropdown-menu">
          <li><a href="index.php?content=customer"><i class="fa fa-user"></i> เพิ่ม/แก้ไข ลูกค้า</a></li>
          <li><a href="index.php?content=customer_address"><i class="fa fa-user"></i> เพิ่ม/แก้ไข ที่อยู่ลูกค้า</a></li>
          <li><a href="index.php?content=customer_kind"><i class="fa fa-user"></i> เพิ่ม/แก้ไข ประเภทลูกค้า</a></li>
          <li><a href="index.php?content=customer_type"><i class="fa fa-user"></i> เพิ่ม/แก้ไข ชนิดลูกค้า</a></li>
          <li><a href="index.php?content=customer_group"><i class="fa fa-user"></i> เพิ่ม/แก้ไข กลุ่มลูกค้า</a></li>
          <li><a href="index.php?content=customer_class"><i class="fa fa-user"></i> เพิ่ม/แก้ไข เกรดลูกค้า</a></li>
          <li><a href="index.php?content=customer_area"><i class="fa fa-user"></i> เพิ่ม/แก้ไข เขตลูกค้า</a></li>
          <li><a href="index.php?content=customer_transfer"><i class="fa fa-user"></i> โอน/ย้าย ลูกค้า</a></li>
          <li><a href="index.php?content=customer_credit"><i class="fa fa-user"></i>  วงเงินเครดิต</a></li>
        </ul>
      </li>
      <li class="dropdown-submenu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-shield"></i>  สปอนเซอร์</a>
        <ul class="dropdown-menu">
          <li><a href="index.php?content=sponsor"><i class="fa fa-user"></i> เพิ่ม/แก้ไข รายชื่อสปอนเซอร์</a></li>
          <li><a href="index.php?content=sponsor_approver"><i class="fa fa-user"></i> เพิ่ม/แก้ไข ผู้อนุมัติงบสปอนเซอร์</a></li>
        </ul>
      </li>
      <li class="dropdown-submenu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> พนักงาน</a>
        <ul class="dropdown-menu">
          <li><a href="index.php?content=Employee"><i class="fa fa-user"></i> เพิ่ม/แก้ไข พนักงาน</a></li>
          <li><a href="index.php?content=sale_group"><i class="fa fa-user"></i> เพิ่ม/แก้ไข ทีมขาย</a></li>
          <li><a href="index.php?content=sale"><i class="fa fa-user"></i> เพิ่ม/แก้ไข พนักงานขาย</a></li>
          <li><a href="index.php?content=support"><i class="fa fa-user"></i> เพิ่ม/แก้ไข  รายชื่ออภินันทนาการ</a></li>
          <li><a href="index.php?content=support_approver"><i class="fa fa-user"></i> เพิ่ม/แก้ไข ผู้อนุมัติงบอภินันท์</a></li>
        </ul>
      </li>
      <li class="dropdown-submenu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-users"></i> ผู้จำหน่าย</a>
      	<ul class="dropdown-menu">
        	<li><a href="index.php?content=supplier"><i class='fa fa-user'></i> เพิ่ม/แก้ไข ผู้จำหน่าย</a></li>
          <li><a href="index.php?content=supplier_group"><i class='fa fa-users'></i> เพิ่ม/แก้ไข กลุ่มผู้จำหน่าย</a></li>
        </ul>
      </li>
      <li class="dropdown-submenu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-star"></i> การตลาด</a>
        <ul class="dropdown-menu">
          <li><a href="index.php?content=channels"><i class='fa fa-star'></i> เพิ่ม/แก้ไข ช่องทางการขาย</a></li>
          <li><a href="index.php?content=payment_method"><i class='fa fa-star'></i> เพิ่ม/แก้ไข ช่องทางการชำระเงิน</a></li>
        </ul>
      </li>
      <li class="dropdown-submenu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-truck"></i> จัดส่ง</a>
      	<ul class="dropdown-menu">
        	<li><a href="index.php?content=sender"><i class="fa fa-truck"></i> เพิ่ม/แก้ไข รายชื่อผู้จัดส่ง</a></li>
          <li><a href="index.php?content=transport"><i class="fa fa-truck"></i> เชื่อมโยงการจัดส่งของลูกค้า</a></li>
        </ul>
      </li>
      <li class="dropdown-submenu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bank"></i> บริษัท</a>
      	<ul class="dropdown-menu">
        	<li><a href="index.php?content=branch"><i class="fa fa-bank"></i> เพิ่ม/แก้ไข สาขา</a></li>
          <li><a href="index.php?content=bank_account"><i class="fa fa-bank"></i> เพิ่ม/แก้ไข บัญชีธนาคาร</a></li>
        </ul>
      </li>
      <li><a href="index.php?content=syncData"><i class="fa fa-exchange"></i> นำเข้าข้อมูลจาก formula</a></li>
      <li class="divider"></li>
      <li><a href="index.php?content=export_product_db"><i class="fa fa-database"></i> ส่งออกรายการสินค้า นำเข้า POS</a></li>
      <li><a href="index.php?content=import_stock_zone"><i class='fa fa-database'></i> บันทึกยอดสินค้าคงเหลือยกมาต้นงวด</a></li>
      <li><a href="index.php?content=export_order_dhl"><i class="fa fa-database"></i> ส่งออกออเดอร์ นำเข้า DHL</a></li>
      <!--<li><a href="index.php?content=test_run"><i class='fa fa-database'></i> ทดสอบระบบ</a></li> -->

    </ul>
  </li>

<!--            <li><a href="../doc/index.php" target="_blank"><i class="fa fa-book"></i> คู่มือการใช้งาน</a></li> -->
</ul>
<?php endif; ?>
