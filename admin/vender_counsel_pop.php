<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

if(strlen($_ShopInfo->getId())==0){
	echo "<script>alert('�������� ��η� �����Ͻñ� �ٶ��ϴ�.');window.close();</script>";
	exit;
}

$date=$_POST["date"];
$mode=$_POST["mode"];
$re_content=$_POST["re_content"];

$sql = "SELECT * FROM tblvenderadminqna WHERE date='".$date."' ";
$result=mysql_query($sql,get_db_conn());
$data=mysql_fetch_object($result);
mysql_free_result($result);
if(!$data) {
	echo "<script>alert(\"�ش� �Խù��� �������� �ʽ��ϴ�.\");window.close();</script>";
	exit;
}
if(strlen($data->re_date)==14) $data->reply="<FONT COLOR=\"blue\"><B>Y</B> (�亯 �ۼ��� �Ϸ�Ǿ����ϴ�.)</fonr>";
else $data->reply="<FONT COLOR=\"red\"><B>Y</B> (�亯 �ۼ��� �ȵǾ����ϴ�.)</font>";

$sql = "SELECT id,com_name FROM tblvenderinfo WHERE vender='".$data->vender."' ";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	$data->id=$row->id;
	$data->name=$row->com_name;
}
mysql_free_result($result);

if($mode=="update" && strlen($re_content)>0) {
	$sql = "UPDATE tblvenderadminqna SET ";
	$sql.= "re_date		= '".date("YmdHis")."', ";
	$sql.= "re_content	= '".$re_content."' ";
	$sql.= "WHERE date='".$date."' ";
	mysql_query($sql,get_db_conn());

	echo "<script>alert(\"�ش� ��㿡 ���� �亯�� �Ϸ�Ǿ����ϴ�.\");opener.location.reload();window.close();</script>";
	exit;
} else if ($mode=="delete") {
	$sql = "DELETE FROM tblvenderadminqna WHERE date='".$date."' ";
	mysql_query($sql,get_db_conn());
	echo "<script>alert(\"�ش� ��� ������ �����Ͽ����ϴ�.\");opener.location.reload();window.close();</script>";
	exit;
}
?>

<html>
<head>
<meta http-equiv='Content-Type' content='text/html;charset=euc-kr'>
<title>������ü ���Խ���</title>
<link rel="stylesheet" href="style.css" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
document.onkeydown = CheckKeyPress;
document.onkeyup = CheckKeyPress;
function CheckKeyPress() {
	ekey = event.keyCode;

	if(ekey == 38 || ekey == 40 || ekey == 112 || ekey ==17 || ekey == 18 || ekey == 25 || ekey == 122 || ekey == 116) {
	   event.keyCode = 0;
	   return false;
	 }
}

function CheckForm(form) {
	if(form.re_content.length==0) {
		alert("�亯 ������ �Է��ϼ���.");
		form.re_content.focus();
		return;
	}
	form.mode.value="update";
	form.submit();
}

function CheckDelete() {
	if(confirm("�ش� �Խñ��� �����Ͻðڽ��ϱ�?")) {
		document.form1.mode.value="delete";
		document.form1.submit();
	}
}
//-->
</SCRIPT>
</head>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 style="overflow-x:hidden;">

<TABLE WIDTH="328" BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
	<TD>
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td><IMG SRC="images/vender_counsel_pop_t.gif" WIDTH="212" HEIGHT="31" ALT=""></td>
		<td width="100%" background="images/member_mailallsend_imgbg.gif">&nbsp;</td>
		<td align=right><img src="images/member_mailallsend_img2.gif" width="20" height="31" border="0"></td>
	</tr>
	</table>
	</TD>
</TR>
<TR>
	<TD style="padding:6pt;">
	<table cellpadding="0" cellspacing="0" width="100%">
	<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
	<input type=hidden name=mode>
	<input type=hidden name=date value="<?=$date?>">
	<tr>
		<td width="100%">
		<TABLE cellSpacing=0 cellPadding=0 width="584" border=0>
		<col width=130></col>
		<col width=></col>
		<TR>
			<TD colspan=2 background="images/table_top_line.gif"></TD>
		</TR>
		<TR>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ü��</TD>
			<TD class="td_con1"><B><span class="font_blue"><?=$data->id?></B> <?=(strlen($data->name)>0?"(".$data->name.")":"")?></span></TD>
		</TR>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<TR>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����</TD>
			<TD class="td_con1"><?=$data->subject?></TD>
		</TR>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����</TD>
			<TD class="td_con1"><?=nl2br($data->content)?></TD>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�亯</TD>
			<TD class="td_con1"><TEXTAREA style="width:400;height:205" name=re_content class="textarea"><?=$data->re_content?></TEXTAREA></TD>
		</tr>
		<TR>
			<TD colspan=2 background="images/table_top_line.gif"></TD>
		</TR>
		</TABLE>
		</td>
	</tr>
	<tr>
		<td width="100%" align="center">
		<a href="javascript:CheckForm(document.form1);"><img src="images/btn_write1.gif" width="69" height="24" border="0" vspace="10" border=0></a>
		<a href="javascript:CheckDelete();"><img src="images/btn_dela.gif" width="69" height="24" border="0" vspace="10" border=0 hspace="2"></a>
		<a href="javascript:window.close()"><img src="images/btn_closea.gif" width="69" height="24" border="0" vspace="10" border=0 hspace="0"></a>
		</td>
	</tr>
	</form>
	</table>
	</TD>
</TR>
</TABLE>
</body>
</html>