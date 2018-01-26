<?php
require '../../library/config.php';
require '../../library/functions.php';
require '../function/tools.php';
require '../function/zone_helper.php';



if(isset($_GET['addNew']))
{
  include 'consign_check/consign_check_add.php';

}


if(isset($_GET['updateHeader']))
{
  $id  = $_POST['id_consign_check'];
  $arr = array(
    'date_add' => dbDate($_POST['date_add']),
    'remark'   => addslashes($_POST['remark'])
  );

  $cs = new consign_check();

  $sc = $cs->update($id, $arr);

  echo $sc === TRUE ? 'success' : $cs->error;
}


 ?>
