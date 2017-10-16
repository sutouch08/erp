<?php 
class Register_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();	
	}//contruct

	public function register($data){

		$this->db->trans_start();

		$this->db->insert('customer_online', $data['customer']);
			$id = $this->db->insert_id();//last id
			
			$data['address']["id_customer_online"] =  $id;
			$data['account']["id_customer_online"] =  $id;

			$this->db->insert('customer_online_address', $data['address']);
			$this->db->insert('account_customer_online', $data['account']);

			$rs = $this->db->select('id_great')->where('ip_address', $this->input->ip_address())->get('great');

			if( $rs->num_rows() == 1 )
			{
				//check great's id cart
				$id_great = $rs->row()->id_great;
				$checkCart = $this->db->select('id_cart')->where('id_great',$id_great)->where('id_customer','0')->get('cart_online');

				$id_cart = $checkCart->row()->id_cart;

				//update id customer and great id in cart
				$data = array(
					'id_customer' => $id,
					'id_great' => '0'
					);

				$this->db->where('id_cart', $id_cart);
				$this->db->update('cart_online', $data); 


			}

			// trans success
			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE)
			{
				return 'fail';
			}else{
				//session set
				$last_id = $this->Register_model->getLast();
				$user_data = $this->Register_model->getUserData($last_id[0]->id_customer);


				$userdata = array(
					'id_customer'     => $user_data[0]->id_customer,
					'first_name'     => $user_data[0]->fname,
					'last_name'     => $user_data[0]->lname,					
					);
				$this->session->set_userdata($userdata);

				return 'success';
			}


		// $data['address']["id_customer_online"] = 1;
		// return $data['address']["id_customer_online"];
		}//function


		public function proviance(){

			$query="SELECT PROVINCE_ID, PROVINCE_NAME FROM province ORDER BY PROVINCE_NAME ASC";
			$result= $this->db->query($query);

			return $result->result() ;
		}

		public function district($ID){
			$query="SELECT AMPHUR_ID, AMPHUR_NAME FROM amphur WHERE PROVINCE_ID='".$ID."'";
			$result= $this->db->query($query);

			return $result->result() ;
		}

		public function subdistrict($ID){
			$query="SELECT DISTRICT_ID, DISTRICT_NAME FROM district WHERE AMPHUR_ID='".$ID."'";
			$result= $this->db->query($query);

			return $result->result() ;
		}

		public function postcode($ID){
			$query="SELECT POST_CODE FROM amphur_postcode WHERE AMPHUR_ID='".$ID."'";
			$result= $this->db->query($query);

			return $result->result();
		}


		public function getLast(){

			$rs = $this->db->select_max('id_customer')->get('customer_online');
			if ($rs->num_rows() > 0)
			{
				return $rs->result();
			}


		}

		public function getUserData($id='')
		{
			$query="SELECT * FROM customer_online WHERE id_customer ='".$id."'";
			$result= $this->db->query($query);

			return $result->result() ;
		}

		public function checkDuplicate($str_username=''){
			$rs = $this->db->where('username', $str_username)->get('account_customer_online');
			if( $rs->num_rows() > 0 )
			{
				return "dup";
			}
			else
			{
				return "notDup";
			}

		}

		public function addMemberAddr($id_customer,$role="",$form_data=[])
		{
			$greatID = 0;
			$id_c = 0;
			if ($role=="member") {
				$id_c = $id_customer;
			}else{
				$greatID     = $id_customer;
			}

			$data = array(
				'id_address'=>'',
				'id_customer_online'=>$id_c, 
				'id_great'=>$greatID,
				'fname'=>$form_data['fname'],
				'lname'=>$form_data['lname'],
				'address_no'=>$form_data['addr'],
				'subdistrict'=>$form_data['Subdistrict'], 
				'district'=>$form_data['District'],
				'proviance'=>$form_data['Proviance'], 
				'postcode'=>$form_data['Postcode']
				);
			// return $form_data['addr'];
			if ($this->db->insert('customer_online_address', $data)) 
			{
				if ($role == "member") {
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

					->where('customer_online_address.id_customer_online',$id_c)
					->get('customer_online_address');
					return $rs->result() ;

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

					->where('customer_online_address.id_great',$greatID)
					->get('customer_online_address');

					return $rs->result() ;
				}

			} 


		}

		public function updateMemberAddr($id=0)
		{


		}




}//class


?>