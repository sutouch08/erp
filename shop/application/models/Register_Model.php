<?php 
class Register_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();	
	}//contruct

	public function register($data){

		$url='http://localhost/ci_rest_server/index.php/api/register/register/register';
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);	
		curl_setopt($curl, CURLOPT_POST, true);	
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('x-api-key: 1234'));

		$html = curl_exec($curl);
		curl_close ($curl);

		return  json_decode($html);
		// return $html;

	}//function


	public function proviance(){

		$url = "http://localhost/ci_rest_server/index.php/api/register/register/proviance";
		$this->curl->create($url);
		$this->curl->http_header('x-api-key','1234');
		return  json_decode($this->curl->execute());
	}

	public function district($ID){
		$url = "http://localhost/ci_rest_server/index.php/api/register/register/district?id=".$ID;
		$this->curl->create($url);
		$this->curl->http_header('x-api-key','1234');
		return  json_decode($this->curl->execute());
	}

	public function subdistrict($ID){
		$url = "http://localhost/ci_rest_server/index.php/api/register/register/subdistrict?id=".$ID;
		$this->curl->create($url);
		$this->curl->http_header('x-api-key','1234');
		return  json_decode($this->curl->execute());
	}

	public function postcode($ID){
		$url = "http://localhost/ci_rest_server/index.php/api/register/register/postcode?id=".$ID;
		$this->curl->create($url);
		$this->curl->http_header('x-api-key','1234');
		return  json_decode($this->curl->execute());
	}

	

	public function updateMemberAddr($id=0)
	{


	}




}//class


?>