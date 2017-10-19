<?php 
class Main extends CI_Controller
{

	public $layout = "include/template";
	public $title  = "ยินดีต้อนรับ";
	
	public function __construct()
	{

		@parent::__construct();		
		
		$this->load->model("main_model");
		$this->load->model('product_model');
		$this->load->model('cart_model');
		$this->load->model('Menu_model');
		$this->load->model('Member_model');

		@$this->home = base_url()."shop/main";
		//$this->customer api success
		$this->customer     = $this->Member_model->getIdAndRole();//great or member
		$this->id_cart 	    = getIdCart($this->customer->id);
		
		//get data by auth
		$this->cart_items 	= $this->cart_model->getCartProduct($this->id_cart);
		$this->cart_qty		= $this->cart_model->cartQty($this->id_cart);
		
	}
	
	public function index($id=0)
	{
		$data['title']			= $this->title;
		$data['cart_items']		= $this->cart_items==''?$this->cart_items=[]:$this->cart_items;
		$data['id_customer']    = $this->customer->id;
		$data['id_cart']		= $this->id_cart;
		$data['cart_qty']		= $this->cart_qty;
		$data['view'] 			= 'main';	

		//get newProduct data from api by curl
		// $data['new_arrivals'] 	= $this->main_model->new_arrivals()==''?[]:$this->main_model->new_arrivals();

		//get features data from api by curl
		$data['features']		= $this->main_model->features()==''?[]:$this->main_model->features();
		
		//api success
		$data['menus']          =  $this->Menu_model->menus();
		

	
		$this->load->view("include/template", $data);
	
	}


	public function loadMoreFeatures()
	{
		$this->load->model('main_model');
		$data = array();
		if( $this->input->post('offset') )
		{
			$result = $this->main_model->moreFeatures($this->input->post('offset'));
			print_r(json_encode($result));
			// if( !$result['message'] )
			// {
			// 	// print_r($result);
			// 	foreach( $result as $rs )
			// 	{

			// 		$promo = 0;
			// 		if( $rs->discount_amount > 0 || $rs->discount_percent > 0 )
			// 		{
			// 			$promo = 1;
			// 		}

			// 		$sp = sell_price($rs->product_price, $rs->discount_amount, $rs->discount_percent);
					
			// 		$arr = array(
			// 			'link'				=>	base_url().'shop/product_detail/product/'.$rs->product_id,
			// 			'image_path'		=> get_id_image((int)$rs->product_id,4),
			// 			'style_id'			=> $rs->style_id,
			// 			'promotion'			=> $promo,
			// 			'new_product'		=> is_new_product($rs->product_id),
			// 			'discount'			=> $rs->discount_amount+$rs->discount_percent,
			// 			'discount_amount'	=> number_format($rs->discount_amount,2,'.',''),
			// 			'discount_percent'	=> number_format($rs->discount_percent,2,'.',''),
			// 			'discount_label'	=> discount_label($rs->discount_amount, $rs->discount_percent),
			// 			'available_qty'    => apply_stock_filter($this->product_model->getAvailableQty($rs->product_id)), 
			// 			'product_code'		=> $rs->style_code,
			// 			'product_name'		=> $rs->style_name,
			// 			'sell_price'		=> number_format($sp),
			// 			'price'				=> number_format($rs->product_price,2,'.','')
			// 		);	
			// 		array_push($data, $arr);
			// 	}//foreach 

			// 	print_r(json_encode($data));
			// }//$result !== FALSE 
			// else
			// {
			// 	print_r(json_encode($result));
			// }
			
		}//$this->input->post('offset')
		
	}//function loadmore
	
	
	
	
}/// end class


?>