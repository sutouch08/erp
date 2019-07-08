<h1>การเตรียมข้อมูล สำหรับเริ่มต้นระบบ</h1>
<p>
ก่อนที่จะเริ่มใช้ระบบ จำเป็นต้องเพิ่มข้อมูลเริ่มต้นเพื่อใช้เป็น Master ในระบบก่อนซึ่งการเพิ่มข้อมูล Master จะอยู่ในหัวข้อถัดๆไป แต่ในหัวข้อนี้ กล่าวถึงการจัดเตรียมข้อมูลสำหรับใช้เป็น Master เพื่อให้การทำงานเป็นไปได้อย่างรวดเร็วซึ่งข้อมูลที่เราจะต้องจัดเตรียมไว้ก่อนมีดังนี้
</p>
<p>&nbsp;</p>
<p>
	<h2>สาขา</h2>
	สาขาใช้ในการระบุคลังสินค้า กรณีที่มีคลังสินค้ามากกว่า 1 ที่และคลังสินค้าอยู่พื้นที่ห่างกัน สาขาจะเข้ามามีหน้าที่ในการแบ่งการสั่งสินค้าให้สั่งได้เฉพาะสินค้าที่มีอยู่ในสาขาที่กำหนดเท่านั้น เพื่อลดปัญหาในการจัดการสต็อกระหว่างคลัง ข้อมูลที่ต้องเตรียมคือ
	<ul>
		<li>ชื่อสาขา</li>
	</ul>
	<br>
	<h2>คลังสินค้า</h2>
		ตัวระบบจะมีคลังสินค้าเริ่มต้นและเป็นคลังสินค้าหลักให้ 1 คลัง คือ คลังหลัก หากต้องการใช้งานคลังมากกว่า 1 คลัง สามารถเพิ่มได้ <a href="index.php?content=warehouse">(เพิ่ม/แก้ไข คลังสินค้า)</a>
	<ul></ul>
	<br>
	<h2>โซนเก็บสินค้า</h2>
	คลังสินค้าแต่ละคลังต้องมีโซนสำหรับเก็บสินค้าอย่างน้อย 1 โซน การกำหนดโซน ควรมีกำหนดให้สามารถบงบอกได้ว่าอยู่ตำแหน่งใดของคลังเพื่อให้สามารถใช้งานได้เต็มประสิทธิภาพ ซึ่งการเพิ่มโซนต้องการข้อมูลดังนี้
	<ul>
		<li>ชื่อโซน</li>
	    <li>บาร์โค้ด (เพื่อให้ระบบใช้งานได้เต็มประสิทธิภาพ ควรทำบาร์โค้ดแล้วติดไว้ตามโซนที่กำหนด)</li>
	</ul>
	<br>

	<h2>โปรไฟล์</h2>
		ระบบใช้โปรไฟล์ในการกำหนดสิทธิ์ พนักงานที่มีโปรไฟล์เดียวกัน จะมีสิทธิ์การใช้งานระบบเท่ากัน ข้อมูลที่ต้องเตรียมคือ
	<ul>
		<li>ชื่อโปรไฟล์</li>
	</ul>
	<br>
<h2>ข้อมูลพนักงาน</h2>
ระบบจะถือว่าพนักงานที่เพิ่มเข้าระบบเป็นหนึ่งในผู้ใช้งาน ซึ่งการจะเข้าสู่ระบบได้จำเป็นต้องเพิ่มพนักงานและระบุ Email หรือ User name ที่จะใช้ลงชื่อเข้าใช้ หากไม่กำหนดพนักงานคนนั้นจะไม่สามารถเข้าใช้ระบบได้ ข้อมูลที่ต้องเตรียมมีดังนี้
<ul>
	<li>ชื่อ-สกุล</li>
    <li>Email หรือ User name ที่จะใช้ในการเข้าระบบ</li>
    <li>รหัสผ่าน</li>
    <li>รหัสลับ(เฉพาะผู้ที่มีอำนาจในการอนุมัติต่างๆ)</li>
		<li>โปรไฟล์</li>
</ul>
<br>


