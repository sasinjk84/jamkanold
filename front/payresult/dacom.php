<?
/*
LG������ ���� �뺸 ó�� (RESULT=OK, RESULT=NO)
*/
if(getenv("SERVER_ADDR")!=getenv("REMOTE_ADDR")) exit;

$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

Header("Pragma: no-cache");

$ip=getenv("REMOTE_ADDR");

$ordercode=$_POST["ordercode"];
$paytype=$_POST["paytype"];
$txtype=$_POST["txtype"];
$cflag=$_POST["cflag"];
$price=$_POST["price"];
$ok=$_POST["ok"];

$paymethod="";
$istaxsave=false;
$date=date("YmdHis");

if($paytype=="SC0040" && strlen($ordercode)>0 && strlen($price)>0 && strlen($ok)>0) {	//������� �Ա��뺸
	$sql = "SELECT paymethod, pay_flag, pay_admin_proc, deli_gbn, deli_date FROM tblorderinfo ";
	$sql.= "WHERE ordercode='".$ordercode."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$paymethod=$row->paymethod;
		$pay_flag=$row->pay_flag;
		$pay_admin_proc=$row->pay_admin_proc;
		$deli_gbn=$row->deli_gbn;
		$deli_date=$row->deli_date;
	} else {
		echo "RESULT=NO"; exit;
	}
	mysql_free_result($result);

	if($ok=="Y" && preg_match("/^(O|Q){1}/", $paymethod)) {
		$sql = "UPDATE tblorderinfo SET bank_date='".$date."', deli_gbn='N' ";
		$sql.= "WHERE ordercode='".$ordercode."' AND price='".$price."' ";
		if(mysql_query($sql,get_db_conn())) {
			$sql = "UPDATE tblorderproduct SET deli_gbn='N' ";
			$sql.= "WHERE ordercode='".$ordercode."' ";
			$sql.= "AND NOT (productcode LIKE '999%' OR productcode LIKE 'COU%') ";
			mysql_query($sql,get_db_conn());
		}
	} else if($ok=="C" && preg_match("/^(O|Q){1}/", $paymethod)) {
		$sql = "UPDATE tblorderinfo SET bank_date=NULL ";
		$sql.= "WHERE ordercode='".$ordercode."' AND price='".$price."' ";
		mysql_query($sql,get_db_conn());
	} else {
		echo "RESULT=NO"; exit;
	}

	if(!mysql_error()) {
		echo "RESULT=OK";
		$istaxsave=true;
	} else {
		echo "RESULT=NO";
		if(strlen(AdminMail)>0) {
			@mail(AdminMail,"LG������ ������� �Ա�ó�� �ȵ�","$sql<br>".mysql_error());
		}
	}

	if($istaxsave) {
		$sql = "SELECT tax_type FROM tblshopinfo ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);
		$tax_type=$row->tax_type;
		mysql_free_result($result);

		if($tax_type=="Y") {
			if(preg_match("/^(O|Q){1}/", $paymethod)) {
				$sql = "SELECT bank_date, pay_admin_proc FROM tblorderinfo ";
				$sql.= "WHERE ordercode='".$ordercode."' ";
				$result=mysql_query($sql,get_db_conn());
				$row=mysql_fetch_object($result);
				mysql_free_result($result);
				if(strlen($row->bank_date)>=12 && $row->pay_admin_proc=="Y") {
					$sql = "SELECT COUNT(*) as cnt FROM tbltaxsavelist WHERE ordercode='".$ordercode."' AND type='N' ";
					$result=mysql_query($sql,get_db_conn());
					$row=mysql_fetch_object($result);
					mysql_free_result($result);
					if($row->cnt>0) {
						$flag="Y";
						include($Dir."lib/taxsave.inc.php");
					}
				} else {
					//���ݿ����� ���ó���� �ؾߵɱ�?????
				}
			}
		}
	}
} else if((preg_match("/^(N|C|R)$/", $txtype)) && strlen($ordercode)>0 && strlen($ok)>0) {//�ڵ�����Ȯ��/����Ȯ��/���
	#### paymethod=>"Q|P", pay_flag=>"0000", pay_admin_proc=>"!C", deli_gbn=>"Y", escrow_result=>"N" #####
	$sql = "SELECT paymethod,pay_flag,pay_admin_proc,deli_gbn,deli_date,escrow_result FROM tblorderinfo ";
	$sql.= "WHERE ordercode='".$ordercode."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$paymethod=$row->paymethod;
		$pay_flag=$row->pay_flag;
		$pay_admin_proc=$row->pay_admin_proc;
		$deli_gbn=$row->deli_gbn;
		$deli_date=$row->deli_date;
		$escrow_result=$row->escrow_result;
	} else {
		echo "RESULT=NO"; exit;
	}
	mysql_free_result($result);
	if($ok=="Y" && preg_match("/^(Q|P){1}/", $paymethod)) {			//����Ȯ��
		$sql = "UPDATE tblorderinfo SET escrow_result='Y' ";
		$sql.= "WHERE ordercode='".$ordercode."' ";
		mysql_query($sql,get_db_conn());
		if(!mysql_error()) {
			echo "RESULT=OK";
		} else {
			if(strlen(AdminMail)>0) {
				@mail(AdminMail,"LG������ ����ũ�� ����Ȯ��ó�� �ȵ�","$sql<br>".mysql_error());
			}
			echo "RESULT=NO";
		}
	} else if($ok=="C" && preg_match("/^(Q|P){1}/", $paymethod)) {	//�������
		$sql = "UPDATE tblorderinfo SET ";
		$sql.= "deli_gbn		= 'H' ";
		$sql.= "WHERE ordercode='".$ordercode."' ";
		mysql_query($sql,get_db_conn());
		if(!mysql_error()) {
			$sql = "UPDATE tblorderproduct SET deli_gbn='H' ";
			$sql.= "WHERE ordercode='".$ordercode."' ";
			$sql.= "AND NOT (productcode LIKE '999%' OR productcode LIKE 'COU%') ";
			mysql_query($sql,get_db_conn());

			echo "RESULT=OK";
		} else {
			if(strlen(AdminMail)>0) {
				@mail(AdminMail,"LG������ ����ũ�� �������ó�� �ȵ�","$sql<br>".mysql_error());
			}
			echo "RESULT=NO";
		}
	} else {
		echo "RESULT=NO"; exit;
	}
} else if($txtype=="X" && strlen($ordercode)>0) {		//����ũ�� ������� �ԱݿϷ�� �ǿ� ���ؼ� ������
	######### paymethod=>"Q|P", pay_flag=>"0000", pay_admin_proc=>"N|Y", (deli_gbn=>"N|S|X" OR (deli_gbn=>"D" AND strlen(deli_date)==0))  ######
	$sql = "SELECT paymethod, pay_flag, pay_admin_proc, bank_date, deli_gbn, deli_date FROM tblorderinfo ";
	$sql.= "WHERE ordercode='".$ordercode."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$paymethod=$row->paymethod;
		$pay_flag=$row->pay_flag;
		$pay_admin_proc=$row->pay_admin_proc;
		$bank_date=$row->bank_date;
		$deli_gbn=$row->deli_gbn;
		$deli_date=$row->deli_date;
	} else {
		echo "RESULT=NO"; exit;
	}
	mysql_free_result($result);
	if(preg_match("/^(Q){1}/", $paymethod)) {
		$sql = "SELECT tax_type FROM tblshopinfo ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);
		$tax_type=$row->tax_type;
		mysql_free_result($result);

		if($tax_type=="Y") {	//���ݿ����� �ڵ� ����
			$sql = "SELECT COUNT(*) as cnt FROM tbltaxsavelist WHERE ordercode='".$ordercode."' AND type='Y' ";
			$result=mysql_query($sql,get_db_conn());
			$row=mysql_fetch_object($result);
			mysql_free_result($result);
			if($row->cnt>0) {
				$flag="C";
				include($Dir."lib/taxsave.inc.php");
			}
		}

		if (strlen($bank_date)==14) {
			$deliupdate =" deli_gbn='E' ";	//ȯ�Ҵ��
			$up_deli_gbn="E";
		} else {
			$deliupdate = " deli_gbn='C' ";
			$up_deli_gbn="C";
		}

		$sql = "UPDATE tblorderinfo SET ".$deliupdate." ";
		$sql.= "WHERE ordercode='".$ordercode."' ";
		mysql_query($sql,get_db_conn());
		if(!mysql_error()) {
			$sql = "UPDATE tblorderproduct SET deli_gbn='".$up_deli_gbn."' ";
			$sql.= "WHERE ordercode='".$ordercode."' ";
			$sql.= "AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') ";
			mysql_query($sql,get_db_conn());
			echo "RESULT=OK";
		} else {
			if(strlen(AdminMail)>0) {
				@mail(AdminMail,"LG������ ����ũ�� ������ó�� �ȵ�","$sql<br>".mysql_error());
			}
			echo "RESULT=NO";
		}
	} else {
		echo "RESULT=NO"; exit;
	}
} else if($txtype=="D" && strlen($ordercode)>0) {	//������Ұ�� (����ũ�� ������� ȯ���뺸)
	######### paymethod=>"Q", pay_flag=>"0000", pay_admin_proc=>"N", deli_gbn=>"E"  ######
	$sql = "SELECT paymethod, pay_flag, pay_admin_proc, deli_gbn, bank_date FROM tblorderinfo ";
	$sql.= "WHERE ordercode='".$ordercode."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$paymethod=$row->paymethod;
		$pay_flag=$row->pay_flag;
		$pay_admin_proc=$row->pay_admin_proc;
		$deli_gbn=$row->deli_gbn;
		$bank_date=$row->bank_date;
	} else {
		echo "RESULT=NO"; exit;
	}
	mysql_free_result($result);

	if(preg_match("/^(Q){1}/", $paymethod)) {
		$sql = "UPDATE tblorderinfo ";
		$sql.= "SET pay_admin_proc='C', deli_gbn='C', bank_date='".substr($bank_date,0,8)."X' ";
		$sql.= "WHERE ordercode='".$ordercode."' ";
		mysql_query($sql,get_db_conn());
		if(!mysql_error()) {
			$sql = "UPDATE tblorderproduct SET deli_gbn='C' ";
			$sql.= "WHERE ordercode='".$ordercode."' ";
			$sql.= "AND NOT (productcode LIKE '999%' OR productcode LIKE 'COU%') ";
			mysql_query($sql,get_db_conn());

			echo "RESULT=OK";
		} else {
			if(strlen(AdminMail)>0) {
				@mail(AdminMail,"LG������ ������� ȯ�ҿϷ�ó�� �ȵ�","$sql<br>".mysql_error());
			}
			echo "RESULT=NO";
		}
	} else {
		echo "RESULT=NO";
	}
}
?>