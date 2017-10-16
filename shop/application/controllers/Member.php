<?php 
class Member extends CI_Controller
{	

	public $layout = "include/template";
	public $title = "Member Info";
	


	public function __construct()
	{
		parent::__construct();		
		$this->load->model("Member_model");
		$this->load->model("login_model");
		$this->load->model('cart_model');
		$this->load->model('Menu_model');
		$this->home = base_url()."shop/main";
		$this->id_customer  = getIdCustomer();
		$this->id_cart 	    = getIdCart($this->id_customer['id']);
		// $this->cart_value	= $this->cart_model->cartValue($this->id_cart);
		$this->cart_items 	= $this->cart_model->getCartProduct($this->id_cart);
		$this->cart_qty		= $this->cart_model->cartQty($this->id_cart);
		
		
	}
	
	public function index()
	{
		$data['title']			= $this->title;
		$data['view'] 			= 'module/member_info';	
		$data['cart_items']		= $this->cart_items==''?$this->cart_items=array():$this->cart_items;
		$data['order_detail']	= $this->Member_model->getOrder_Detail();	
		$data['address']		= $this->Member_model->getAddress_Online($this->id_customer);

		$data['menus'] =  $this->Menu_model->menus();
		
		$this->load->view("include/template", $data);

	}

	public function validation()
	{	
	
		if($this->input->post()){

			$user 	= $this->input->post("username",true);
			$pass 	= $this->input->post("password",true);

		    // $pass 	= md5(md5(md5($this->input->post("password"))));

			$rs 	= $this->login_model->userValidation($user, $pass);

			//is member
			if( $rs )
			{
				$user_info = $this->login_model->getUserInfo($rs->id_customer_online);
				
				$userdata = array(
					'id_customer'   => $user_info->id_customer,
					'first_name'	=> $user_info->fname,
					'last_name'		=> $user_info->lname,
				);

				$this->session->set_userdata($userdata);
				
				$id_cart_great  = $this->id_cart;
				$id_cart_member = $this->login_model->getIdCartMember($user_info->id_customer);

				$this->login_model->loged($user_info->id_customer);
				$this->login_model->switch_cart_item($id_cart_great,$id_cart_member);
				
				print_r($userdata);
				// print_r($this->session->userdata());

			}
			else
			{
				echo "error";
			}
		}
		else
		{
			echo "error";
		}
	}

	// public function logOut()
	// {
	// 	$this->login_model->loged_out($_SESSION['id_customer']);

	// 	$userdata = array('id_customer','first_name','last_name','email');
	// 	$this->session->unset_userdata($userdata);
	// 	session_destroy();
	// 	redirect('shop/main', 'refresh');

	// }

}

?>