<?
if(getenv("HTTPS")!="on") {
	header("HTTP/1.0 404 Not Found");
	exit;
}

#���θ� ������ �α���
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$shopurl=$_POST["shopurl"];
$mem_id=$_POST["mem_id"];
$mem_pw=$_POST["mem_pw"];

$flag	= false;
$disabled = 0;
$currenttime = time();

if(!eregi($shopurl,getenv("HTTP_REFERER"))) {
	exit;
}

if(strlen($shopurl)==0 || strlen($mem_id)==0 || strlen($mem_pw)==0) {
	echo "<html><head><title></title></head><body onload=\"alert('�α��� ������ �ùٸ��� �ʽ��ϴ�.');history.go(-1)\"></body></html>";exit;
}

$sql = "SELECT id, passwd, expirydate, disabled FROM tblsecurityadmin ";
$sql.= "WHERE id='".$mem_id."' AND passwd=md5('".$mem_pw."')";
$result = @mysql_query($sql,get_db_conn());
if($row=@mysql_fetch_object($result)) {
	$passwd = $row->passwd;
	$expirydate = (int)$row->expirydate;
	$disabled = (int)$row->disabled;

	if ($expirydate == 0) {
		$flag = true;
	} else {
		if ($expirydate > time())
			$flag = true;
		else
			$flag = false;
	}

	if ($disabled == 1) {
		$flag = false;
	}

	if ($flag) {
		$flag = false;
		if (md5($mem_pw) == $passwd) $flag = true;
	}

	if ($flag) {
		$authkey = md5(uniqid(""));
		$sql = "UPDATE tblsecurityadmin SET authkey='".$authkey."' WHERE id='".$mem_id."' ";
		@mysql_query($sql,get_db_conn());

		echo "<html><head><title></title></head><body>\n";
		echo "<form name=form1 method=post action=\"http://".$shopurl."/".RootPath.AdminDir."loginproc.php\">\n";
		echo "<input type=hidden name=ssltype value=\"ssl\">\n";
		echo "<input type=hidden name=mem_id value=\"".$mem_id."\">\n";
		echo "<input type=hidden name=sessid value=\"".$authkey."\">\n";
		echo "</form>\n";
		echo "<script>document.form1.submit();</script>\n";
		echo "</body></html>\n";
		exit;
	} else {
		echo "<script> alert(\"�α��� ������ �ùٸ��� �ʽ��ϴ�.\\n\\n�ٽ� Ȯ���Ͻñ� �ٶ��ϴ�.\");history.go(-1); </script>";
		exit;
	}
} else {
	echo "<html><head><title></title></head><body onload=\"alert('��й�ȣ�� Ʋ���ϴ�.');history.go(-1)\"></body></html>";exit;
}
@mysql_free_result($result);
?>