<?php
class Product_detail extends CI_Controller
{
	public $layout = "include/template";
	public $title = "รายละเอียดสินค้า";
	
	public function __construct()
	{
		parent::__construct();		
		$this->load->model("main_model");
		$this->load->model('product_model');
		$this->load->model('cart_model');
		$this->load->model('Menu_model');
		$this->load->model('Member_model');

		$this->home = base_url()."shop/main";

		$this->customer     = $this->Member_model->Validate_Great();//great or member
		$this->id_cart 	    = getIdCart($this->customer->id);
		$this->cart_items 	= $this->cart_model->getCartProduct($this->id_cart);
		$this->cart_qty		= $this->cart_model->cartQty($this->id_cart);
	}
	
	public function product($id)
	{

		$h = 5;
		$id_pd = sprintf("%0".$h."d",$id);

		$data['title']			= 'Product Details';
		$data['product'] 		= $this->product_model->getProductDetail($id_pd);
		$data['images']			= $this->product_model->productImages($data['product'][0]->style_id);

		// echo "<pre>";
		// print_r($data['images']);
		// exit();

		// $data['grid']			= $this->product_model->grid($data['product'][0]->style_id);
		
		$data['view']			= 'product_detail';
		$data['cart_items']		= $this->cart_items==''?$this->cart_items=array():$this->cart_items;
		$data['customer']       = $this->customer;
		$data['id_cart']		= $this->id_cart;
		$data['cart_qty']		= $this->cart_qty;
		$data['menus']			=  $this->Menu_model->menus();

		$this->load->view($this->layout, $data);	
	}
	
}
/// end class

?>