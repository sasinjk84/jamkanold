<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

if(strlen($_ShopInfo->getId())==0){
	echo "<script>alert('�������� ��η� �����Ͻñ� �ٶ��ϴ�.');window.close();</script>";
	exit;
}

$CurrentTime = time();

if ($sid && $return_page && $year && $month && $day) {
	$sql = "DELETE FROM tblschedule WHERE idx = '".$sid."' ";
	$delete = mysql_query($sql,get_db_conn());

	if ($delete) {
		echo "<script>alert('�ش� ������ ���� �Ͽ����ϴ�.');location='".$return_page."?year=".$year."&month=".$month."&day=".$day."';</script>";
		exit;
	} else {
		echo "<script>alert('���� ������ ������ �߻��Ͽ����ϴ�.');history.go(-1);</script>";
		exit;
	}
} else {
	echo "<script>alert('�߸��� ����� �����Դϴ�.');history.go(-1);</script>";
	exit;
}
?>