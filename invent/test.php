<?php
require '../library/config.php';
require '../library/functions.php';
require "function/tools.php";
require "function/sponsor_helper.php";
require "function/support_helper.php";
require "function/lend_helper.php";
?>

<!DOCTYPE HTML>
<html>

<head>

    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="../favicon.ico" />
    <title>ทดสอบระบบ</title>

    <!-- Core CSS - Include with every page -->
    <link href="<?php echo WEB_ROOT; ?>library/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo WEB_ROOT; ?>library/css/paginator.css" rel="stylesheet">
    <link href="<?php echo WEB_ROOT; ?>library/css/font-awesome.css" rel="stylesheet">
    <link href="<?php echo WEB_ROOT; ?>library/css/bootflat.min.css" rel="stylesheet">
     <link rel="stylesheet" href="<?php  echo WEB_ROOT;?>library/css/jquery-ui-1.10.4.custom.min.css" />
     <script src="<?php echo WEB_ROOT; ?>library/js/jquery.min.js"></script>
    
  	<script src="<?php  echo WEB_ROOT;?>library/js/jquery-ui-1.10.4.custom.min.js"></script>
    <script src="<?php echo WEB_ROOT; ?>library/js/bootstrap.min.js"></script>
     


<body>
<div class="container">
<?php
require '../library/class/PHPExcel.php';
include 'function/date_helper.php';
$path = getConfig('IMPORT_PRODUCT_GROUP_PATH'); 
$movePath = getConfig('MOVE_PRODUCT_GROUP_PATH');
$sc = opendir($path);

if( $sc )
{
	
	while( $file = readdir($sc) )
	{
		if( $file == '.' || $file == '..')
		{
			continue;
		}
		$fileName = $path.$file;
		$moveFileName = $movePath.$file;
		$reader = new PHPExcel_Reader_Excel5();
		$excel = $reader->load($fileName);
		
		$cellCollection = $excel->getActiveSheet()->toArray(null, TRUE, TRUE, TRUE);
		$i = 1;
		$pg = new product_group();
		foreach($cellCollection as $row)
		{
			if( $i != 1 )
			{
				$code = trim( $row['A'] );
				if( $pg->isExists( $code ) === FALSE )
				{
					$date = PHPExcel_Shared_Date::ExcelToPHPObject($row['N']);
					$arr = array( 
										'code'		=> trim($row['A']),
										'name'		=> $row['B'],
										'date_upd'	=> $date->format('Y-m-d')
										);
					$pg->add($arr);		
											
				}
				else
				{
					$code = trim( $row['A'] );
					$date = PHPExcel_Shared_Date::ExcelToPHPObject($row['N']);
					$arr = array( 
										'name'		=> $row['B'],
										'date_upd'	=> $date->format('Y-m-d')
										);
										
					$pg->update( $code, $arr);	
					
				}
			}
			$i++;
		}
		
		rename($fileName, $moveFileName);
	}	
}
else
{
echo 'fail to open file';	
}
closedir($sc);

			
			?>

</div>

</body>

</html>
