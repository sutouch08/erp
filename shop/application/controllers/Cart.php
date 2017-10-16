<?php
class Cart extends CI_Controller
{
	public $home;
	public $layout = "include/template";
	public $title = "ตะกร้าสินค้า";
	public $cart_items;
	public $width,$height,$long;

	public function __construct()
	{
		parent:: __construct();
		$this->load->model('product_model');
		$this->load->model('cart_model');
		$this->load->model('Menu_model');
		$this->home = base_url()."shop/main";
		$this->customer     = getIdCustomer();
		$this->bank 		= getBank();
		$this->id_cart 	    = getIdCart($this->customer['id']);
		$this->cart_value	= 0;
		$this->cart_items 	= $this->cart_model->getCartProduct($this->id_cart);
		$this->cart_qty		= $this->cart_model->cartQty($this->id_cart);
	}
	
	public function index()
	{

	}
	
	
	public function cart($id=0)
	{
		$data['title']			= 'Cart detail';
		$data['view'] 			= 'cart';

		$data['id_cart']		= $this->id_cart;
		$data['cart_qty']		= $this->cart_qty;
		$data['menus'] 			= $this->Menu_model->menus();
		
		$data['item_in_cart']  = $this->cart_model->getItemInCart($this->id_cart);
		$data['transport']	   = $this->cart_model->getTrans($data['item_in_cart'],$this->id_cart);
		$data['address']	   = $this->cart_model->getAddress($this->customer);
		$data['bank']		   = $this->bank ;
		
		

		$this->load->view($this->layout, $data);

	}
	public function getCartQty()
	{
		if( $this->input->post('id_cart',true) )
		{
			$id_cart = $this->input->post('id_cart',true);
			if( $id_cart != 0 )
			{
				$qty = $this->cart_model->cartQty($id_cart);
				echo $qty;	
			}
			else
			{
				echo 'no_item';
			}				
		}
	}
	
	public function updateCart()
	{
		if( $this->input->post('id_pd',true) && $this->input->post('qty',true) )
		{
			$id_cart 	= $this->id_cart ;
			$id_pd 		= $this->input->post('id_pd',true);
			$qty 		= $this->input->post('qty',true);	
			$rs = $this->cart_model->updateCartProduct($id_cart, $id_pd, $qty);
			if( $rs == true )
			{
				echo 'success';
			}
			else
			{
				echo 'fail';
			}
		}
	}
	
	public function deleteCartProduct()
	{
		if($this->input->post('id_pd') )
		{
			
			$id_pd 	= $this->input->post('id_pd');
			$rs = $this->cart_model->deleteCartProduct($this->id_cart , $id_pd);
			if( $rs )
			{
				echo 'success';
			}
			else
			{
				echo 'fail';
			}
		}
		
	}
	
	private function cal_carton($item_weight,$item_width,$item_height,$item_long){

		$boxAttr    = $this->cart_model->getBox();
		$box_weight = 0;
		$box_width  = 0;
		$box_long   = 0;
		$box_height = 0;


		foreach ($boxAttr as $box) {
			$box_weight  = $box->box_weight;
			$box_width   = $box->box_width;
			$box_long    = $box->box_long;
			$box_height  = $box->box_height;

			if($item_width < $box_width && $item_long < $box_long){
				$box_width -= 10;
				$box_long  -= $item_long;
			}

			return $box_width;

		}
	}

	public function getCostTrans(){
		
		$data['item_in_cart']  = $this->cart_model->getItemInCart($this->id_cart);
		$data['transport']	   = $this->cart_model->getTrans($data['item_in_cart'],$this->id_cart);
		$se = [];
		if ($this->input->post('id',true)) {
			//*********************** Create Order *************************
			// $this->cart_model->create_order();
			
			foreach ($data['transport'] as $key => $value) {
				if($value['id'] == $this->input->post('id',true)){
					array_push($se,$value);
				}
			}
		}
		print_r(json_encode($se));
	}

	
	public function getBank()
	{	
		$id_bank = $this->input->post('id_bank',true);
		foreach ($this->bank as $key => $value) {
			if($value->id_account == $id_bank)
			{
				print_r(json_encode($value));
			}
		}
		
		
	}


}/// end class

?>