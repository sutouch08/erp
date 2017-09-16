<?php 

class validate_credentials
{
	
	public function validatePermission($id_tab, $id_profile, $fields="")
	{
		$sc =  FALSE;
		if( $fields == "")
		{
			$qs = dbQuery("SELECT a.add, a.edit, a.delete FROM tbl_access AS a WHERE a.id_profile = ".$id_profile." AND id_tab = ".$id_tab);
			if( dbNumRows($qs) == 1 )
			{
				$rs = dbFetchObject($qs);
				$sc = ($rs->add == 1 OR $rs->edit == 1 OR $rs->delete == 1 ) ? TRUE : FALSE;
			}
			
		}
		else
		{
			$qs = dbQuery("SELECT a.".$fields." AS value FROM tbl_access AS a WHERE a.id_profile = ".$id_profile." AND id_tab = ".$id_tab);
			if( dbNumRows($qs) == 1 )
			{
				$rs = dbFetchObject($qs);
				$sc = $rs->value == 1 ? TRUE : FALSE;
			}
		}
		return $sc;
	}
	
	
}//--- end class
?>