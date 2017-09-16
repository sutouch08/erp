<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";

if( isset( $_GET['validateCredentials'] ) )
{
	$s_key 	= $_POST['s_key'];
	$id_tab 	= $_POST['id_tab'];
	$field 		= $_POST['field'];
	$emp 		= new employee();
	$employee = $emp->whoUseThisKey($s_key);
	if( $employee === FALSE )
	{
		$sc = 'wrong password';
	}
	else
	{
		$vd = new validate_credentials();
		//--- field is add/ edit / delete if not spacific will be return true if some value = 1 
		$rs = $vd->validatePermission($id_tab, $employee->id_profile, $field); 
		if($rs === TRUE )
		{
			$sc = 'allow';
		}
		else
		{
			$sc = "You don't have permission !";
		}
	}
	echo $sc;	
}


?>