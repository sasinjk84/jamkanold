<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/admin_more.php");

if(strlen($_ShopInfo->getId())==0){
	echo "<script>alert('�������� ��η� �����Ͻñ� �ٶ��ϴ�.');window.close();</script>";
	exit;
}

$productcode=$_REQUEST["productcode"];
if(strlen($productcode)==0) {
	echo "<html><head></head><body onload=\"alert('�ش� ��ǰ�� �������� �ʽ��ϴ�.');window.close();\"></body></html>";exit;
}

$sql = "SELECT productname FROM tblproduct WHERE productcode='".$productcode."'";
$result=mysql_query($sql,get_db_conn());
$row=mysql_fetch_array($result);
?>

<html>
	<head>
	<meta http-equiv='Content-Type' content='text/html;charset=euc-kr'>
	<title>��ǰ ������ ���� ����</title>
	<link rel="stylesheet" href="style.css" type="text/css">
	</head>
	<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 oncontextmenu="return false" style="overflow-x:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false" onLoad="PageResize();">

	<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed;" id=table_body>
		<tr>
			<td align=center>
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
					<TR>
						<TD height="31" background="images/member_mailallsend_imgbg.gif">
							<table cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td width="28"><p></td>
									<td><p><b><font color="white">������ ��ǰ ������ ���� ����</b></font></td>
								</tr>
							</table>
						</TD>
					</TR>
					<tr><td style="padding-top:4pt; padding-bottom:2pt; text-align:right;"><font color="black"><span style="font-size:11px;">* ������ <span style="color:#ff3300; font-weight:bold; letter-spacing:-1px;">��ǰ ������ ����</span> ������ Ȯ���Ͻ� �� �ֽ��ϴ�.</span></font></td></tr>
					<tr><td height=20></td></tr>
					<tr><td style="padding-left:8px; padding-bottom:2px;"><b>�� ��ǰ��</b> : <?=$row[productname]?></td></tr>
					<tr>
						<td align=center>
						<?
							getProductCommissionHistory($productcode, "1");
						?>
						</td>
					</tr>
					<tr><td height=10></td></tr>
					<tr>
						<td align=center><input type="image" src="images/btn_close.gif" width="36" height="18" border="0" vspace="0" hspace="2" onclick="window.close();">
						</td>
					</tr>
					<tr><td height=10></td></tr>
				</table>
			</td>
		</tr>
	</table>

	</body>
</html>