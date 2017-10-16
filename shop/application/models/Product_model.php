<?php 
class Product_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();	
	}
	
	public function getProduct($parent=0,$child=0,$sub_child=0)
	{
		
		$data = [
			'parent' => $parent,
			'child' => $child,
			'sub_child'=>$sub_child
		];

		$url='http://localhost/ci_rest_server/index.php/api/product/product/product_by_menu';
							
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);	
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);	
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('x-api-key: 1234'));

		$html = curl_exec($curl);
		curl_close ($curl);

		return  json_decode($html);
	}

	public function getAvailableQty($id_pd)
	{
		$qty 		= 0; 
		$move 	    = $this->moveQty($id_pd);
		$cancle 	= $this->cancleQty($id_pd);
		$order	    = $this->orderQty($id_pd);
		$rs 		= $this->db->select_sum('qty')->join('tbl_zone', 'tbl_zone.id_zone = tbl_stock.id_zone')->where('id_product', $id_pd)->where('id_warehouse !=', 2)->get('tbl_stock');

		if( $rs->num_rows() == 1 && !is_null($rs->row()->qty))
		{
			$qty = $rs->row()->qty;
		}
		$qty 	= ($qty + $move + $cancle) - $order;

		return $qty;	
	}

	public function getAvailableQty_OnGrid($id_style,$id_color,$id_size)
	{

		$qty 		= 0; 
		$move 	    = $this->moveQty($id_style);
		$cancle 	= $this->cancleQty($id_style);
		$order	    = $this->orderQty($id_style);


		$rs = $this->db->query("SELECT SUM(qty) AS qty FROM tbl_product INNER JOIN tbl_stock ON CAST(tbl_product.id AS UNSIGNED) = tbl_stock.id_product INNER JOIN tbl_zone ON tbl_zone.id_zone = tbl_stock.id_zone WHERE tbl_product.id IN (SELECT tbl_product.id FROM tbl_product WHERE tbl_product.id_style = $id_style AND tbl_product.id_color = $id_color AND tbl_product.id_size = $id_size )");

		// $qty 		= 0; 
		// $move 	    = $this->moveQty($sub_query);
		// $cancle 		= $this->cancleQty($sub_query);
		// $order	    = $this->orderQty($sub_query);


		if( $rs->num_rows() == 1 && !is_null($rs->row()))
		{
			$qty = $rs->row()->qty;
		}
		$qty 	= ($qty + $move + $cancle) - $order;

		return $qty ;	
	}

	public function cancleQty($id_pa)
	{
		// $qty 	= 0;
		// $rs 	= $this->db->select_sum('qty')->where('id_product_attribute', $id_pa)->get('tbl_cancle');
		// if( $rs->num_rows() == 1 && !is_null($rs->row()->qty) )
		// {
		// 	$qty = $rs->row()->qty;
		// }
		// return $qty;
		return 0;
	}

	public function moveQty($id_pa)
	{
		// $qty = 0;
		// $rs 	= $this->db->select_sum('qty_move')->where('id_product_attribute', $id_pa)->get('tbl_move');
		// if( $rs->num_rows() == 1 && !is_null($rs->row()->qty_move))
		// {
		// 	$qty = $rs->row()->qty_move;
		// }
		// return $qty;
		return 0;
	}

	public function orderQty($id_pa)
	{

		return 0;
	}

	public function getProductDetail($id)
	{
		$rs  = $this->db->select('tbl_product.id as product_id,
			tbl_product.price as product_price,
			tbl_product_style.id as style_id,
			tbl_product_style.code as style_code,
			tbl_product_style.name as style_name,
			promotion.discount_percent,
			promotion.discount_amount,
			tbl_color.id as id_color,
			tbl_color.code as code_color,
			tbl_color.name as color_name,
			tbl_color.id_group,
			tbl_size.id as size_id,
			tbl_size.name as size_name,
			')
		->join('tbl_product_style' , 'tbl_product_style.id = tbl_product.id_style','left')
		->join('tbl_color','tbl_color.id = tbl_product.id_color','left')
		->join('promotion','promotion.id_product = tbl_product.id','left')
		->join('tbl_size','tbl_size.id = tbl_product.id_size','left')
		->where('tbl_product.id',$id)
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

	public function productImages($id_pd)
	{
		$rs = $this->db->where('id_style', $id_pd)->get('tbl_image');
		if( $rs->num_rows() > 0 )
		{
			return $rs->result();	
		}
		else
		{
			return FALSE;
		}
	}

	public function getProductInfo($id_pd)
	{
		$info = '';
		$rs = $this->db->select('product_detail')->where('id_product', $id_pd)->get('tbl_product_detail');
		if( $rs->num_rows() == 1)
		{
			$info = $rs->row()->product_detail;
		}
		return $info;
	}


	public function moreItem($offset,$parent=0,$child=0,$sub_child=0)
	{	

		if($parent != 0 && $child == 0 && $sub_child == 0){
			$rs  = $this->db->select('tbl_product.id as product_id,
				tbl_product.code as product_code,
				tbl_product.name as product_name,
				tbl_product.price as product_price,
				promotion.discount_percent,
				promotion.discount_amount,
				tbl_style.id as style_id,
				tbl_style.code as style_code,
				tbl_style.name as style_name,
				')
			->join('tbl_style' , 'tbl_style.id = tbl_product.id_style')
			->join('product_online','product_online.id_product = tbl_product.id')
			->join('promotion','promotion.id_product = product_online.id_product','left')
			->where('product_online.id_parent_menu',$parent)
			->where('tbl_product.show_in_online',1)
			->where('tbl_product.is_deleted',0)
			->where('tbl_product.active',1)
			->order_by('tbl_product.id_category', 'desc')
			->limit(20,$offset)
			->get('tbl_product');

		}else if($parent != 0 && $child != 0 && $sub_child == 0){
			$rs  = $this->db->select('tbl_product.id as product_id,
				tbl_product.code as product_code,
				tbl_product.name as product_name,
				tbl_product.price as product_price,
				promotion.discount_percent,
				promotion.discount_amount,
				tbl_style.id as style_id,
				tbl_style.code as style_code,
				tbl_style.name as style_name,
				')
			->join('tbl_style' , 'tbl_style.id = tbl_product.id_style')
			->join('product_online','product_online.id_product = tbl_product.id')
			->join('promotion','promotion.id_product = product_online.id_product','left')
			->where('product_online.id_parent_menu',$parent)
			->where('product_online.id_child_menu',$child)
			->where('tbl_product.show_in_online',1)
			->where('tbl_product.is_deleted',0)
			->where('tbl_product.active',1)
			->order_by('tbl_product.id_category', 'desc')
			->limit(20,$offset)
			->get('tbl_product');

		}else if($parent != 0 && $child != 0 && $sub_child != 0){
			$rs  = $this->db->select('tbl_product.id as product_id,
				tbl_product.code as product_code,
				tbl_product.name as product_name,
				tbl_product.price as product_price,
				promotion.discount_percent,
				promotion.discount_amount,
				tbl_style.id as style_id,
				tbl_style.code as style_code,
				tbl_style.name as style_name,
				')
			->join('tbl_style' , 'tbl_style.id = tbl_product.id_style')
			->join('product_online','product_online.id_product = tbl_product.id')
			->join('promotion','promotion.id_product = product_online.id_product','left')
			->where('product_online.id_parent_menu',$parent)
			->where('product_online.id_child_menu',$child)
			->where('product_online.id_subchild_menu',$sub_child)
			->where('tbl_product.show_in_online',1)
			->where('tbl_product.is_deleted',0)
			->where('tbl_product.active',1)
			->order_by('tbl_product.id_category', 'desc')
			->limit(20,$offset)
			->get('tbl_product');

		}

		if( $rs->num_rows() > 0 )
		{
			return $rs->result();	
		}
		else
		{
			return FALSE;
		}	
	}

	public function grid($id_style)
	{
		$rs = $this->db->select('tbl_style.code,tbl_style.name,tbl_color.id_color,tbl_color.color_name,tbl_size.id_size,tbl_size.size_name')
		->join('tbl_size','tbl_size.id_size = tbl_product.id_size')
		->join('tbl_style','tbl_style.id = tbl_product.id_style')
		->join('tbl_color','tbl_color.id_color = tbl_product.id_color')
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


	public function getSizeByColor($select_color,$id_style)
	{


		$rs = $this->db->select('tbl_size.id_size,tbl_size.size_name')
		->join('tbl_size','tbl_size.id_size = tbl_product.id_size')
		->where('tbl_product.id_style',$id_style)
		->where('tbl_product.id_color',$select_color)
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

	public function getProdctFormGrid($id_style,$id_size,$id_color){
		$rs = $this->db->select("tbl_product.id")
		->where('tbl_product.id_style',$id_style)
		->where('tbl_product.id_size',$id_size)
		->where('tbl_product.id_color',$id_color)
		->get('tbl_product');

		return  $rs->result()[0];

	}

	public function getColorGroup($data_product)
	{
		$data =[];
		foreach ($data_product as $key => $value) {
			$data[$key] = $value->style_id; 
		}

		$url='http://localhost/ci_rest_server/index.php/api/product/product/color';
							
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);	
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);	
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('x-api-key: 1234'));

		$html = curl_exec($curl);
		curl_close ($curl);

		return  json_decode($html);
		// return $html;
	}

	public function  getSizeGroup($data_product)
	{
		$data =[];
		foreach ($data_product as $key => $value) {
			$data[$key] = $value->style_id; 
		}

		$url='http://localhost/ci_rest_server/index.php/api/product/product/size';
							
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);	
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);	
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('x-api-key: 1234'));

		$html = curl_exec($curl);
		curl_close ($curl);

		return  json_decode($html);
		// return $html;
	} 


	public function  filter($parent='',$child='',$sub_child='',$color='',$size='',$minPrice=0,$maxPrice=5000)
	{
		$data =[
			"parent"=>$parent,
			"child"=>$child,
			"sub_child"=>$sub_child,
			"color"=>serialize($color),
			"size"=>serialize($size),
			"minPrice"=>$minPrice,
			"maxPrice"=>$maxPrice
		];

		$url='http://localhost/ci_rest_server/index.php/api/product/product/product_filter';
							
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);	
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);	
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('x-api-key: 1234'));

		$html = curl_exec($curl);
		curl_close ($curl);

		return  json_decode($html);
		// return $data;
	} 

}/// End class

?>