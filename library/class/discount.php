<?php
class discount 
{
	public function __construct()
	{
		
	}
	
	
	public function getItemDiscount($id_pd, $id_cus, $qty, $id_payment, $id_channels, $date = "")
	{
		$date = $date == "" ? date('Y-m-d') : $date;
		$pd = new product($id_pd);
		$cs = new customer($id_cus);
		
		//--- default value if dont have any discount
		$sc = array("discount" => 0, "unit" => "percent", "amount" => 0, "id_rule" => NULL);
		
		if( $pd->id != "" && $cs->id != "" )
		{
			$qr = "SELECT r.id, r.item_price, r.item_disc, r.item_disc_unit, r.qty, r.amount, r.canGroup FROM tbl_discount_policy AS p ";
			
			//----- get list of rule in policy
			$qr .= "LEFT JOIN tbl_discount_rule AS r ON p.id = r.id_discount_policy ";
			
			//---- get list of product if specific SKU
			$qr .= "LEFT JOIN tbl_discount_rule_items AS i ON r.id = i.id_rule ";
			
			//---- get list of customer if specific personaly customer
			$qr .= "LEFT JOIN tbl_discount_rule_customers AS c ON r.id = c.id_rule ";
			
			//--- get list of sales channels if specific channels
			$qr .= "LEFT JOIN tbl_discount_rule_channels AS n ON r.id = n.id_rule ";
			
			//---- get list of payment thod if specific payment method
			$qr .= "LEFT JOIN tbl_discount_rule_payment AS m ON r.id = m.id_rule ";
			
			//----- Check preriod and approval first
			$qr .= "WHERE p.date_start <= '".$date."' AND p.date_end >= '".$date."' AND p.isApproved = 1 AND p.active = 1 AND p.isDeleted = 0 ";
			
			//----- now check product
			$qr .= "AND (all_product = 1 OR id_product = '".$pd->id."' OR id_product_style = '".$pd->id_style."' OR id_product_group = '".$pd->id_group."' ";
			$qr .= "OR id_product_type = '".$pd->id_type."' OR id_product_kind = '".$pd->id_kind."' OR id_product_category = '".$pd->id_category."' ";
			$qr .= "OR id_product_brand = '".$pd->id_brand."' OR product_year = '".$pd->year."' ) ";
			
			//---- now check customer
			$qr .= "AND (all_customer = 1 OR id_customer_group = '".$cs->id."' OR id_customer_group = '".$cs->id_group."' ";
			$qr .= "OR id_customer_area = '".$cs->id_area."' OR id_customer_kind = '".$cs->id_kind."' OR id_customer_type = '".$cs->id_type."' ";
			$qr .= "OR id_customer_class = '".$cs->id_class."' ) ";
			
			//---- now check  payment method
			$qr .= "AND ( all_payment = 1 OR id_payment = '".$id_payment."') ";
			
			//---- now check sales channels
			$qr .= "AND ( all_channels = 1 OR id_channels = '".$id_channels."') ";
			
			//--- now check qty options
			$qr .= "AND (qty = 0 OR  qty <= ".$qty.") ";
			
			//--- now check amount options
			$qr .= "AND ( amount = 0 OR amount <= ".( $qty * $pd->price ).") ";
			
			$qr .= "AND r.active = 1 AND r.isDeleted = 0";
			
			$qs = dbQuery($qr);
			
			if( dbNumRows($qs) > 0 )
			{
				$setPrice = 0; //--- ถ้ามีการกำหนดราคาขาย จะไม่สนใจส่วนลด ส่วนต่างราคาขาย จะถูกแปลงเป็นส่วนลดแทน
				$discAmount = 0;
				$discLabel = 0;
				$price_rule = 0;
				$dis_rule = 0;
				$disc_unit = "percent";
				while( $rs = dbFetchObject($qs) )
				{
					//---- ถ้ามีการกำหนดราคาขาย
					if( $rs->item_price > 0 ){
						$discount 	=	$pd->price - $rs->item_price;
						$price_rule 	= ( $discount > $setPrice ) ? $rs->id : $price_rule;
						$setPrice 	= $discount > $setPrice ? $discount : $setPrice;
					}
					else
					{
						$unit = $rs->item_disc_unit;
						if( $rs->item_disc > 0 )
						{
							$discount 		= $unit == "percent" ? $pd->price * ( $rs->item_disc * 0.01 ) :  $rs->item_disc;
							$dis_rule 		= ( $discount > $discAmount ) ? $rs->id : $dis_rule;
							$disc_unit 		= ( $discount > $discAmount ) ? $unit : $disc_unit;
							$discLabel 		= ( $discount > $discAmount ) ? $rs->item_disc : $discLabel;
							$discAmount 	= ( $discount > $discAmount ) ? $discount : $discAmount;
							
						}	//-- end if
						
					}	//-- end if
					
				}//--- end while
				
				//---- เลือกรูปแบบส่วนลดที่ดีที่สุด
				$disType = ( $setPrice > $discAmount ) ? "price" : "disc";
				$sc = array(
					//--- ถ้าส่วนลดเป็นแบบกำหนดราคา ให้ใช้ส่วนต่างราคาเป็นส่วนลดเลย หากไม่ใช้ ดูว่าส่วนลดเป้น % ให้เติม % เข้าไปด้วย
					"discount" => $disType == "price" ? $setPrice : ( $disc_unit == "percent" ? $discLabel.' %' : $discLabel ),
					"unit"	=> $disType == "price" ? "amount" : $disc_unit,
								
					//--- เอายอดส่วนลดที่ได้ มา คูณ ด้วย จำนวนสั่ง เป้นส่วนลดทั้งหมด
					"amount"	=> $disType == "price" ? ( $qty * $setPrice ) : ( $qty * $discAmount ),
							
					//--- เลือก id_rule จากส่วนลดที่ดีที่สุด
					"id_rule"	=> $disType == "price" ? $price_rule : $dis_rule							
				); //-- end array
			
			} //--- end if dbNumRows
		
		}
		return $sc;						
	}
	
	
	
