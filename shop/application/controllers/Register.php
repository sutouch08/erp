<?php 
class Register extends CI_Controller
{	
	
	public $layout = "include/template";
	public $title  = "Register";

	public function __construct()
	{
		parent::__construct();		
		$this->load->model("Register_model");
		$this->load->model('cart_model');
		$this->load->model('Menu_model');
		$this->load->model('Member_model');

		$this->home = base_url()."shop/main";

		$this->id_customer  = $this->Member_model->Validate_Great();//great or member

		$this->id_cart 	    = getIdCart($this->id_customer->id);
		$this->cart_items 	= $this->cart_model->getCartProduct($this->id_cart);
		$this->cart_qty		= $this->cart_model->cartQty($this->id_cart);
	}
	
	public function index()
	{
		$data['title']			= $this->title;
		$data['menus'] 			=  $this->Menu_model->menus();
		$data['cart_items']		= $this->cart_items==''?$this->cart_items=array():$this->cart_items;
		$data['cart_qty']		= $this->cart_qty;
		if(isset($_SESSION['id_customer'])){
			$data['view'] 			= 'main';	
		}else{
			$data['view'] 			= 'module/register';	
		}
		$this->load->view("include/template", $data);
	}

	public function getData(){

		@$ID    = $_GET['ID'];
		@$type  = $_GET['TYPE'];

		switch ($type) {
			case "Proviance":
				$result = $this->Register_model->proviance();
			break;
			case "District":
				$result = $this->Register_model->district($ID);
			break;
			case "Subdistrict":
				$result = $this->Register_model->subdistrict($ID);
			break;
			case "Postcode":
				$result = $this->Register_model->postcode($ID);
			break;
			default:
			
		}
		
		print_r(json_encode($result));
	}//functiom

	//********************************************************************
	//                        REGISTER
	//********************************************************************

	public function register(){

		$data['title']			= $this->title;
		$data['cart_items']		= $this->cart_items==''?$this->cart_items=array():$this->cart_items;
		$data['menus'] =  $this->Menu_model->menus();

		if(@$this->session->userdata('id_customer')){
			$data['view'] 			= 'main';	
			$this->load->view("include/template", $data);
		}
		else
		{
			$data['register']['ip_address'] = 
			array(
				"ip_address"=>$this->input->ip_address()
			);

			$data['register']['customer'] =
			 array(
				"fname"    =>$this->input->post('fname',true),
				"lname"    =>$this->input->post('lname',true),
				"birthdate"=>$this->input->post('birthDate',true),
				"tel"      =>$this->input->post('tel',true),
				"sex"      =>$this->input->post('sex',true),
				"status"   =>"0",
			);

			$data['register']['account'] = 
			array(
				"id_customer_online"	=>"",
				"username"	=>$this->input->post('userName',true),
				"password"	=>md5(md5(md5($this->input->post('password',true)))),
				"email"		=>$this->input->post('email',true),
				"status"	=>"1",
				"last_login"=>date("Y-m-d H:i:s"),
			);

			$data['register']['address'] = 
			array(
				"id_customer_online"	=>"",
				"id_great"				=>"",
				"address_no"			=>$this->input->post('addr',true),
				"subdistrict"			=>$this->input->post('Subdistrict',true),
				"district"				=>$this->input->post('District',true),
				"proviance"				=>$this->input->post('Proviance',true),
				"postcode"				=>$this->input->post('Postcode',true),
			);


				$regis_status = $this->Register_model->register($data['register']);
				
				if($regis_status->status == "success"){
					$data['view'] 			= 'module/member_info';	
					$data['regis_status']   = "Register Success !";
				}else{
					$data['regis_status']   = "Somthing Wrong !";
					$data['view'] 			= 'module/register';	
				}

				$this->load->view("include/template", $data);
			
		}//else
	}//function

	

}//class

?>