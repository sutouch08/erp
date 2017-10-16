<?php

function getIdCart($id_cus)
{
	$id = 0;
	if($id_cus=='0'){
		return '0';
	}else{

		if(!get_instance()->session->userdata('id_customer')){
			$amI = "id_great";
			$id_c = '0';
			$id_g = $id_cus;
		}else{
			$amI = "id_customer";
			$id_c = $id_cus;
			$id_g = '0';
		}

		$rs = get_instance()->db->select('id_cart')->where($amI, $id_cus)->where('cart_status','0')->get('cart_online');
		
		if( $rs->num_rows() > 0 )
		{
			$id = $rs->row()->id_cart;
			return $id;	
		}else{
			
			$data = array(
			   'id_cart'  =>'',
			   'id_customer'=>$id_c,
			   'id_great' => $id_g,
			   'date_add' => date("Y-m-d H:i:s"),
			   'cart_status'=>'',
			);

				get_instance()->db->insert('cart_online', $data); 
				$id = get_instance()->db->insert_id();
				return $id;
		}

		
	}//else
	
    
}

function cartValue($id_cart = 0)
{
	$value = 0.00;
	if( $id_cart != 0 )
	{
		$rs = get_instance()->db->join('tbl_cart', 'tbl_cart.id_cart = tbl_cart_product.id_cart')->where('tbl_cart_product.id_cart', $id_cart)->get('tbl_cart_product');
		if( $rs->num_rows() > 0 )
		{
			foreach($rs->result() as $rd)
			{
				$id_pd 		= getIdProduct($rd->id_product_attribute);
				$qty 		= $rd->qty;
				$price 		= itemPrice($rd->id_product_attribute);
				$dis		= get_discount($rd->id_customer, $id_pd);
				$sell_price	= sell_price($price, $dis['discount'], $dis['type']);
				$value += $sell_price;
			}
		}
	}
	return $value;
}

function delivery_cost($qty = 0)
{
	$cost = 0;
	if( $qty > 0 )
	{
		$extra = $qty -1;
		$basic = 50;
		$cost = $basic + ($extra * 10 );
	}
	return $cost;	
}

function transpot_cost($id)
{
		$data['item_in_cart']  = $this->cart_model->getItemInCart($this->id_cart);
		$data['transport']	   = $this->cart_model->getTrans($data['item_in_cart'],$this->id_cart);
		$se = [];
		foreach ($data['transport'] as $key => $value) {
			if($value['id'] == $id){
				array_push($se,$value);
			}
		}
		print_r(json_encode($se));
}

function getDiscount($id_cart)
{	
	$total_discount = 0;
	$rs = get_instance()->db->select('cart_product_online.qty,tbl_product.id,tbl_product.price,promotion.discount_percent,
			promotion.discount_amount,')
	->join('tbl_product','tbl_product.id = cart_product_online.id_product')
	->join('promotion','promotion.id_product = tbl_product.id','left')
	->where('cart_product_online.id_cart_online',$id_cart)
	->get('cart_product_online');
	if( $rs->num_rows() > 0 )
	{
		foreach ($rs->result() as $item) {
			$total_discount += $item->discount_amount*$item->qty;
			$total_discount += (($item->discount_percent*$item->price)/100)*$item->qty;
		}
		
	}
	return $total_discount;
}


function getTotal($id_cart)
{
	$total_amount = 0;
	$rs = get_instance()->db->select('cart_product_online.qty,tbl_product.id,tbl_product.price')
	->join('tbl_product','tbl_product.id = cart_product_online.id_product')
	->where('cart_product_online.id_cart_online',$id_cart)
	->get('cart_product_online');
	if( $rs->num_rows() > 0 )
	{
		foreach ($rs->result() as $item) {
			$total_amount += $item->price*$item->qty;
		}
		
	}
	return $total_amount;
}

?>