	//----- คำนวณส่วนลดใหม่ โดยจำนวนซื้อกับมูลค่าซื้อจะมีผลกับส่วนลด
	public function getItemRecalDiscount($id_order, $id_pd, $price, $id_cus, $qty, $id_payment, $id_channels, $date = "")
	{
		$date = $date == "" ? date('Y-m-d') : $date;
		$pd = new product($id_pd);
		$cs = new customer($id_cus);
		$order = new order();
		//--- default value if dont have any discount
		$sc = array("discount" => 0, "amount" => 0, "id_rule" => NULL);
		
		if( $pd->id != "" && $cs->id != "" )
		{
			$qr = "SELECT r.id, r.item_price, r.item_disc, r.item_disc_unit, r.qty, r.amount, r.canGroup FROM tbl_discount_policy AS p ";
			
			//----- get list of rule in policy
			$qr .= "LEFT JOIN tbl_discount_rule AS r ON p.id = r.id_discount_policy ";
			
			//---- get list of product if specific SKU
			$qr .= "LEFT JOIN tbl_discount_rule_items AS i ON r.id = i.id_rule ";
			
			//---- get list of customer if specific personaly customer
			$qr .= "LEFT JOIN tbl_discount_rule_customers AS c ON r.id = c.id_rule ";
			
			//--- get list of sales channels if specific channels
			$qr .= "LEFT JOIN tbl_discount_rule_channels AS n ON r.id = n.id_rule ";
			
			//---- get list of payment thod if specific payment method
			$qr .= "LEFT JOIN tbl_discount_rule_payment AS m ON r.id = m.id_rule ";
			
			//----- Check preriod and approval first
			$qr .= "WHERE p.date_start <= '".$date."' AND p.date_end >= '".$date."' AND p.isApproved = 1 AND p.active = 1 AND p.isDeleted = 0 ";
			
			//----- now check product
			$qr .= "AND (all_product = 1 OR id_product = '".$pd->id."' OR id_product_style = '".$pd->id_style."' OR id_product_group = '".$pd->id_group."' ";
			$qr .= "OR id_product_type = '".$pd->id_type."' OR id_product_kind = '".$pd->id_kind."' OR id_product_category = '".$pd->id_category."' ";
			$qr .= "OR id_product_brand = '".$pd->id_brand."' OR product_year = '".$pd->year."' ) ";
			
			//---- now check customer
			$qr .= "AND (all_customer = 1 OR id_customer_group = '".$cs->id."' OR id_customer_group = '".$cs->id_group."' ";
			$qr .= "OR id_customer_area = '".$cs->id_area."' OR id_customer_kind = '".$cs->id_kind."' OR id_customer_type = '".$cs->id_type."' ";
			$qr .= "OR id_customer_class = '".$cs->id_class."' ) ";
			
			//---- now check  payment method
			$qr .= "AND ( all_payment = 1 OR id_payment = '".$id_payment."') ";
			
			//---- now check sales channels
			$qr .= "AND ( all_channels = 1 OR id_channels = '".$id_channels."') ";
			
			$qr .= "AND r.active = 1 AND r.isDeleted = 0";
			
			//echo $qr;
			$qs = dbQuery($qr);
			
			if( dbNumRows($qs) > 0 )
			{
				$setPrice = 0; //--- ถ้ามีการกำหนดราคาขาย จะไม่สนใจส่วนลด ส่วนต่างราคาขาย จะถูกแปลงเป็นส่วนลดแทน
				$discAmount = 0;
				$discLabel = 0;
				$price_rule = 0;
				$dis_rule = 0;
				$disc_unit = "percent";
				
				while( $rs = dbFetchObject($qs) )
				{
					//---- ถ้ามีการกำหนดราคาขาย
					if( $rs->item_price > 0 )
					{
						
						//----- หากมีการกำหนดยอดขั้นต่ำ และ สามารถรวมยอดได้
						if( ( $rs->qty > 0 OR $rs->amount > 0 ) && $rs->canGroup == 1 )
						{
							
								//---- คำนวณยอดสั่งใหม่
								$order_qty = $order->getSumOrderStyleQty($id_order, $pd->id_style);
								
								if( ( $order_qty > 0 && $rs->qty <= $order_qty ) OR ( $order_amount > 0 && $rs->amount <= ( $order_qty * $price) ) )
								{
									$data = $this->get_item_price_disc($pd->id, $price, $cs->id, $order_qty, $order_amount, $id_payment, $id_channels, $date);
									$discount 	=	$data['amount'] / $order_qty; 	//--- ทำย้อนกลับ เพื่อหาส่วนลดที่ดีที่สุด
									$price_rule 	= ( $discount > $setPrice ) ? $data['id_rule'] : $price_rule;
									$setPrice 	= $discount > $setPrice ? $discount : $setPrice;
								}
						}
						else if( ( ( $rs->qty > 0 && $rs->qty <= $qty) OR ( $rs->amount > 0 && $rs->amount <= ($qty * $price) ) ) && $rs->canGroup == 0 )
						{				
							$discount 	=	$price - $rs->item_price;
							$price_rule 	= ( $discount > $setPrice ) ? $rs->id : $price_rule;
							$setPrice 	= $discount > $setPrice ? $discount : $setPrice;
						}
						else if( $rs->qty == 0 && $rs->amount == 0 )
						{
							
							$discount 	=	$price - $rs->item_price;
							$price_rule 	= ( $discount > $setPrice ) ? $rs->id : $price_rule;
							$setPrice 	= $discount > $setPrice ? $discount : $setPrice;
						}
						
					}
					else
					{
						
						$unit = $rs->item_disc_unit;
						
						if( $rs->item_disc > 0 )
						{
							//---- มีการกำหนดจำนวนขั้นตำหรือยอดขั้นต่ำ และสามารถรวมยอดเป็นรุ่นได้
							if( ( $rs->qty > 0 OR $rs->amount > 0 )&& $rs->canGroup == 1 )
							{
								
								$order_qty = $order->getSumOrderStyleQty($id_order, $pd->id_style);
								
								if( ( $order_qty > 0 && $rs->qty <= $order_qty ) OR ( $order_amount > 0 && $rs->amount <= ($order_qty * $price)) )
								{
									
									$data = $this->get_item_disc($pd->id, $price, $cs->id, $order_qty, $id_payment, $id_channels, $date); 
									$discount 		= $data['amount'] / $order_qty;
									$dis_rule 		= ( $discount > $discAmount ) ? $data['id_rule'] : $dis_rule;
									$disc_unit 		= ( $discount > $discAmount ) ? $data['unit'] : $disc_unit;
									$discLabel 		= ( $discount > $discAmount ) ? $data['discount'] : $discLabel;
									$discAmount 	= ( $discount > $discAmount ) ? $discount : $discAmount;
								}
							}
							else if( ( ($rs->qty > 0 && $rs->qty <= $qty ) OR ( $rs->amount > 0 && $rs->amount <= ($qty * $price) ) ) && $rs->canGroup == 0 )
							{
								
								$discount 		= $unit == "percent" ? $price * ( $rs->item_disc * 0.01 ) :  $rs->item_disc;
								$dis_rule 		= ( $discount > $discAmount ) ? $rs->id : $dis_rule;
								$disc_unit 		= ( $discount > $discAmount ) ? $unit : $disc_unit;
								$discLabel 		= ( $discount > $discAmount ) ? $rs->item_disc : $discLabel;
								$discAmount 	= ( $discount > $discAmount ) ? $discount : $discAmount;
							}
							else if( $rs->qty == 0 && $rs->amount == 0 )
							{
								
								$discount 		= $unit == "percent" ? $price * ( $rs->item_disc * 0.01 ) :  $rs->item_disc;
								$dis_rule 		= ( $discount > $discAmount ) ? $rs->id : $dis_rule;
								$disc_unit 		= ( $discount > $discAmount ) ? $unit : $disc_unit;
								$discLabel 		= ( $discount > $discAmount ) ? $rs->item_disc : $discLabel;
								$discAmount 	= ( $discount > $discAmount ) ? $discount : $discAmount;
							}
							
						}	//-- end if
						
					}	//-- end if
					
				}//--- end while
				
				//---- เลือกรูปแบบส่วนลดที่ดีที่สุด
				$disType = ( $setPrice > $discAmount ) ? "price" : "disc";
				$sc = array(
					//--- ถ้าส่วนลดเป็นแบบกำหนดราคา ให้ใช้ส่วนต่างราคาเป็นส่วนลดเลย หากไม่ใช้ ดูว่าส่วนลดเป้น % ให้เติม % เข้าไปด้วย
					"discount" => $disType == "price" ? $setPrice : ( $disc_unit == "percent" ? $discLabel.' %' : $discLabel ),
					
					//---- หน่วยของส่วนลด
					"unit"	=> $disType == "price" ? "amount" : $disc_unit,
								
					//--- เอายอดส่วนลดที่ได้ มา คูณ ด้วย จำนวนสั่ง เป้นส่วนลดทั้งหมด
					"amount"	=> $disType == "price" ? ( $qty * $setPrice ) : ( $qty * $discAmount ),
							
					//--- เลือก id_rule จากส่วนลดที่ดีที่สุด
					"id_rule"	=> $disType == "price" ? $price_rule : $dis_rule							
				); //-- end array
			
			} //--- end if dbNumRows
		
		}
		return $sc;						
	}
		
	
	public function get_item_disc($id_pd, $price, $id_cus, $qty, $id_payment, $id_channels, $date = "")
	{
		$date = $date == "" ? date('Y-m-d') : $date;
		$pd = new product($id_pd);
		$cs = new customer($id_cus);
		
		//--- default value if dont have any discount
		$sc = array("discount" => 0, "unit" => "percent", "amount" => 0, "id_rule" => NULL);
		
		if( $pd->id != "" && $cs->id != "" )
		{
			$qr = "SELECT r.id, r.item_price, r.item_disc, r.item_disc_unit, r.qty, r.amount, r.canGroup FROM tbl_discount_policy AS p ";
			
			//----- get list of rule in policy
			$qr .= "LEFT JOIN tbl_discount_rule AS r ON p.id = r.id_discount_policy ";
			
			//---- get list of product if specific SKU
			$qr .= "LEFT JOIN tbl_discount_rule_items AS i ON r.id = i.id_rule ";
			
			//---- get list of customer if specific personaly customer
			$qr .= "LEFT JOIN tbl_discount_rule_customers AS c ON r.id = c.id_rule ";
			
			//--- get list of sales channels if specific channels
			$qr .= "LEFT JOIN tbl_discount_rule_channels AS n ON r.id = n.id_rule ";
			
			//---- get list of payment thod if specific payment method
			$qr .= "LEFT JOIN tbl_discount_rule_payment AS m ON r.id = m.id_rule ";
			
			//----- Check preriod and approval first
			$qr .= "WHERE p.date_start <= '".$date."' AND p.date_end >= '".$date."' AND p.isApproved = 1 AND p.active = 1 AND p.isDeleted = 0 ";
			
			//----- now check product
			$qr .= "AND (all_product = 1 OR id_product = '".$pd->id."' OR id_product_style = '".$pd->id_style."' OR id_product_group = '".$pd->id_group."' ";
			$qr .= "OR id_product_type = '".$pd->id_type."' OR id_product_kind = '".$pd->id_kind."' OR id_product_category = '".$pd->id_category."' ";
			$qr .= "OR id_product_brand = '".$pd->id_brand."' OR product_year = '".$pd->year."' ) ";
			
			//---- now check customer
			$qr .= "AND (all_customer = 1 OR id_customer_group = '".$cs->id."' OR id_customer_group = '".$cs->id_group."' ";
			$qr .= "OR id_customer_area = '".$cs->id_area."' OR id_customer_kind = '".$cs->id_kind."' OR id_customer_type = '".$cs->id_type."' ";
			$qr .= "OR id_customer_class = '".$cs->id_class."' ) ";
			
			//---- now check  payment method
			$qr .= "AND ( all_payment = 1 OR id_payment = '".$id_payment."') ";
			
			//---- now check sales channels
			$qr .= "AND ( all_channels = 1 OR id_channels = '".$id_channels."') ";
			
			//--- now check qty options
			$qr .= "AND (qty = 0 OR  qty <= ".$qty.") ";
			
			//--- now check amount options
			$qr .= "AND ( amount = 0 OR amount <= ".( $qty * $price ).") ";
			
			$qr .= "AND canGroup = 1 ";		
			
			$qr .= "AND r.active = 1 AND r.isDeleted = 0";
			
			$qs = dbQuery($qr);
			
			if( dbNumRows($qs) > 0 )
			{
				$discAmount = 0;
				$discLabel = 0;
				$dis_rule = 0;
				$disc_unit = "percent";
				while( $rs = dbFetchObject($qs) )
				{			
						$unit = $rs->item_disc_unit;
						if( $rs->item_disc > 0 )
						{
							$discount 		= $unit == "percent" ? $price * ( $rs->item_disc * 0.01 ) :  $rs->item_disc;
							$dis_rule 		= ( $discount > $discAmount ) ? $rs->id : $dis_rule;
							$disc_unit 		= ( $discount > $discAmount ) ? $unit : $disc_unit;
							$discLabel 		= ( $discount > $discAmount ) ? $rs->item_disc : $discLabel;
							$discAmount 	= ( $discount > $discAmount ) ? $discount : $discAmount;
							
						}	//-- end if
					
				}//--- end while
				
				//---- เลือกรูปแบบส่วนลดที่ดีที่สุด
				$sc = array(
					//--- ถ้าส่วนลดเป็นแบบกำหนดราคา ให้ใช้ส่วนต่างราคาเป็นส่วนลดเลย หากไม่ใช้ ดูว่าส่วนลดเป้น % ให้เติม % เข้าไปด้วย
					"discount" => $discLabel,
					
					//---- หน่วยของส่วนลด
					"unit"	=> $disc_unit,
								
					//--- เอายอดส่วนลดที่ได้ มา คูณ ด้วย จำนวนสั่ง เป้นส่วนลดทั้งหมด
					"amount"	=> $qty * $discAmount ,
							
					//--- เลือก id_rule จากส่วนลดที่ดีที่สุด
					"id_rule"	=> $dis_rule	
											
				); //-- end array
			
			} //--- end if dbNumRows
		
		}
		return $sc;						
	}
	
	
	public function get_item_price_disc($id_pd, $price, $id_cus, $qty, $id_payment, $id_channels, $date = "")
	{
		$date = $date == "" ? date('Y-m-d') : $date;
		$pd = new product($id_pd);
		$cs = new customer($id_cus);
		
		//--- default value if dont have any discount
		$sc = array("discount" => 0, "unit" => "percent", "amount" => 0, "id_rule" => NULL);
		
		if( $pd->id != "" && $cs->id != "" )
		{
			$qr = "SELECT r.id, r.item_price, r.item_disc, r.item_disc_unit, r.qty, r.amount, r.canGroup FROM tbl_discount_policy AS p ";
			
			//----- get list of rule in policy
			$qr .= "LEFT JOIN tbl_discount_rule AS r ON p.id = r.id_discount_policy ";
			
			//---- get list of product if specific SKU
			$qr .= "LEFT JOIN tbl_discount_rule_items AS i ON r.id = i.id_rule ";
			
			//---- get list of customer if specific personaly customer
			$qr .= "LEFT JOIN tbl_discount_rule_customers AS c ON r.id = c.id_rule ";
			
			//--- get list of sales channels if specific channels
			$qr .= "LEFT JOIN tbl_discount_rule_channels AS n ON r.id = n.id_rule ";
			
			//---- get list of payment thod if specific payment method
			$qr .= "LEFT JOIN tbl_discount_rule_payment AS m ON r.id = m.id_rule ";
			
			//----- Check preriod and approval first
			$qr .= "WHERE p.date_start <= '".$date."' AND p.date_end >= '".$date."' AND p.isApproved = 1 AND p.active = 1 AND p.isDeleted = 0 ";
			
			//----- now check product
			$qr .= "AND (all_product = 1 OR id_product = '".$pd->id."' OR id_product_style = '".$pd->id_style."' OR id_product_group = '".$pd->id_group."' ";
			$qr .= "OR id_product_type = '".$pd->id_type."' OR id_product_kind = '".$pd->id_kind."' OR id_product_category = '".$pd->id_category."' ";
			$qr .= "OR id_product_brand = '".$pd->id_brand."' OR product_year = '".$pd->year."' ) ";
			
			//---- now check customer
			$qr .= "AND (all_customer = 1 OR id_customer_group = '".$cs->id."' OR id_customer_group = '".$cs->id_group."' ";
			$qr .= "OR id_customer_area = '".$cs->id_area."' OR id_customer_kind = '".$cs->id_kind."' OR id_customer_type = '".$cs->id_type."' ";
			$qr .= "OR id_customer_class = '".$cs->id_class."' ) ";
			
			//---- now check  payment method
			$qr .= "AND ( all_payment = 1 OR id_payment = '".$id_payment."') ";
			
			//---- now check sales channels
			$qr .= "AND ( all_channels = 1 OR id_channels = '".$id_channels."') ";
			
			//--- now check qty options
			$qr .= "AND (qty = 0 OR  qty <= ".$qty.") ";
			
			//--- now check amount options
			$qr .= "AND ( amount = 0 OR amount <= ".( $qty * $price ).") ";
			
			$qr .= "AND canGroup = 1 ";
			
			$qr .= "AND r.active = 1 AND r.isDeleted = 0";
			
			$qs = dbQuery($qr);
			
			if( dbNumRows($qs) > 0 )
			{
				$setPrice = 0; 	//--- ถ้ามีการกำหนดราคาขาย จะไม่สนใจส่วนลด ส่วนต่างราคาขาย จะถูกแปลงเป็นส่วนลดแทน
				$discLabel = 0;
				$price_rule = 0;
				while( $rs = dbFetchObject($qs) )
				{
					//---- ถ้ามีการกำหนดราคาขาย
					if( $rs->item_price > 0 ){
						$discount 	=	$price - $rs->item_price;
						$price_rule 	= ( $discount > $setPrice ) ? $rs->id : $price_rule;
						$setPrice 	= $discount > $setPrice ? $discount : $setPrice;
					}
					
				}//--- end while
				
				//---- เลือกรูปแบบส่วนลดที่ดีที่สุด
				$sc = array(
					//--- ถ้าส่วนลดเป็นแบบกำหนดราคา ให้ใช้ส่วนต่างราคาเป็นส่วนลดเลย หากไม่ใช้ ดูว่าส่วนลดเป้น % ให้เติม % เข้าไปด้วย
					"discount" => $setPrice,
					
					//--- หน่วยของส่วนลด
					"unit"	=> "amount",
								
					//--- เอายอดส่วนลดที่ได้ มา คูณ ด้วย จำนวนสั่ง เป้นส่วนลดทั้งหมด
					"amount"	=> $qty * $setPrice,
							
					//--- เลือก id_rule จากส่วนลดที่ดีที่สุด
					"id_rule"	=> $price_rule							
				); //-- end array
			
			} //--- end if dbNumRows
		
		}
		return $sc;						
	}
	
	
	
