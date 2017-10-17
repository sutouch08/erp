<?php

require '../../library/config.php';
require '../../library/functions.php';
require '../function/tools.php';
require '../function/support_helper.php';
include '../function/date_helper.php';


//---	เพิ่มรายชื่อผู้รับสปอนเซอร์ใหม่พร้อมเพิ่มงบประมาณในตัว
//---	แต่งบประมาณยังไม่ถูกอนุมัตินะ
if( isset( $_GET['addNewSupport']))
{
	include 'support/support_add.php';
}






//---	ตรวจสอบปีซ้ำกันมั้ย
if( isset($_GET['checkDuplicatedYear']))
{
	$id_budget = isset( $_GET['id_budget']) ? $_GET['id_budget'] : FALSE;
	$id_support = $_GET['id_support'];
	$year = $_GET['year'];
	$bd = new support_budget();
	$rs = $bd->isExistsYear($id_support, $year, $id_budget);
	echo $rs === FALSE ? 'ok' : 'Duplicated';
}







//---	เพิ่มงบประมาณใหม่
if( isset($_GET['addNewBudget']))
{
	$bd = new support_budget();

	//---	ตรวจสอบก่อนว่าปีซ้ำกันมั้ย ถ้าไม่ซ้ำเพิ่มใหม่ได้เลย
	//---	(ไม่สามารถซ้ำกันได้เพราะ database ออกแบบให้เป็น unique)
	if( $bd->isExistsYear($_POST['id_support'], $_POST['year']) === FALSE )
	{
		$arr = array(
						'reference' => trim($_POST['reference']),
						'id_support' => $_POST['id_support'],
						'id_employee' => $_POST['id_employee'],
						'start' => dbDate($_POST['fromDate']),
						'end'	=> dbDate($_POST['toDate']),
						'year' => dbYear($_POST['year']),
						'budget'	=> $_POST['budget'],
						'balance' => $_POST['budget'],
						'remark'	=> $_POST['remark']
					);
		$rs = $bd->add($arr);

		echo $rs === FALSE ? 'เพิ่มงบประมาณไม่สำเร็จ' : 'success';
	}
	else
	{
		echo 'ปีงบประมาณซ้ำ กรุณาเลือกใหม่';
	}

}





if( isset($_GET['updateBudget']))
{
	include 'support/support_budget_update.php';
}







//---	approve budget
if( isset( $_GET['approveBudget']))
{
	$bd = new support_budget();
	$id = $_POST['id_budget'];
	$arr = array(
					'active' => $_POST['active'],
					'approver' => $_POST['id_emp'],
					'approve_key' => $_POST['approve_key']
				);


	$rs = $bd->update($id, $arr);

	echo $rs === TRUE ? 'success' : 'ไม่สำเร็จ';
}





//---	Delete support
if( isset($_GET['deleteSupport']))
{
	include 'support/support_delete.php';
}





//---	set using budget
if( isset( $_GET['setActiveBudget']))
{
	$id_support = $_POST['id_support'];
	$id_budget 	= $_POST['id_budget'];

	$sp = new support();
	$rs = $sp->update($id_support, array('id_budget' => $id_budget));

	echo $rs === TRUE ? 'success' : 'เปลี่ยนงบประมาณไม่สำเร็จ';
}







//---	รายละเอียดงบประมาณ (เปลียนปี)
if( isset( $_GET['getBudgetData']))
{
	$id = $_GET['id_budget'];
	$bd = new support_budget($id);
	$arr = array(
					'id'	=> $bd->id,
					'reference' => $bd->reference,
					'budget'	=> $bd->budget,
					'start'	=> thaiDate($bd->start),
					'end'	=> thaiDate($bd->end),
					'year'	=> $bd->year,
					'remark' => $bd->remark
				);
	echo json_encode($arr);
}








//---	check transection before delete
if( isset($_GET['checkTransection']))
{
	$id_employee = $_GET['id_employee'];
	$role = 3; //---- อภินันท์
	$order = new order();
	$rs = $order->isExitsTransection($id_employee, $role);

	echo $rs === TRUE ? 'transection_exists' : 'no_transection';
}








//---	ค้นรายชื่อผู้รับสปอนเซอร์ ใช้ในการสั่งออเดอร์
if( isset($_GET['getSupportEmpolyee']))
{
	$date = dbDate($_GET['date']);
	$sc = array();
	$cs = new support();
	$qs = $cs->getSupportAndBudgetBalance(trim($_REQUEST['term']), $date);
	if(dbNumRows($qs) > 0)
	{
		while( $rs = dbFetchObject($qs))
		{
			$sc[] = $rs->name. ' | '. $rs->id_employee . ' | '. $rs->id_budget;
		}

	}
	else
	{
		$sc[] = 'ไม่พบข้อมูล';
	}

	echo json_encode($sc);

}


//---	ค้นหาพนักงาน
if( isset( $_GET['getEmployee']))
{
	$sc = array();
	$emp = new employee();
	$qs = $emp->search('id_employee, first_name, last_name', trim($_REQUEST['term']));
	if( dbNumRows($qs) > 0)
	{
		while($rs = dbFetchObject($qs))
		{
			$sc[] = $rs->first_name.' '.$rs->last_name. ' | ' .$rs->id_employee;
		}

	}
	else
	{
		$sc[] = 'ไม่พบข้อมูล';
	}

	echo json_encode($sc);
}



if( isset( $_GET['getBudgetBalance']))
{
	$id_employee = $_GET['id_employee'];
	$sp = new support();
	echo number($sp->getBudgetBalanceByEmployee($id_employee), 2);
}



//---	ตรวจสอบรายชื่อซ้ำ
if( isset( $_GET['isExistsSupport']))
{
	$id_employee = $_POST['id_employee'];
	$support = new support();
	$id = $support->getId($id_employee);

	echo $id == 0 ? 'ok' : $id;
}





if( isset($_GET['clearFilter']))
{
	deleteCookie('sSupportName');
	deleteCookie('sSupportYear');
	echo 'done';
}
?>
