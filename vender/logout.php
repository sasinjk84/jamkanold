<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");

$isaccesspass=true;
include("access.php");

$vidx=$_VenderInfo->getVidx();
$id=$_VenderInfo->getId();
$authkey=$_VenderInfo->getAuthkey();

$_VenderInfo->setVidx("");
$_VenderInfo->setId("");
$_VenderInfo->setAuthkey("");
$_VenderInfo->Save();

$connect_ip = getenv("REMOTE_ADDR");
if(strlen($vidx)>0 && strlen($id)>0) {
	#mysql_query("UPDATE tblvenderinfo SET authkey='' WHERE vender='".$vidx."'",get_db_conn());
	mysql_query("DELETE FROM tblvendersession WHERE vender='".$vidx."' AND authkey='".$authkey."'",get_db_conn());

	$log_content = "�α׾ƿ� : $id";
	$_VenderInfo->ShopVenderLog($vidx,$connect_ip,$log_content);
}

?>
<body onload="alert('�α׾ƿ��Ǿ����ϴ�.');top.location.href='<?=$Dir.VenderDir?>'">
</body>