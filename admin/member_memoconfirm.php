<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

if(strlen($_ShopInfo->getId())==0){
	echo "<script>alert('�������� ��η� �����Ͻñ� �ٶ��ϴ�.');window.close();</script>";
	exit;
}

?>

<html>
<head>
<meta http-equiv='Content-Type' content='text/html;charset=euc-kr'>
<title>��� ȸ���޸�</title>
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

function PageResize() {
	var oWidth = document.all.table_body.clientWidth + 10;
	var oHeight = document.all.table_body.clientHeight + 80;

	window.resizeTo(oWidth,oHeight);

	document.form1.id.focus();
}

function CheckForm() {
	if(document.form1.id.value.length==0) {
		alert("ȸ�� ���̵� �Է��ϼ���.");
		document.form1.id.focus();
		return;
	}
	document.form1.submit();
}
//-->
</SCRIPT>
</head>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 oncontextmenu="return false" style="overflow-x:hidden;overflow-y:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false" onLoad="PageResize();">
<TABLE WIDTH="250" BORDER=0 CELLPADDING=0 CELLSPACING=0 style="table-layout:fixed;" id=table_body>
<TR>
	<TD width="100%" height="31" background="images/win_titlebg1.gif">
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td><img src="images/member_memo_wintitle.gif" border="0"></td>
		<td width="100%" background="images/member_memo_imgbg.gif" border="0"></td>
	</tr>
	</table>
	</TD>
</TR>
<TR>
	<TD style="padding:5pt;">
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
	<TR>
		<TD colspan=2 background="images/table_top_line.gif"></TD>
	</TR>
	<form name=form1 method=post action="member_memopop.php">
	<TR>
		<TD class="table_cell" width="49"><img src="images/icon_point2.gif" width="8" height="11" border="0">ȸ��ID</TD>
		<TD class="td_con1" width="159"><input type=text name=id size=15 class="input" style="width:100%"></TD>
	</TR>
	<TR>
		<TD colspan=2 background="images/table_top_line.gif"></TD>
	</TR>
	</TABLE>
	</TD>
</TR>
<TR>
	<TD align=center>�޸� ����� ȸ��ID�� �Է��ϼ���!</TD>
</TR>
<TR>
	<TD align=center><a href="javascript:CheckForm();"><img src="images/btn_ok3.gif" width="36" height="18" border="0" vspace="2" border=0></a>&nbsp;<a href="javascript:window.close();"><img src="images/btn_close.gif" width="36" height="18" border="0" vspace="2" border=0 hspace="2"></a></TD>
</TR>
</form>
</TABLE>
</body>
</html>