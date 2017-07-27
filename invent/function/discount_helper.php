<?php
function showDiscountByProductGroup($id_customer, $id_product_group)
{
	$sc = 0.00;
	$qs = dbQuery("SELECT discount FROM tbl_customer_discount WHERE id_customer = '".$id_customer."' AND id_product_group = '".$id_product_group."'");
	if( dbNumRows($qs) > 0 )
	{
		list( $sc ) = dbFetchArray($qs);	
	}
	return $sc;
}


?>