<?php
class Menu_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}


	function menus() {
        
       $this->curl->create('http://localhost/ci_rest_server/index.php/api/menubar/menubar/menubar');
        $this->curl->http_header('x-api-key','1234');
        return  json_decode($this->curl->execute());
    }

}