<?php
class approver
{

  public function __construct(){

  }


  //---
  public function getApprover($doc_type, $id_emp)
  {
    $sc = FALSE;
    $qs = dbQuery("SELECT * FROM tbl_approver WHERE doc_type = '".$doc_type."' AND id_employee = '".$id_emp."'");
    if( dbNumRows($qs) == 1 )
    {
      $sc = dbFetchObject($qs);
    }

    return $sc;
  }
}
 ?>