	public function getBillDiscount($id_order)
	{
		$sc = array(
							"discount_step_1" => 	10,
							"unit_step_1"	 => "percent",
							"discount_stem_2"	=> 5,
							"unit_step_2"	=> "percent",
							"id_rule"				=> "2"
							);
		return $sc;
	}
	
	/*
	public function add(array $ds)
	{
		$sc = FALSE;
		if( count( $ds ) > 0 )
		{
			$fields	= "";
			$values	= "";
			$i	= 1;
			foreach( $ds as $field => $value )
			{
				$fields	.= $i == 1 ? $field : ", ".$field;
				$values	.= $i == 1 ? "'".$value."'" : ", '".$value."'";
				$i++;	
			}
			$sc = dbQuery("INSERT INTO tbl_customer_discount (".$fields.") VALUES (".$values.")");
		}
		return $sc;
	}
	
	
	
	
	public function update($id, array $ds)
	{
		$sc = FALSE;
		if( count( $ds ) > 0 )
		{
			$set	= "";
			$i 		= 1;
			foreach( $ds as $field => $value )
			{
				$set .= $i == 1 ? $field." = '".$value."'" : ", ".$field." = '".$value."'";
				$i++;
			}
			$sc = dbQuery("UPDATE tbl_customer_discount SET ".$set." WHERE id = '".$id."'");
		}
		return $sc;
	}
	
	
	
	public function delete($id)
	{
		return dbQuery("DELETE FROM tbl_customer_discount WHERE id = ".$id);	
	}
	
	//--------------------- เฉพาะกรณีที่ใช้กลุ่มสินค้าในการอ้างอิงเท่านั้น ---------------------//
	public function getDiscountIdByGroup($id_customer, $id_group)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id FROM tbl_customer_discount WHERE id_customer = '".$id_customer."' AND id_product_group = '".$id_group."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}
	
	
	//----------------- ตรวจสอบว่า มีส่วนลดกับกลุ่มสินค้าสำหรับลูกค้าค้นนี้อยู่หรือไม่------------//
	public function isExistsGroupDiscount($id_customer, $id_group)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT discount FROM tbl_customer_discount WHERE id_customer = '".$id_customer."' AND id_product_group = '".$id_group."'");
		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;	
		}
		return $sc;
	}
	
	*/
	
	
}//-- end class

?>