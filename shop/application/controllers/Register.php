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

		$this->id_customer  = $this->Member_model->getIdAndRole();//great or member

		$this->id_cart 	    = getIdCart($this->id_customer->id);
		$this->cart_items 	= $this->cart_model->getCartProduct($this->id_cart);
		$this->cart_qty		= $this->cart_model->cartQty($this->id_cart);
	}
	
	public function index()
	{
		$data['title']			= $this->title;
		$data['menus'] =  $this->Menu_model->menus();
		$data['cart_items']		= $this->cart_items==''?$this->cart_items=array():$this->cart_items;
		$data['cart_qty']		= $this->cart_qty;
		if(isset($_SESSION['id_customer'])){
			$data['view'] 			= 'main';	
		}else{
			$data['view'] 			= 'module/register';	
		}
		$this->load->view("include/template", $data);
	}

	public function register(){
		$data['title']			= $this->title;
		$data['cart_items']		= $this->cart_items==''?$this->cart_items=array():$this->cart_items;
		$data['menus'] =  $this->Menu_model->menus();
		if(@$this->session->userdata('id_customer')){
			$data['view'] 			= 'main';	
			$this->load->view("include/template", $data);
		}else{

			$data['customer'] = array(
				"fname"    =>$this->input->post('fname',true),
				"lname"    =>$this->input->post('lname',true),
				"birthdate"=>$this->input->post('birthDate',true),
				"tel"      =>$this->input->post('tel',true),
				"sex"      =>$this->input->post('sex',true),
				"status"   =>"0",
				);

			$data['account'] = array(
				"id_customer_online"	=>"",
				"username"	=>$this->input->post('userName',true),
				"password"	=>md5(md5(md5($this->input->post('password',true)))),
				"email"		=>$this->input->post('email',true),
				"status"	=>"1",
				"last_login"=>date("Y-m-d H:i:s"),
				);

			$data['address'] = array(
				"id_customer_online"	=>"",
				"id_great"				=>"",
				"address_no"			=>$this->input->post('addr',true),
				"subdistrict"			=>$this->input->post('Subdistrict',true),
				"district"				=>$this->input->post('District',true),
				"proviance"				=>$this->input->post('Proviance',true),
				"postcode"				=>$this->input->post('Postcode',true),
				);

			$ck = $this->Register_model->checkDuplicate($this->input->post('userName',true));
			
			if($ck == "notDup"){
				$regis_status = $this->Register_model->register($data);
				// echo $ck." ".$regis_status;

				if($regis_status == "success"){
					$data['view'] 			= 'module/member_info';	
					$data['regis_status']   = "Register Success !";
				}else{
					$data['regis_status']   = "Somthing Wrong !";
					$data['view'] 			= 'module/register';	
				}

				$this->load->view("include/template", $data);
			}else{
				// echo "dup ".$ck;
				// on duplicate
				$data['regis_status']   = "Duplicate Data USERNAME !";
				$data['view'] 			= 'module/register';	

				$this->load->view("include/template", $data);
			}
		}
	}	

	public function add_address(){
		$add_addr =[];
		$id_customer   = $this->id_customer->id;
		$role = $this->id_customer['role'];
		
		if ($this->input->post()) {
	
		$data = Array
			(
			    "fname" =>$this->input->post("fname",true), 
			    "lname" =>$this->input->post("lname",true), 
			    "tel" =>$this->input->post("tel",true), 
			    "addr" =>$this->input->post("addr",true), 
			    "Proviance" =>$this->input->post("Proviance",true), 
			    "District" =>$this->input->post("District",true), 
			    "Subdistrict" =>$this->input->post("Subdistrict",true), 
			    "Postcode" => $this->input->post("Postcode",true)
			);


		$add_addr = $this->Register_model->addMemberAddr($id_customer,$role,$data);
		}
		print_r(json_encode($add_addr));
	
	}

	public function getData(){

		@$ID    = $_GET['ID'];
		@$type  = $_GET['TYPE'];
		
		if($type =='Proviance'){
			$result = $this->Register_model->proviance();
		}else if($type=='District') {
			$result = $this->Register_model->district($ID);
		} else if($type=='Subdistrict'){
			$result = $this->Register_model->subdistrict($ID);

		} else if($type=='Postcode'){
			$result = $this->Register_model->postcode($ID);
		}
		print_r(json_encode($result));
	}//functiom
		
}//class

?>