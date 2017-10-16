<?php 
class Forgot_password extends CI_Controller
{	
	
	public $layout = "include/template";
	public $title = "Forgot Password";



	public function __construct()
	{
		parent::__construct();		
		
		$this->load->model("Forgot_model");
		$this->load->model('cart_model');
		$this->load->model('Menu_model');
		$this->home = base_url()."shop/main";
		$this->id_customer  = getIdCustomer();
		$this->id_cart 	    = getIdCart($this->id_customer);
		$this->cart_value	= $this->cart_model->cartValue($this->id_cart);
		$this->cart_items 	= $this->cart_model->getCartProduct($this->id_cart);
		$this->cart_qty		= $this->cart_model->cartQty($this->id_cart);
		
		
	}
	
	public function index()
	{
		$data['title']			= $this->title;
		$data['view'] 			= 'module/forgot_password';	
		$data['menus'] 			=  $this->Menu_model->menus();
		$this->load->view("include/template", $data);

	}
}

?>