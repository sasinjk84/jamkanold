<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
include_once($Dir."lib/admin_more.php");


/* �߰� jdy */
$admin_chk = $_POST[admin_chk];
/* �߰� jdy */
/* ������⺻�������� ��������� ������� �ʵ��� �����ϸ� �α����� �ȵ� */

$_dataShopMoreInfo = getShopMoreInfo();

//function_use = 1 �ΰ�� ������ü�� ����ϴ� ���̳� �߰������� ���� insert �ȵǾ����� ������ ���� �ƹ��͵� ���� ��쵵 ����� ����Ҽ� �ֵ��� �Ǵ��Ѵ�.
if ($admin_chk!="1" && $_dataShopMoreInfo['function_use']=="0" && strlen($_dataShopMoreInfo['function_use'])>0) {
	echo "<script> alert(\"����� ����Ͻ� �� �����ϴ�.\\n\\n���θ��� �����Ͻñ� �ٶ��ϴ�.\");location.href='/vender/'; </script>";
	exit;
}
/* ������⺻�������� ��������� ������� �ʵ��� �����ϸ� �α����� �ȵ� */

$_VenderInfo = new _VenderInfo($_COOKIE[_vinfo]);

$connect_ip = getenv("REMOTE_ADDR");

$id = $_POST[id];
$passwd = $_POST[passwd];

$ssltype=$_POST["ssltype"];
$sessid=$_POST["sessid"];

$history="-1";
$ssllogintype="";
if($ssltype=="ssl" && strlen($id)>0 && strlen($sessid)==32) {
	$ssllogintype="ssl";
	$history="-2";
}

if (strlen($id)>0 && (strlen($passwd)>0 || $ssllogintype=="ssl")) {
	$sql = "SELECT vender, id, disabled FROM tblvenderinfo ";
	$sql.= "WHERE id='".$id."' AND delflag='N' ";
	if($ssllogintype=="ssl") {
		$sql.= "AND authkey='".$sessid."' ";
	} else {

		//$sql.= "AND passwd=md5('".$passwd."') ";
		/* ���� jdy */
		if ($admin_chk=="1") {
			$sql.= "AND passwd='".$passwd."' ";
		}else{
			$sql.= "AND passwd=md5('".$passwd."') ";
		}
		/* ���� jdy */
	}
	$result=@mysql_query($sql,get_db_conn());
	if($row=@mysql_fetch_object($result)) {
		$vidx=$row->vender;
		$id = $row->id;
		$disabled = (int)$row->disabled;

		if ($disabled==1) {
			echo "<script> alert(\"�ش� ��ü�� ���� �������̹Ƿ� �α����� �Ұ����մϴ�.\\n\\n���θ��� �����Ͻñ� �ٶ��ϴ�.\");history.go(".$history."); </script>";
			exit;
		} else {
			$authkey = md5(uniqid(""));

			$_VenderInfo->setVidx($vidx);
			$_VenderInfo->setId($id);
			$_VenderInfo->setAuthkey($authkey);
			$_VenderInfo->Save();

			$_ShopInfo->Save();

			$sql = "UPDATE tblvenderinfo SET authkey='', logindate='".date("YmdHis")."' ";
			$sql.= "WHERE id = '".$id."'";
			$update=@mysql_query($sql,get_db_conn());

			$sql = "INSERT tblvendersession SET ";
			$sql.= "authkey		= '".$authkey."', ";
			$sql.= "vender		= '".$vidx."', ";
			$sql.= "ip			= '".$connect_ip."', ";
			$sql.= "date		= '".date("YmdHis")."' ";
			mysql_query($sql,get_db_conn());

			$log_content = "�α��� : $id";
			$_VenderInfo->ShopVenderLog($vidx,$connect_ip,$log_content);
		}
	} else {
		echo "<script> alert(\"�α��� ������ �ùٸ��� �ʽ��ϴ�.\\n\\n�ٽ� Ȯ���Ͻñ� �ٶ��ϴ�.\");history.go(".$history."); </script>";
		exit;
	}
	@mysql_free_result($result);
} else {
	$id = $_VenderInfo->getId();
	$vidx = $_VenderInfo->getVidx();
	$authkey = $_VenderInfo->getAuthkey();

	$sql = "SELECT a.vender FROM tblvenderinfo a, tblvendersession b WHERE a.vender='".$vidx."' AND a.id = '".$id."' ";
	$sql.= "AND a.delflag='N' AND a.vender=b.vender AND b.authkey = '".$authkey."' ";
	$result = @mysql_query($sql,get_db_conn());
	$rows = @mysql_num_rows($result);
	if ($rows <= 0) {
		$_VenderInfo->setId("");
		$_VenderInfo->setVidx("");
		$_VenderInfo->setAuthkey("");
		$_VenderInfo->Save();
		echo "<script> alert(\"�������� ��η� �ٽ� �����Ͻñ� �ٶ��ϴ�.\");history.go(".$history."); </script>";
		exit;
	}
	@mysql_free_result($result);
}
?>

<html>
<head>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">

<title>���θ� ���� ������</title>
</head>
<frameset rows="*,0" border=0>
<frame src="main.php" name=bodyframe noresize scrolling=auto marginwidth=0 marginheight=0>
<frame src="blank.php" name=hiddenframe noresize scrolling=no marginwidth=0 marginheight=0>
</frameset>
</body>
</html>