<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "pr-1";
$MenuCode = "nomenu";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################



$mode = !_empty($_POST['mode'])?trim($_POST['mode']):"";
$section = !_empty($_POST['section'])?trim($_POST['section']):"";


if(strlen($mode)<=0 || strlen($section)<=0){
	echo '<script>alert("�ʼ����� ���޵��� �ʾҽ��ϴ�.");history.go(-1);</script>';exit;
}else{
	$boardSetSQL="";
	switch($mode){
		case "C":
			$boardSetSQL = "UPDATE tblboardadmin SET grant_mobile = 'N' WHERE board = '".$section."' ";
		break;
		case "U":
			$boardSetSQL = "UPDATE tblboardadmin SET grant_mobile = 'Y' WHERE board = '".$section."' ";
		break;
	}
	

	if(strlen($boardSetSQL)>0){
		if(false !== mysql_query($boardSetSQL,get_db_conn())){
			echo '<script>alert("���� �Ǿ����ϴ�.");location.replace("./mobile_board_display_set.php");</script>';exit;
		}else{
			echo '<script>alert("������ �߻��Ͽ� ó������ �ʾҽ��ϴ�.");location.replace("./mobile_board_display_set.php");</script>';exit;
		}
	}
}
exit;
?>