<?php
require '../../library/config.php';
require '../../library/functions.php';
require '../function/tools.php';
include '../function/report_helper.php';

if(isset($_GET['poBacklogs']) && isset($_GET['report']))
{
  include 'report/purchase/report_po_backlogs.php';
}


if(isset($_GET['poBacklogs']) && isset($_GET['export']))
{
  if($_GET['showItem'] == 0)
  {
    include 'report/purchase/export_po_backlogs_by_style.php';
  }
  else
  {
    include 'report/purchase/export_po_backlogs_by_item.php';
  }

}

 ?>
