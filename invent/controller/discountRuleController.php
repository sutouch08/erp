<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";

//---	เพิ่มนเงื่อนไขใหม่
if(isset($_GET['addNew']))
{
	include 'rule/new_rule.php';
}


if(isset($_GET['updateRule']))
{
	include 'rule/rule_update.php';
}


//------ กำหนดเงื่อนไขส่วนลดในกฏ
if(isset($_GET['setDiscount']))
{
	include 'rule/rule_set_discount.php';
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
