<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/admin_more.php");

if(strlen($_ShopInfo->getId())==0){
	echo "<script>alert('�������� ��η� �����Ͻñ� �ٶ��ϴ�.');window.close();</script>";
	exit;
}

$vender=$_REQUEST["vender"];
$p_type = $_REQUEST["type"];
if(strlen($vender)==0) {
	echo "<html><head></head><body onload=\"alert('�ش� ������ü�� �������� �ʽ��ϴ�.');window.close();\"></body></html>";exit;
}

$sql = "SELECT a.*, b.brand_name,b.brand_description,c.prdt_allcnt,c.prdt_cnt,c.cust_cnt,c.count_total ";
$sql.= "FROM tblvenderinfo a, tblvenderstore b, tblvenderstorecount c ";
$sql.= "WHERE a.vender='".$vender."' AND a.delflag='N' AND a.vender=b.vender AND b.vender=c.vender ";
$result=mysql_query($sql,get_db_conn());
$row=mysql_fetch_array($result);
?>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html;charset=euc-kr'>
<title>��ü ������ ���� ����</title>
<link rel="stylesheet" href="style.css" type="text/css">
</head>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 oncontextmenu="return false" style="overflow-x:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false" onLoad="PageResize();">

<table border=0 cellpadding=0 cellspacing=0 width=100% id=table_body>
<tr>
	<td width=100% align=center>
	<table border=0 cellpadding=0 cellspacing=0 width=100%>
	<TR>
		<TD height="31" background="images/member_mailallsend_imgbg.gif">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="28"><p></td>
					<td><p><b><font color="white"><?=$row[com_name]?>(<?=$row[id]?>) ������ ������ ���� ����</b></font></td>
				</tr>
			</table>
		</TD>
	</TR>
	<tr><td style="padding-top:4pt; padding-bottom:2pt; text-align:right;"><font color="black"><span style="font-size:11px;">* ������ <span style="color:#ff3300; font-weight:bold; letter-spacing:-1px;">������ ����</span> ������ Ȯ���Ͻ� �� �ֽ��ϴ�.</span></font></td></tr>
	<tr>
		<td align=center>
		<?
			getVenderCommissionHistory($vender, 1, $p_type);
		?>
		</td>
	</tr>
	<tr><td height=10></td></tr>
	<? if ($p_type !="if") { ?>
	<tr>
		<td align=center><input type="image" src="images/btn_close.gif" width="36" height="18" border="0" vspace="0" hspace="2" onclick="window.close();">
		</td>
	</tr>
	<tr><td height=10></td></tr>
	<? } ?>
	</table>

	</td>
</tr>
</table>

</body>
</html>