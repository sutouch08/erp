<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";

//---	เพิ่มนโยบายใหม่
if(isset($_GET['addNew']))
{
	include 'policy/policy_add.php';
}

?>
