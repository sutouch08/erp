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
		$url = "http://localhost/ci_rest_server/index.php/api/product/product/ProductDetail/?id=".$id;
		$this->curl->create($url);
		$this->curl->http_header('x-api-key','1234');
		return  json_decode($this->curl->execute());
	}

	public function productImages($id_pd)
	{
		$url = "http://localhost/ci_rest_server/index.php/api/product/product/product_images/?id=".$id_pd;
		$this->curl->create($url);
		$this->curl->http_header('x-api-key','1234');
		return  json_decode($this->curl->execute());
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
		$this->curl->create('http://localhost/ci_rest_server/index.php/api/product/product/moreProduct/?offset='.$offset.'&parrent='.$parent.'&child='.$child.'&sub_child='.$sub_child);
		$this->curl->http_header('x-api-key','1234');
		return  json_decode($this->curl->execute());	

	}

	public function grid($id_style)
	{
		$url = "http://localhost/ci_rest_server/index.php/api/product/product/product_grid/?id=".$id_style;
		$this->curl->create($url);
		$this->curl->http_header('x-api-key','1234');
		return  json_decode($this->curl->execute());

	}


	public function getSizeByColor($select_color,$id_style)
	{

		$url = "http://localhost/ci_rest_server/index.php/api/product/product/izeByColor/?style=".$id_style."&color=".$select_color;
		$this->curl->create($url);
		$this->curl->http_header('x-api-key','1234');
		return  json_decode($this->curl->execute());
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
			array_push($data,$value->style_id); 
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
		// return $data;
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


	public function  filterItem($id_style='',$color='',$size='',$minPrice=0,$maxPrice=5000)
	{
		$data =[
			"id_style"=>serialize($id_style),
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