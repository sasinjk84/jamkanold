<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "vd-1";
$MenuCode = "vender";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$type=$_GET["type"];
$seq=$_GET["idx"];
$productcode=$_GET["productcode"];

if (!$type) {
	?>
	<script type="text/javascript">
		<!--
		alert("�ڷᰡ �ùٸ��� �ʽ��ϴ�.");
	   / -->
	</script>
	<?
	exit();
}

if ($type=="all") {
	
	if (!$productcode) {
		?>
		<script type="text/javascript">
			<!--
			alert("�ڷᰡ �ùٸ��� �ʽ��ϴ�.");
		   / -->
		</script>
		<?
		exit();
	}

	$sql = "delete from commission_history where productcode='".$productcode."'";
	mysql_query($sql,get_db_conn());

}else if($type=="one"){

	if (!$seq) {
		?>
		<script type="text/javascript">
			<!--
			alert("�ڷᰡ �ùٸ��� �ʽ��ϴ�.");
		   / -->
		</script>
		<?
		exit();
	}
	
	$sql = "delete from commission_history where seq='".$seq."'";
	mysql_query($sql,get_db_conn());

}

?>

<script type="text/javascript">
<!--
	alert("�����Ǿ����ϴ�.");
	parent.location.reload();
// -->
</script>