<?php
class Cart_model extends CI_Model
{
	public function __construct()
	{
		parent:: __construct();	
	}
	
	public function getCartProduct($id_cart)
	{
		$rs = $this->db->where('id_cart_online',$id_cart)->get('cart_product_online');
		if( $rs->num_rows() > 0 )
		{
			return $rs->result();
		}
		else
		{
			return FALSE;
		}
	}

	public function getItemInCart($id_cart){
		$rs = $this->db->select('cart_product_online.qty,
			tbl_product.id,
			tbl_product_style.code,
			tbl_product_style.name,
			tbl_product.price,
			promotion.discount_percent,
			promotion.discount_amount,
			tbl_product.weight,
			tbl_product.width,
			tbl_product.length,
			tbl_product.height,
			tbl_color.*,
			tbl_size.*')
		
		->join('tbl_product','tbl_product.id = cart_product_online.id_product')
		->join('promotion','promotion.id_product = tbl_product.id','left')
		->join('tbl_product_style','tbl_product_style.id = tbl_product.id_style')
		->join('tbl_color','tbl_color.id = tbl_product.id_color')
		->join('tbl_size','tbl_size.id = tbl_product.id_size')
		->where('cart_product_online.id_cart_online',$id_cart)
		->get('cart_product_online');


		if( $rs->num_rows() > 0 )
		{
			return $rs->result();
		}
		else
		{
			return FALSE;
		}
	}

	public function getAddress($cus)
	{
		$id = $cus['id'];
		$role = $cus['role'];
		
		if ($cus['role'] == "member") {
			$rs = $this->db->select('
				customer_online_address.id_address,
				customer_online_address.id_customer_online,
				customer_online_address.id_great,
				customer_online_address.fname,
				customer_online_address.lname,
				customer_online_address.address_no,
				customer_online_address.postcode,
				province.PROVINCE_NAME,
				amphur.AMPHUR_NAME,
				district.DISTRICT_NAME,

				')
			->join('province','province.PROVINCE_ID = customer_online_address.proviance')
			->join('amphur' , 'amphur.AMPHUR_ID = customer_online_address.district')
			->join('district','district.DISTRICT_ID = customer_online_address.subdistrict')

			->where('customer_online_address.id_customer_online',$id)
			->get('customer_online_address');
		}else{
			$rs = $this->db->select('
				customer_online_address.id_address,
				customer_online_address.id_customer_online,
				customer_online_address.id_great,
				customer_online_address.fname,
				customer_online_address.lname,
				customer_online_address.address_no,
				customer_online_address.postcode,
				province.PROVINCE_NAME,
				amphur.AMPHUR_NAME,
				district.DISTRICT_NAME,

				')
			->join('province','province.PROVINCE_ID = customer_online_address.proviance')
			->join('amphur' , 'amphur.AMPHUR_ID = customer_online_address.district')
			->join('district','district.DISTRICT_ID = customer_online_address.subdistrict')

			->where('customer_online_address.id_great',$id)
			->get('customer_online_address');
		}

		return $rs->result();
	}

	public function getTrans($item,$id_cart)
	{
		if(!empty($item)){
			$transport= [];
			$qty = 0;
			$discount = getDiscount($id_cart);
			$total_price = 0;

			$rs = $this->db->select("transport_rule.*,transport_with.logistic_name
				")
			->join('transport_with','transport_rule.rule_for_logis_id = transport_with.id_logistic','right')
			->where('transport_rule.actived',1)
			->get('transport_rule');

			if( $rs->num_rows() > 0 )
			{
				foreach ($item as $value) {
					$qty += $value->qty;
					$total_price += $value->price*$value->qty;
				}
				$total_price = $total_price - $discount;
				
				switch ($rs->result()[0]->id_rule) {
				    case "0":
				        return "case 0";
				        break;
				    case "1":
				        return "case 1";
				        break;
				    //other
				    default:
						foreach ($rs->result() as $res) {
							$trans_data = [];
							//filter by price
							if($res->lower_price <= $total_price && $res->lower_item <= $qty)
							{	
								$trans_data['id'] = $res->id_rule;
								$trans_data['name'] = $res->logistic_name;
								$trans_data['trans_price'] = 0;
							}
							else
							{
								$trans_data['id'] = $res->id_rule;
								$trans_data['name'] = $res->logistic_name;
								$trans_data['trans_price'] = $res->fix_price;
							}
							array_push($transport,$trans_data);
						}//foreach $res
				}//switch case
				
				return $transport;
			}// if $rs->num_rows() > 0
			else
			{
				return "not set some active";
			}
		}
		else
		{
			return "no item";
		}
	}

	public function getTCost($id)
	{
		$rs = $this->db->select("*")
		->where('id_rule',$id)
		->where('actived',1)
		->get('transport_rule');

		if( $rs->num_rows() > 0 )
		{
			return $rs->result();
		}
		else
		{
			return FALSE;
		}
	}
	
	public function getTypeTrans($id_trans)
	{
		$rs = $this->db->select('*')->where('id_logistic',$id_trans)
		->get('transport_domestic_type');
		if( $rs->num_rows() > 0 )
		{
			return $rs->result();
		}
		else
		{
			return FALSE;
		}
	}

	public function getAttr($id_pd)
	{
		
		$rs = $this->db->where('id', $id_pd)->get('tbl_product');
		if( $rs->num_rows() > 0 )
		{
			return $rs->result();
		}
		else
		{
			return FALSE;
		}
	}
	public function createCart($data)
	{
		$rs = $this->db->insert('tbl_cart', $data);
		if( $rs )
		{
			return $this->db->insert_id();
		}
		else
		{
			return FALSE;
		}
	}
	
	public function addToCart($data)
	{

		$url='http://localhost/ci_rest_server/index.php/api/cart/cart/addToCart';
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);	
		curl_setopt($curl, CURLOPT_POST, true);	
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('x-api-key: 1234'));

		$html = curl_exec($curl);
		curl_close ($curl);

		return  json_decode($html);
		
		

	}
	
	
	public function cartQty($id_cart = 0)
	{
		$qty = 0;
		if( $id_cart != 0 )
		{
			$rs = $this->db->select_sum('qty')->where('id_cart_online', $id_cart)->get('cart_product_online');
			if( $rs->row()->qty != 0 && $rs->row()->qty !== NULL )
			{
				$qty = $rs->row()->qty;
			}
		}
		return $qty;
	}
	
	public function updateCartProduct($id_cart, $id_pd, $qty)
	{
		
		return $this->db->where('id_cart_online', $id_cart)->where('id_product', $id_pd)->update('cart_product_online', array('qty'=>$qty));
		

	}
	
	public function deleteCartProduct($id_cart, $id_pd)
	{
		return $this->db->where('id_cart_online', $id_cart)->where('id_product', $id_pd)->delete('cart_product_online');	
	}

	public function getBox(){
		$rs = $this->db->get('box_attribute');

		if( $rs )
		{
			return $rs->result();;
		}
		else
		{
			return FALSE;
		}
	}

	public function createGreatID(){

		//not member
		$rs = $this->db->select('id_great')->where('ip_address', $this->input->ip_address())->get('great');

		if( $rs->num_rows() == 1 )
		{
			// have id great 
			$id_great = $rs->row()->id_great;
			return $id_great;
		}else{

			$data = array(
				'id_great' => '',
				'ip_address' => $this->input->ip_address() ,
			);

			$this->db->insert('great', $data); 
			$insert_id = $this->db->insert_id();

			return $insert_id;
		}
		
	}

	public function createCartID($great_id){

		if($this->session->userdata('id_customer')){

		}else{
			$rs = $this->db->select('id_cart')->where('id_customer',0)->where('id_great',$great_id)->where('cart_status',0)->get('cart_online');

			if( $rs->num_rows() == 1 )
			{
				// have id cart 
				$id_cart = $rs->row()->id_cart;
				return $id_cart;
			}else{
				$data = array(
					'id_cart' => '',
					'id_customer' => '0',
					'id_great'=>$great_id,
					'date_add'=> date("Y-m-d H:i:s"),
					'cart_status'=>'0'
				);

				$this->db->insert('cart_online', $data); 
				$insert_id = $this->db->insert_id();
				return $insert_id;	
			}//else
		}//else
		
		
	}//function


	public function getCart_ID($id_customer){

		
		$rs = $this->db->select('id_cart')->where('id_great','0')->where('id_customer',$id_customer)->get('cart_online');
		if( $rs->num_rows() == 1 )
		{
			return $rs->row()->id_cart;
		}else{
			return '';
		}
		
	}


}// end class;


?>