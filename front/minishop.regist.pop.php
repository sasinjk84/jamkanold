<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");

$sellvidx=$_POST["sellvidx"];
$mode=$_POST["mode"];
$email_yn=$_POST["email_yn"];

if(strlen($_ShopInfo->getMemid())==0 || strlen($sellvidx)==0) {
	echo "<html></head><body onload=\"window.close()\"></body></html>";exit;
}

$sql = "SELECT brand_name FROM tblvenderstore WHERE vender='".$sellvidx."' ";
$result=mysql_query($sql,get_db_conn());
if(!$row=mysql_fetch_object($result)) {
	echo "<html></head><body onload=\"window.close()\"></body></html>";exit;
}
$brand_name=$row->brand_name;
mysql_free_result($result);

$sql = "SELECT * FROM tblregiststore WHERE id='".$_ShopInfo->getMemid()."' AND vender='".$sellvidx."' ";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	$isregist=true;
	if($mode=="update" && preg_match("/^(Y|N)$/",$email_yn)) {
		if($email_yn=="N") {
			$sql = "UPDATE tblregiststore SET email_yn='N' ";
			$sql.= "WHERE id='".$_ShopInfo->getMemid()."' AND vender='".$sellvidx."' ";
			mysql_query($sql,get_db_conn());
		}
	} else {
		$mode="";
	}
} else {
	$isregist=false;
	$sql = "INSERT tblregiststore SET ";
	$sql.= "id			= '".$_ShopInfo->getMemid()."', ";
	$sql.= "vender		= '".$sellvidx."', ";
	$sql.= "email_yn	= 'Y' ";
	if(mysql_query($sql,get_db_conn())) {
		$sql = "UPDATE tblvenderstorecount SET cust_cnt=cust_cnt+1 ";
		$sql.= "WHERE vender='".$sellvidx."' ";
		mysql_query($sql,get_db_conn());
	}
}
mysql_free_result($result);

?>

<html>
<head>
<title>�̴ϼ� ���Ѽ��θ� ���</title>
<meta http-equiv="CONTENT-TYPE" content="text/html;charset=EUC-KR">

<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<style>
td {font-family:Tahoma;color:666666;font-size:9pt;}

tr {font-family:Tahoma;color:666666;font-size:9pt;}
BODY,TD,SELECT,DIV,form,TEXTAREA,center,option,pre,blockquote {font-family:Tahoma;color:000000;font-size:9pt;}

A:link    {color:333333;text-decoration:none;}

A:visited {color:333333;text-decoration:none;}

A:active  {color:333333;text-decoration:none;}

A:hover  {color:#CC0000;text-decoration:none;}
</style>
<SCRIPT LANGUAGE="JavaScript">
<!--
function PageResize() {
	var oWidth = document.all.table_body.clientWidth + 10;
	var oHeight = document.all.table_body.clientHeight + 55;

	window.resizeTo(oWidth,oHeight);
}
<?if($isregist==false){?>
function fnConfirm() {
	email_yn="Y";
	if(document.all["cusMemo"][1].checked==true) email_yn="N";
	document.form1.email_yn.value=email_yn;
	document.form1.mode.value="update";
	document.form1.submit();
}
<?}?>
//-->
</SCRIPT>
</head>

<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 oncontextmenu="return false" style="overflow-x:hidden;overflow-y:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false" onLoad="PageResize();">
<table border=0 cellpadding=0 cellspacing=0 width=400 style="table-layout:fixed;" id=table_body>
<tr>
	<td align=center>
	<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
	<tr><td height=5></td></tr>
	<tr>
		<td style="padding:15">
		<B>�̴ϼ� ���� ���θ�</B>
		</td>
	</tr>
	<tr><td height=2 bgcolor=red></td></tr>
	<tr><td height=25></td></tr>
	<tr>
		<td align=center>

		<?if($isregist==true){?>
			<?if($mode=="update"){?>

			<div>
			<strong><span style="color:red">( <?=$brand_name?> )</span> �̴ϼ��� ���� ���θ��� ��ϵǾ����ϴ�.</strong>
			<p style="line-height:14pt;color:#585858">
				<?if($email_yn=="Y"){?>
				�Ǹ��ڰ� ������ ���� ���ſ� �����ϼ̽��ϴ�.<br />
				���Բ� ������ ������ ���� �� �� �ֵ���<br />�ּ��� ����� ���ϰڽ��ϴ�. �����մϴ�.
				<?}else{?>
				�Ǹ��ڰ� ������ ���� ������ �ź� �Ͽ����ϴ�.<br />
				���� ���θ����� ������ ���� ������ ���Ͻ� ���<br />������������ > ���� ���θ������� ���� �Ͻ� �� �ֽ��ϴ�.
				<?}?>
			</p>
			</div>

			<?}else{?>

			<div>
			<strong><span style="color:red">( <?=$brand_name?> )</span> �̴ϼ��� �̹� ���� ���θ��� ��� �Ǿ����ϴ�.</strong>
			</div>

			<?}?>

			<p>
				<a href="javascript:window.close();"><img src="<?=$Dir?>images/minishop/btnConfirm.gif" border=0></a>
			</p>

		<?}else{?>

		<form name=form1 method=post action="<?=$_SERVER[PHP_SELF]?>">
		<input type=hidden name=sellvidx value="<?=$sellvidx?>">
		<input type=hidden name=mode>
		<input type=hidden name=email_yn>
		</form>

		<div>
		<strong><span style="color:red">( <?=$brand_name?> )</span> �̴ϼ��� ���� ���θ��� ��� �մϴ�.</strong>
		<p style="line-height:14pt;color:#585858">
			���� ���θ��� ��� �Ͻø� �Ǹ����� �̺�Ʈ�� <br />
			��ǰ ������ ���� ������ ������ �� �ֽ��ϴ�.<br />�̴ϼ� �Ǹ��ڰ� ������ ������ ���� �Ͻðڽ��ϱ�?
		</p>
		</div>

		<div>
			<input type="radio" name="cusMemo" value="Y" checked> <label for="">������</label>
			<input type="radio" name="cusMemo" value="N"> <label for="">�������� ����</label>
		</div>
		<p>
			<a href="javascript:fnConfirm();"><img src="<?=$Dir?>images/minishop/btnConfirm.gif" border=0></a>
		</p>

		<?}?>

		</td>
	</tr>
	<tr><td height=50></td></tr>
	<tr><td height=2 bgcolor=red></td></tr>
	<tr><td height=10></td></tr>
	<tr>
		<td align=right style="padding-right:20">
		<img src="<?=$Dir?>images/minishop/btsClose.gif" alt="â�ݱ�" style="cursor:hand" onclick="window.close();">
		</td>
	</tr>
	<tr><td height=20></td></tr>
	</table>
	</td>
</tr>
</table>
</body>
</html>