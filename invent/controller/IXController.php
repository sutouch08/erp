<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";
include "../../library/db.php";
include "ix/ixFunctions.php";


//---- export TR to IX
if(isset($_GET['export_ix_transfer']))
{
  include "ix/export_ix_transfer.php";
}



//----- export mv to IX
if(isset($_GET['export_ix_move']))
{
  include "ix/export_ix_move.php";
}


//--- export orde to IX
if(isset($_GET['export_ix_order']))
{
  include "ix/export_ix_order.php";
} //--- end function




//----- get order list to export (for auto export)
if(isset($_GET['get_order_list']))
{
  $from_date = fromDate($_GET['from_date']);
  $to_date = toDate($_GET['to_date']);
  $limit = $_GET['limit'];

  $qr  = "SELECT id FROM tbl_order ";
  $qr .= "WHERE role IN(1,2) ";
  $qr .= "AND state IN(8, 9, 10) ";
  $qr .= "AND date_add >= '{$from_date}' ";
  $qr .= "AND date_add <= '{$to_date}' ";
  $qr .= "AND ix IN(0,3) ";
  $qr .= "ORDER BY date_add ASC ";
  $qr .= "LIMIT {$limit}";

  $qs = dbQuery($qr);
  if(dbNumRows($qs) > 0)
  {
    $ds = array();
    while($rs = dbFetchObject($qs))
    {
      $ds[] = $rs->id;
    }

    echo json_encode($ds);
  }
  else
  {
    echo 'not found';
  }

}



//----- get order list to export (for auto export)
if(isset($_GET['get_move_list']))
{
  $from_date = fromDate($_GET['from_date']);
  $to_date = toDate($_GET['to_date']);
  $limit = $_GET['limit'];

  $qr  = "SELECT id FROM tbl_move ";
  $qr .= "WHERE isSaved = 1 ";
  $qr .= "AND isCancle = 0 ";
  $qr .= "AND date_add >= '{$from_date}' ";
  $qr .= "AND date_add <= '{$to_date}' ";
  $qr .= "AND ix IN(0,3) ";
  $qr .= "ORDER BY date_add ASC ";
  $qr .= "LIMIT {$limit}";

  $qs = dbQuery($qr);
  if(dbNumRows($qs) > 0)
  {
    $ds = array();
    while($rs = dbFetchObject($qs))
    {
      $ds[] = $rs->id;
    }

    echo json_encode($ds);
  }
  else
  {
    echo 'not found';
  }

}



//----- get order list to export (for auto export)
if(isset($_GET['get_transfer_list']))
{
  $from_date = fromDate($_GET['from_date']);
  $to_date = toDate($_GET['to_date']);
  $limit = $_GET['limit'];

  $qr  = "SELECT id FROM tbl_transfer ";
  $qr .= "WHERE isSaved = 1 ";
  $qr .= "AND isCancle = 0 ";
  $qr .= "AND date_add >= '{$from_date}' ";
  $qr .= "AND date_add <= '{$to_date}' ";
  $qr .= "AND ix IN(0,3) ";
  $qr .= "ORDER BY date_add ASC ";
  $qr .= "LIMIT {$limit}";

  $qs = dbQuery($qr);
  if(dbNumRows($qs) > 0)
  {
    $ds = array();
    while($rs = dbFetchObject($qs))
    {
      $ds[] = $rs->id;
    }

    echo json_encode($ds);
  }
  else
  {
    echo 'not found';
  }

}




 ?>
