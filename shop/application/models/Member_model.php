<?php 
class Member_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getAddress_Online($id_cus){

		$query = $this->db->select('customer_online_address.id_address , customer_online_address.address_no,customer_online_address.postcode,district.DISTRICT_NAME,amphur.AMPHUR_NAME,province.PROVINCE_NAME')
		->from('customer_online_address','customer_online_address.id_customer_online = customer_online.id_customer')
		->join('district','district.DISTRICT_ID = customer_online_address.subdistrict')
		->join('amphur','amphur.AMPHUR_ID = customer_online_address.district')
		->join('province','province.PROVINCE_ID = customer_online_address.proviance')
		->where('customer_online_address.id_customer_online',$id_cus)
		->get(); 

		if( $query->num_rows() > 0 )
		{
			return $query->result();	
		}
		else
		{
			return false;
		}
	}

	public function getIdAndRole()
	{
		

	// $ci->session->unset_userdata('id_customer');
	if(!$this->session->userdata('id_customer')){// is not member ?
		//not member

		$data = [
			'ip_address', $this->input->ip_address()
		];

		$url='http://localhost/ci_rest_server/index.php/api/users/users/getVisitor';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);	
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);	
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('x-api-key: 1234'));

		$html = curl_exec($curl);
		curl_close ($curl);

		return  json_decode($html);

		
	}else{
		return array("id"=>$this->session->userdata('id_customer'),"role"=>"member"); //member
	}
}

}

?>