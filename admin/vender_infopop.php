<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

if(strlen($_ShopInfo->getId())==0){
	echo "<script>window.close();</script>";
	exit;
}

$vender=$_POST["vender"];
if(strlen($vender)==0) {
	echo "<html><head></head><body onload=\"alert('�ش� ������ü�� �������� �ʽ��ϴ�.');window.close();\"></body></html>";exit;
}
$sql = "SELECT * FROM tblvenderinfo WHERE vender='".$vender."' AND delflag='N' ";
$result=mysql_query($sql,get_db_conn());
if(!$row=mysql_fetch_object($result)) {
	echo "<html><head></head><body onload=\"alert('�ش� ������ü�� �������� �ʽ��ϴ�.');window.close();\"></body></html>";exit;
}
mysql_free_result($result);
$_vdata=$row;
?>

<html>
<head>
<meta http-equiv='Content-Type' content='text/html;charset=euc-kr'>
<title>������ü ����</title>
<link rel="stylesheet" href="style.css" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
function PageResize() {
	var oWidth = document.all.table_body.clientWidth + 10;
	var oHeight = document.all.table_body.clientHeight + 75;

	window.resizeTo(oWidth,oHeight);
}
//-->
</SCRIPT>

</head>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 style="overflow-x:hidden;overflow-y:hidden;" onLoad="PageResize();">

<table border=0 cellpadding=0 cellspacing=0 width=450 style="table-layout:fixed;" id=table_body>
<tr>
	<td width=100%>
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td><img src="images/vender_info_pop_t.gif" border="0" width="212" height="31"></td>
		<td width="100%" background="images/member_find_titlebg.gif">&nbsp;</td>
		<td align=right><img src="images/member_find_titleimg.gif" width="20" height="31" border="0"></td>
	</tr>
	</table>
	<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
	<tr><td height=10></td></tr>
	<tr>
		<td align=center>
		<table border=0 cellpadding=0 cellspacing=0 width=95% style="table-layout:fixed">
		<col width=120></col>
		<col width=></col>
		<TR>
			<TD colspan=2 background="images/table_top_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���̵�</td>
			<td class="td_con1"><img width="0"><?=$_vdata->id?></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">ȸ���</td>
			<td class="td_con1"><img width="0"><?=$_vdata->com_name?></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">��ǥ�� ����</td>
			<td class="td_con1"><img width="0"><?=$_vdata->com_owner?></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ǥ ��ȭ/�ѽ�</td>
			<td class="td_con1"><img width="0"><?=$_vdata->com_tel?> / <?=$_vdata->com_fax?></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">ȸ�� �ּ�</td>
			<td class="td_con1"><img width="0">[<?=$_vdata->com_post?>] <?=$_vdata->com_addr?></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����� ����</td>
			<td class="td_con1"><img width="0"><?=$_vdata->p_name?></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����� �ڵ���</td>
			<td class="td_con1"><img width="0"><?=$_vdata->p_mobile?></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����� �̸���</td>
			<td class="td_con1"><img width="0"><?=$_vdata->p_email?></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">����� �μ���</td>
			<td class="td_con1"><img width="0"><?=$_vdata->p_buseo?></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">����� ����</td>
			<td class="td_con1"><img width="0"><?=$_vdata->p_level?></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<TR>
			<TD colspan=2 background="images/table_top_line.gif"></TD>
		</TR>
		</table>
		</td>
	</tr>
	<tr><td height=10></td></tr>
	<tr>
		<td align=center><input type="image" src="images/btn_close.gif" width="36" height="18" border="0" vspace="0" hspace="2" onclick="window.close();"></td>
	</tr>
	<tr><td height=10></td></tr>
	</table>
	</td>
</tr>
</table>

</body>
</html>