<h2>คุณลักษณะของสินค้า</h2>
<ul>
	<li>สี
    	<ul>
        	<li>กำหนด กลุ่มสี</li>
            <li>กำหนด รหัสสี</li>
            <li>กำหนด ชื่อสี</li>
        </ul>
    </li>
    <li>ไซด์
    	<ul>
        	<li>ชื่อไซด์</li>
        </ul>
    </li>
    <li>คุณลัษณะอื่น (เช่น ผู้หญิง, ผู้ชาย, สั้น, ยาว เป็นต้น)
    	<ul>
        	<li>ชื่อคุณลักษณะ</li>
        </ul>
    </li>
</ul>
<br>

<h2>หมวดหมู่สินค้า</h2>
<ul>
	<li>กำหนดหมวดหมู่หลัก</li>
    <li>กำหนดหมวดหมู่ย่อย</li>
</ul>
<br>

<h2>ข้อมูลสินค้า</h2>
<ul>
	<li>ข้อมูลของรุ่นสินค้า
    	<ul>
        	<li>ชื่อรุ่นสินค้า</li>
            <li>รหัสรุ่นสินค้า</li>
            <li>หมวดหมู่หลัก</li>
            <li>หมวดหมู่ย่อย</li>
            <li>ราคาทุน</li>
            <li>ราคาขาย</li>
            <li>ส่วนลด</li>
            <li>น้ำหนัก</li>
            <li>ความกว้าง</li>
            <li>ความยาว</li>
            <li>ความสูง</li>
            <li>คำอธิบายสินค้า</li>
        </ul>
    </li>
    <li>ข้อมูลรายละเอียดตัวสินค้า
    	<ul>
        	<li>รูปภาพ</li>
            <li>รหัสสินค้า หรือ รหัสอ้างอิง</li>
            <li>สี</li>
            <li>ไซด์</li>
            <li>คุณลักษณะอื่น</li>
            <li>ราคาทุน</li>
            <li>ราคาขาย</li>
            <li>น้ำหนัก</li>
            <li>ความกว้าง</li>
            <li>ความสูง</li>
            <li>ความยาว</li>
            <li>บาร์โค้ด สินค้า</li>
            <li>บาร์โค้ด แพ็ค (ถ้ามี)</li>
            <li>กำหนด จำนวนในแพ็ค (ถ้ามี)</li>
        </ul>
    </li>
</ul>
<br>

<h2>ข้อมูลลูกค้า</h2>
<ul>
	<li>กำหนดกลุ่มลูกค้า (อาจแบ่งลูกค้าตามเขตการขาย)</li>
    <li>ลูกค้า
    	<ul>
          <li>รหัสลูกค้า</li>
          <li>ชื่อ - สกุล ผู้ติดต่อ</li>
          <li>ชื่อบริษัท หรือ ชื่อร้าน (ถ้ามี)</li>
          <li>อีเมล์ และรหัสผ่าน (สำหรับเข้าระบบ กรณีลูกค้าสั่งสินค้าผ่านระบบเอง)</li>
          <li>วัน/เดือน/ปี เกิด (ถ้ามี)</li>
          <li>วงเงินเครดิต (สำหรับจำกัดการสั่งซื้อ ถ้าไม่จำกัดให้เซตเป็น 0.00)</li>
          <li>เครดิตเทอม (ถ้ามี)</li>
          <li>กลุ่มลูกค้า</li>
          <li>พนักงานขาย (พนักงานขายที่รับผิดชอบลูกค้ารายนี้)</li>
          <li>ส่วนลด (ตามหมวดหมู่สินค้า)</li>
        </ul>
    </li>
</ul>
<br>
</p>
<p>
ควรเตรียมข้อมูลตามหัวข้อด้านบนไว้ในรูปแบบไฟล์ หรือ พิมพ์เป็นเอกสารเพื่อใช้ในการเพิ่มข้อมูล
</p>


<h2></h2>
<p style="width:100%; text-align:center">
หัวข้อก่อนหน้า : <a href="index.php?content=login">เข้าระบบ</a>&nbsp;&nbsp; | &nbsp;&nbsp;  <a href="#top">ขึ้นบน</a>&nbsp;&nbsp; | &nbsp;&nbsp; หัวข้อถัดไป : <a href="index.php?content=profile">สร้างโปรไฟล์</a>
</p>
