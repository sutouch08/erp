<?php

require '../../library/config.php';
require '../../library/functions.php';
require '../function/tools.php';
require '../function/sponsor_helper.php';
include '../function/date_helper.php';


//---	เพิ่มรายชื่อผู้รับสปอนเซอร์ใหม่พร้อมเพิ่มงบประมาณในตัว
//---	แต่งบประมาณยังไม่ถูกอนุมัตินะ
if( isset( $_GET['addNewSponsor']))
{
	include 'sponsor/sponsor_add.php';
}






//---	ตรวจสอบปีซ้ำกันมั้ย
if( isset($_GET['checkDuplicatedYear']))
{
	$id_budget = isset( $_GET['id_budget']) ? $_GET['id_budget'] : FALSE;
	$id_sponsor = $_GET['id_sponsor'];
	$year = $_GET['year'];
	$bd = new sponsor_budget();
	$rs = $bd->isExistsYear($id_sponsor, $year, $id_budget);
	echo $rs === FALSE ? 'ok' : 'Duplicated';
}







//---	เพิ่มงบประมาณใหม่
if( isset($_GET['addNewBudget']))
{
	$bd = new sponsor_budget();

	//---	ตรวจสอบก่อนว่าปีซ้ำกันมั้ย ถ้าไม่ซ้ำเพิ่มใหม่ได้เลย
	//---	(ไม่สามารถซ้ำกันได้เพราะ database ออกแบบให้เป็น unique)
	if( $bd->isExistsYear($_POST['id_sponsor'], $_POST['year']) === FALSE )
	{
		$arr = array(
						'reference' => trim($_POST['reference']),
						'id_sponsor' => $_POST['id_sponsor'],
						'id_customer' => $_POST['id_customer'],
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

	$id = $_POST['id_budget'];
	$bd = new sponsor_budget($id);
	$id_sponsor = $_POST['id_sponsor'];
	$year = $_POST['year'];
	if( $bd->isExistsYear($id_sponsor, $year, $id) === FALSE )
	{
		$arr = array(
						'reference' => trim($_POST['reference']),
						'start' => dbDate($_POST['fromDate']),
						'end'	=> dbDate($_POST['toDate']),
						'year' => dbYear($_POST['year']),
						'budget'	=> $_POST['budget'],
						'remark'	=> $_POST['remark']
					);

		//---	ถ้ามีการแก้ไขเพิ่มยอดงบประมาณต้องได้รับการอนุมัติก่อนใช้งาน
		//---	เป็นสถานะเป็นรออนุมัติ
		if( $bd->budget < $_POST['budget'])
		{
			$arr['active'] = 0;
		}
		
		$rs = $bd->update($id, $arr);
		if( $rs === TRUE )
		{
			$bd->calculate($id);
		}

		echo $rs === FALSE ? 'แก้ไขงบประมาณไม่สำเร็จ' : 'success';
	}
	else
	{
		echo 'ปีงบประมาณซ้ำ กรุณาเลือกใหม่';
	}
}





//---	approve budget
if( isset( $_GET['approveBudget']))
{
	$bd = new sponsor_budget();
	$id = $_POST['id_budget'];
	$arr = array(
					'active' => $_POST['active'],
					'approver' => $_POST['id_emp'],
					'approve_key' => $_POST['approve_key']
				);


	$rs = $bd->update($id, $arr);

	echo $rs === TRUE ? 'success' : 'ไม่สำเร็จ';
}






//---	set using budget
if( isset( $_GET['setActiveBudget']))
{
	$id_sponsor = $_POST['id_sponsor'];
	$id_budget 	= $_POST['id_budget'];

	$sp = new sponsor();
	$rs = $sp->update($id_sponsor, array('id_budget' => $id_budget));

	echo $rs === TRUE ? 'success' : 'เปลี่ยนงบประมาณไม่สำเร็จ';
}



//---	รายละเอียดงบประมาณ (เปลียนปี)
if( isset( $_GET['getBudgetData']))
{
	$id = $_GET['id_budget'];
	$bd = new sponsor_budget($id);
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





//---	ค้นหารายชื่อลูกค้าเพื่อเพิ่มผู้รับ
if( isset( $_GET['getCustomer']) && $_REQUEST['term'])
{
	$sc = array();
	$cs = new customer();
	$qs = $cs->search(trim($_REQUEST['term']), 'id, code, name');
	while( $rs = dbFetchObject($qs))
	{
		$sc[] = $rs->code .' | ' . $rs->name . ' | ' . $rs->id;
	}

	echo json_encode($sc);
}





//---	ตรวจสอบรายชื่อซ้ำ
if( isset( $_GET['isExistsSponsor']))
{
	$id_customer = $_POST['id_customer'];
	$sponsor = new sponsor();
	$id = $sponsor->getId($id_customer);

	echo $id == 0 ? 'ok' : $id;
}





if( isset($_GET['clearFilter']))
{
	deleteCookie('sSponsorName');
	deleteCookie('sActive');
	echo 'done';
}
?>
