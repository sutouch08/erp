<?php 

class Product extends CI_Controller
{

	public $layout = "include/template";
	public $title = "สินค้า";

	
	public function __construct()
	{
		parent::__construct();		
		$this->load->model("main_model");
		$this->load->model('product_model');
		$this->load->model('cart_model');
		$this->load->model('Menu_model');
		$this->load->model('Member_model');
		
		$this->home = base_url()."shop/main";

		$this->id_customer  = $this->Member_model->getIdAndRole();//great or member
		$this->id_cart 	    = getIdCart($this->id_customer->id);
		$this->cart_value	= $this->cart_value	= 0;
		$this->cart_items 	= $this->cart_model->getCartProduct($this->id_cart);
		$this->cart_qty		= $this->cart_model->cartQty($this->id_cart);
		$this->product_qty  = 0;
		
		
	}
	
	public function item($parent= 0,$child= 0,$sub_child= 0){
		$data['title']			= $this->title;		
		$data['view']			= 'module/product';
		
		$product   = $this->product_model->getProduct($parent,$child,$sub_child);	
		
		// echo "<pre>";
		// print_r($product);
		// exit();

		$data['product']	    = $product;
		$this->product_qty      = count($product);

		$data['color']   = $this->product_model->getColorGroup($data['product']);
		$data['size']    = $this->product_model->getSizeGroup($data['product']);

		// echo "<pre>";
		// print_r($data['color']);
		// exit();
	

		//filter data 
		//form submit
		if($this->input->get()){

			$color = $this->input->get('color',true);
			$size  = $this->input->get('size',true);

			if(empty($color))
			{
				$color = [];
				foreach ($data['color'] as $key => $value) {
					array_push($color,$value->id);
				}

			}
			if(empty($size)){
				$size = [];
				foreach ($data['size'] as $key => $value) {
					array_push($size,$value->id);
				}
			}
			

			$minPrice = $this->input->get('minPrice',true);
			$maxPrice = $this->input->get('maxPrice',true);

			$id_style  = [];
			foreach ($data['product'] as $key => $value) {
				if ($value->style_id) {
					array_push($id_style,$value->style_id);
				}
				
			}

			$data['product'] = $this->product_model->filterItem($id_style,$color,$size,$minPrice,$maxPrice);

			// echo "<pre>";
			// print_r($color);
			// exit();
			
		}//if get filter

		$data['cart_items']		= $this->cart_items==''?$this->cart_items=[]:$this->cart_items;
		$data['id_customer']    = $this->id_customer->id;
		$data['id_cart']		= $this->id_cart;
		$data['cart_qty']		= $this->cart_qty;
		$data['menus'] 			= $this->Menu_model->menus();

		$this->load->view("include/template",$data);
		
	}

	

	public function loadMoreItem()
	{
		
		$data = [];
		if( $this->input->post('offset') )
		{
			$result = $this->product_model->moreItem($this->input->post('offset'),$this->input->post('parent',true),$this->input->post('child',true),$this->input->post('sub_child',true));
			if( $result !== FALSE )
			{
				foreach( $result as $rs )
				{
					$promo = 0;
					if( $rs->discount_amount > 0 || $rs->discount_percent > 0 )
					{
						$promo = 1;
					}
					$sp = sell_price($rs->product_price, $rs->discount_amount, $rs->discount_percent);
					$arr = array(
						'link'				=>	'main/productDetail/'.$rs->product_id,
						'image_path'		=> get_image_path(get_id_cover_image($rs->product_id), 3),
						'style_id'			=> $rs->style_id,
						'promotion'			=> $promo,
						'new_product'		=> is_new_product($rs->product_id),
						'discount'			=> $rs->discount_amount+$rs->discount_percent,
						'discount_amount'	=> number_format($rs->discount_amount,2,'.',''),
						'discount_percent'	=> number_format($rs->discount_percent,2,'.',''),
						'discount_label'	=> discount_label($rs->discount_amount, $rs->discount_percent),
						'available_qty'    => apply_stock_filter($this->product_model->getAvailableQty($rs->product_id)), 
						'product_code'		=> $rs->style_code,
						'product_name'		=> $rs->style_name,
						'sell_price'		=> number_format($sp),
						'price'				=> number_format($rs->product_price,2,'.','')
					);	
					array_push($data, $arr);
				}//foreach 
				print_r(json_encode($data));
			}//$result !== FALSE 
			else{
				print_r([]);
			}
			
		}//$this->input->post('offset')

		
	}//function loadmore
	
	public function orderGrid(){
		

		$grid =$this->product_model->grid($this->input->post('id_style'));

		print_r(json_encode($grid));

	}//orderGrid

	public function fetchSize()
	{
		
		$select_color    = $this->input->post('color_select');
		$id_style		 = $this->input->post('id_style');
		$rs              = $this->product_model->getSizeByColor($select_color,$id_style);
		print_r(json_encode($rs));

	}


	public function addToCart()
	{	
		$data    = $this->input->post('dataChoosed',true);
		$data_insert = [];

		foreach ($data as $item) {
			if($item['qty'] > 0)
			{
				$id_product = $this->product_model->getProdctFormGrid($item['id_style'],$item['id_size'],$item['id_color']);
				array_push($data_insert,array("id_cart_product_online"=>'',"id_cart_online"=>$this->id_cart,"id_product"=>$id_product->id,"qty"=>$item['qty'],"date_add"=>''));
			}
		}
		$insert_status = $this->cart_model->addToCart($data_insert);
		// $this->db->insert('cart_product_online', $data_insert); 

		print_r($insert_status);
		
	}

	public function getAvailable_qty(){
		$qty = $this->product_model->getAvailableQty_OnGrid($this->input->post('id_style',true),$this->input->post('id_color',true),$this->input->post('id_size',true));
		print_r($qty);

	}

}

?>