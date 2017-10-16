<?php
class Login_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function userValidation($user, $pass)
	{
		$rs = $this->db->where("username", $user)->where("password", $pass)->get("account_customer_online");
		if($rs->num_rows() == 1 )
		{
			return $rs->result()[0];
		}
		else
		{
			return false;
		}
	}
	public function getUserInfo($id_user){
		$rs = $this->db->where("id_customer", $id_user)->get("customer_online");
		if($rs->num_rows() == 1 )
		{
			return $rs->row();
		}
		else
		{
			return false;
		}
	}
	
	public function loged($id_user)
	{
		date_default_timezone_set('Asia/Bangkok');

		return $this->db->where("id_customer_online", $id_user)->update("account_customer_online", array("status"=>'1',"last_login" => date("Y-m-d H:i:s")));	
	}
	public function loged_out($id_user){
		return $this->db->where("id_customer_online", $id_user)->update("account_customer_online", array("status"=>'0'));	
	}

	public function switch_cart_item($id_cart_great,$id_cart_member){
		return $this->db->where("id_cart_online",$id_cart_great)->update("cart_product_online", array("id_cart_online"=>$id_cart_member));
	}
	
	public function getIdCartMember($id_member){
		$rs = $this->db->select('id_cart')->where("id_customer", $id_member)->get("cart_online");
		if($rs->num_rows() == 1 )
		{
			return $rs->row()->id_cart;
		}
		else
		{
			return false;
		}
	}

}/// end class

?>