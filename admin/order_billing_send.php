<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/cfg.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "or-1";
$MenuCode = "order";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

########################### TEST ���θ� Ȯ�� ##########################
DemoShopCheck("������������� �׽�Ʈ�� �Ұ��� �մϴ�.", "main.php");
#######################################################################

$sql = "SELECT * FROM tblshopbillinfo where bill_state ='Y' ";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	$domain = $row->domain;
	$license_id = $row->license_id;
	$license_no = $row->license_no;
	$bill_state = $row->bill_state;
}
mysql_free_result($result);
if(strlen($domain)==0 || strlen($license_id)==0 || strlen($license_no)==0) {
	echo "<html></head><body onload=\"alert('���ڼ��ݰ�꼭 �������� ����� ID �� ����Ű�� �Է��Ͻñ� �ٶ��ϴ�.');location.href='shop_billinfo.php';\"></body></html>";exit;
}

$SBinfo = new Shop_Billinfo();
$document_id=$_POST["document_id"];
if($document_id){
	$link = "location.href='http://group.hiworks.co.kr/".$SBinfo->domain."/bill/manage/viewdoc/".$document_id."'";
}else{
	$link = "location.href='http://group.hiworks.co.kr/".$SBinfo->domain."/bill/manage/";
}
?>
<script>
<?=$link?>
</script>
<!-- http://office.hiworks.co.kr/getmall/bill/customer/customer_search -->