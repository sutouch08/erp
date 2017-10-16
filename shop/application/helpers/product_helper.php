<?php 

function productName($id_pd)
{
	$name = '';
	$rs 	= get_instance()->db->select('product_name')->where('id_product', $id_pd)->get('tbl_product');
	if( $rs->num_rows() == 1 )
	{
		$name = $rs->row()->product_name;	
	}
	return $name;
}

function itemName($id_pa)
{
	$id_pd 	= getIdProduct($id_pa);	
	$name 	= productName($id_pd);
	return $name;
}

/// ส่งกลับรายชื่อสีต่างๆของสินค้าเป็น Object
function get_product_colors($id)
{
	$colors = FALSE ;
	$c 		=& get_instance();
	$rs = $c->db->select('tbl_color.*
				')
			->join('tbl_color','tbl_color.id = tbl_product.id_color')
			->where('tbl_product.id_style',$id)
			->get('tbl_product');	

	if( $rs->num_rows() > 0 )
	{
		$colors	= $rs->result();
	}
	return $colors;
}

function get_product_sizes($id)
{
	$sizes = FALSE; 
	$c 		=& get_instance();
	$rs = $c->db->select('tbl_product.id as product_id,
				tbl_size.id,
				tbl_size.name
				')
			
			->join('tbl_size','tbl_size.id = tbl_product.id_size')
			->where('tbl_product.id_style',$id)
			->get('tbl_product');	

	if( $rs->num_rows() > 0 )
	{
		$sizes = $rs->result();	
	}
	return $sizes;
}

function getSizesSelected($id_style,$color_select)
{

}

function get_discount($id_cus = 0, $id_pd = 0)
{
	$product 	= get_product($id_pd);
	$cus_disc 	= get_customer_discount($id_cus, $id_pd);  							// ส่วนลดที่กำหนดให้ลูกค้า
	$p_disc		= $product === FALSE && $product->discount_type === 'percentage' ? 0 : $product->discount; 	// ส่วนลดที่ถูกำหนดไว้ที่สินค้า
	$a_disc		= $product === FALSE && $product->discount_type === 'amount' ? 0 : $product->discount; 			// ส่วนลดที่ถูกกำหนดไว้ที่สินค้า
	$price 		= $product === FALSE ? 0 : $product->product_price;
	if( $p_disc != 0 && $a_disc == 0)
	{		// กรณี ส่วนลดเป็น เปอร์เซ็นต์
		$discount 	= $p_disc > $cus_disc ? $p_disc : $cus_disc ;
		$type 		= 'percent';
	}
	else if( $a_disc != 0 && $p_disc == 0 )
	{	// กรณี ส่วนลดในตัวสินค้า เป็น จำนวนเงิน
		$cus_disc 	= ($cus_disc * 0.01) * $price;
		$discount	=  $cus_disc > $a_disc ? $cus_disc : $a_disc;
		$type			= 'amount';			
	}
	else
	{
		$discount 	= $cus_disc;
		$type			= 'percent';	
	}
	$disc = array('discount' => $discount, 'type' => $type);
	return $disc;		
}


function get_product_category($id_pd)
{
	/// ส่ง id_category ของ สินค้า กลับไปเป็น object  1 สินค้า อยู่ได้หลาย category
	$rs = get_instance()->db->select('id_category')->where('id_product', $id_pd)->get('tbl_category_product');	
	if( $rs->num_rows() > 0 )
	{
		return $rs->result();
	}
	else
	{
		return FALSE;
	}
}

function get_product($id_pd)
{
	$product = FALSE;
	$rs = get_instance()->db->where('id_product', $id_pd)->get('tbl_product');
	if( $rs->num_rows() == 1 )
	{
		$product = $rs->row();
	}
	return $product;
}

function getIdProduct($id_pa)
{
	$id_pd = 0;
	$rs = get_instance()->db->select('id_product')->where('id_product_attribute', $id_pa)->get('tbl_product_attribute');
	if( $rs->num_rows() == 1 )
	{
		$id_pd = $rs->row()->id_product;	
	}
	
	return $id_pd;		
}

function get_product_price($id_pd)
{
	$price = 0;
	$rs = get_instance()->db->select('product_price')->where('id_product', $id_pd)->get('tbl_product');
	if( $rs->num_rows() == 1 )
	{
		$price = $rs->row()->product_price;
	}
	return $price;
}

function itemPrice($id_pa)
{
	$price = 0;
	$rs = get_instance()->db->select('price')->where('id_product_attribute', $id_pa)->get('tbl_product_attribute');	
	if( $rs->num_rows() == 1 )
	{
		$price = $rs->row()->price;	
	}
	return $price;
}

function sell_price($price = 0, $discount_amount = 0 , $discount_percent = 0)
{
	if( $discount_percent !== 0 && $price !== 0)
	{
		$discount = $price * ($discount_percent * 0.01);
	}
	return ($price - $discount) - $discount_amount ;
}

function discountAmount($id_pd=0, $qty=0,$price=0)
{

	
}

function discount_label($discount_amount = 0, $discount_percent = 0)
{
	if($discount_percent > 0  && $discount_amount <= 0){
		$discount 	= $discount_percent.' %';
	}else if ($discount_amount > 0 && $discount_percent <= 0 ) {
		$discount 	= number_format($discount_amount,2,'.','').' '.getConfig('CURRENCY');
	}else if($discount_amount > 0 && $discount_percent > 0){
		$discount 	= $discount_percent.' %'.' AND '.number_format($discount_amount,2,'.','').' '.getConfig('CURRENCY');
	}else{
		$discount = null;
	}

	
	return $discount;
}

function is_new_product($id_pd)
{
	$is 			= FALSE;
	$new_days = getConfig('NEW_PRODUCT_DATE');
	$date 		= beforeDate($new_days);
	$rs = get_instance()->db->select('id')->where('id', $id_pd)->where('date_upd >', $date)->get('tbl_product');
	if( $rs->num_rows() == 1 )
	{
		$is		= TRUE; 
	}
	return $is;

}

function get_id_cover_image($id_pd)
{
	$id_image = 0;
	$rs = get_instance()->db->where('id_style', $id_pd)->where('cover', 1)->get('tbl_image');
	if( $rs->num_rows() > 0 )
	{
		$id_image = $rs->row()->id;	
	}
	return $id_image;
}

function get_id_image($id_pd,$size = 2)
{
	$id_pd = (int)$id_pd;

	get_instance()->load->library('product');
	$qs = get_instance()->db->select('id_image')
		->where('id_product',$id_pd)
		->get('tbl_product_image');

		if( $qs->num_rows() > 0 )
		{
			$id_image  = $qs->result_array()[0]['id_image'];
		}else{
			$id_image = '';
		}
		
		return get_image_path($id_image,$size);
		// return $id_image;
		
}

function get_image_path($id, $use_size = 2)
{
		$count		= strlen($id);
		$arr		= str_split($id);
		$path		= base_url()."img/product";
		$n			= 0;
		while( $n < $count )
		{
			$path .= '/' . $arr[$n];
			$n++;
		}
		$path	.= '/';
		$size		= '_default';
		switch( $use_size )
		{
			case 1 :
				$size = '_mini';
			break;
			case 2 :
				$size = '_default';
			break;
			case 3 :
				$size = '_medium';
			break;
			case 4 :
				$size = '_lage';
			break;
		}
		
		if( $count == 0 )
		{
			//----- If image no found
			$path .= 'no_image' . $size . '.jpg';
		}
		else
		{
			//---- if image found
			$path .= 'product' . $size . '_'. $id .'.jpg';
		}
		
		return $path;
}


function prefix_image($size = 2)
{
	switch($size){
		case "1" :
			$pre_fix = "product_mini_";
			break;
		case "2" :
			$pre_fix = "product_default_";
			break;
		case "3" :
			$pre_fix = "product_medium_";
			break;
		case "4" :
			$pre_fix = "product_lage_";
			break;
		default :
			$pre_fix = "";
			break;
		}
	return $pre_fix;
}

function no_image($size = 2)
{
	$img = base_url().'img/product/';
	switch($size){
		case "1" :
			$img .= "no_image_mini.jpg";
			break;
		case "2" :
			$img .= "no_image_default.jpg";
			break;
		case "3" :
			$img .= "no_image_medium.jpg";
			break;
		case "4" :
			$img .= "no_image_lage.jpg";
			break;
		default :
			$img .= "no_image_default.jpg";
			break;
		}
	return $img;
}


function get_image_file($id_image, $size = '2')
{
	$count = strlen($id_image);
	$path = str_split($id_image);
	$file_path	= FILE_PATH.'img/product';
	$n = 0;
	while($n<$count)
	{
		$file_path .= '/'.$path[$n];
		$n++;
	}
	$file_path	.= '/';
	$image	= '';
	$pre_fix = prefix_image($size);
	$file_path .= $pre_fix.$id_image.'.jpg';
		
	return $file_path;
}
function isAttrExists($id_pa, $attr = 'size')
{
	$id = 'id_'.$attr;
	$rs = get_instance()->db->where('id_product_attribute', $id_pa)->where($id.' !=', 0)->get('tbl_product_attribute');
	if( $rs->num_rows() == 1 )
	{
		return TRUE;
	}
	else
	{
		return FALSE;	
	}
}

function attrLabel($id_pa, $attr = 'size')
{
	$res = '';
	$attr = isAttrExists($id_pa, $attr) === TRUE ? $attr : (isAttrExists($id_pa, 'attribute') === TRUE ? 'attribute' : 'color');
	if( isAttrExists($id_pa, $attr) )
	{
		$id_attr = 'id_'.$attr;
		$qs = 'SELECT '.$id_attr.' AS id FROM tbl_product_attribute WHERE id_product_attribute = '.$id_pa;
		
		$rs = get_instance()->db->query($qs);
		if( $rs->num_rows() == 1 )
		{
			if( $attr == 'size' )
			{
				$res = size_name($rs->row()->id);
			}
			else if( $attr == 'attribute' )
			{
				$res = attribute_name($rs->row()->id);	
			}
			else if( $attr == 'color' )
			{
				$res = color_name($rs->row()->id);
			}
		}
	}
	return $res;
}

function attrType($id_pa)
{
	$attr = 'size : ';
	if( isAttrExists($id_pa, 'size') === TRUE )
	{
		$attr = 'size';
	}
	else if( isAttrExists($id_pa, 'attribute') === TRUE )
	{
		$attr = 'attribute';
	}
	else if( isAttrExists($id_pa, 'color') === TRUE )
	{
		$attr = 'color';
	}
	return $attr;
}

function productReference($id_pa)
{
	$reference = '';
	$rs = get_instance()->db->select('reference')->where('id_product_attribute', $id_pa)->get('tbl_product_attribute');
	if( $rs->num_rows() == 1 )
	{
		$reference = $rs->row()->reference;
	}
	return $reference;
}

function color_code($id_color)
{
	$code = '';
	$rs = get_instance()->db->select('color_code')->where('id_color', $id_color)->get('tbl_color');
	if( $rs->num_rows() == 1 )
	{
		$code = $rs->row()->color_code;
	}
	return $code;
}

function color_name($id_color)
{
	$name = '';
	$rs 	= get_instance()->db->select('color_name')->where('id_color', $id_color)->get('tbl_color');
	if( $rs->num_rows() == 1 )
	{
		$name = $rs->row()->color_name;	
	}
	return $name;
}

function size_name($id_size)
{
	$name = '';
	$rs = get_instance()->db->select('size_name')->where('id_size', $id_size)->get('tbl_size');
	if( $rs->num_rows() == 1 )
	{
		$name = $rs->row()->size_name;
	}
	return $name;
}

function attribute_name($id_attr)
{
	$name = '';
	$rs = get_instance()->db->select('attribute_name')->where('id_attribute', $id_attr)->get('tbl_attribute');
	if( $rs->num_rows() == 1 )
	{
		$name = $rs->row()->attribute_name;
	}
	return $name;
}

function apply_stock_filter($qty = 0)
{
	$filter = getConfig('MAX_SHOW_STOCK');
	if( $filter != 0 )
	{
		if( $qty > $filter)
		{
			$qty = $filter;
		}
	}
	return $qty;
}



function getGridByStyle($id_style=0)
{
	$c 		=& get_instance();
	$rs = $c->db->select('tbl_color.id,tbl_color.color_name,tbl_size.id_size,tbl_size.size_name')
		->join('tbl_size','tbl_size.id_size = tbl_product.id_size')
		->join('tbl_color','tbl_color.id = tbl_product.id_color')
		->where('tbl_product.id_style',$id_style)
		->get('tbl_product');

		if( $rs->num_rows() > 0 )
		{
			return $rs->result();	
		}
		else
		{
			return FALSE;
		}	
	
}




?>