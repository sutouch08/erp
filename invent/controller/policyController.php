<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";

//---	เพิ่มนโยบายใหม่
if(isset($_GET['addNew']))
{
	include 'policy/policy_add.php';
}


if(isset($_GET['updatePolicy']))
{
	include 'policy/policy_update.php';
}



if(isset($_GET['clearFilter']))
{
	deleteCookie('policyCode');
	deleteCookie('policyName');
	deleteCookie('startDate');
	deleteCookie('endDate');
	echo 'done';
}
?>
