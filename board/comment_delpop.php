<?
if(substr(getenv("SCRIPT_NAME"),-19)=="/comment_delpop.php"){
	header("HTTP/1.0 404 Not Found");
	exit;
}

include ("head.php");

$num=$_POST["num"];
$c_num=$_POST["c_num"];
$mode=$_POST["mode"];
$up_passwd=$_POST["up_passwd"];


if ($setup[use_comment] != "Y") {
	$errmsg="�ش� �Խ����� ��� ����� �������� �ʽ��ϴ�.";
	echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');window.close();\"></body></html>";exit;
}

if ($member[grant_comment]!="Y") {
	$errmsg="�̿������ �����ϴ�.";
	echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');window.close();\"></body></html>";exit;
}

$qry  = "SELECT * FROM tblboardcomment WHERE board='".$board."' AND parent='".$num."' AND num='".$c_num."' ";
$result1 = mysql_query($qry,get_db_conn());
$ok_result = mysql_num_rows($result1);

if ((!$ok_result) || ($ok_result == -1)) {
	$errmsg="������ ����� �����ϴ�.\\n\\n�ٽ� Ȯ���Ͻñ� �ٶ��ϴ�.";
	echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');window.close();\"></body></html>";exit;
} else {
	$row1 = mysql_fetch_array($result1);
}

if ($_POST["mode"] == "delete") {
	if($member[admin]!="SU") {
		if (strlen($_POST["up_passwd"])==0 AND $boardUserid != $member[id]) {
			$errmsg="�߸��� ��η� �����ϼ̽��ϴ�.";
			echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');window.close();\"></body></html>";exit;
		}

		if (($row1[passwd]!=$_POST["up_passwd"]) && ($setup[passwd]!=$_POST["up_passwd"])) {
			$errmsg="��й�ȣ�� ��ġ���� �ʽ��ϴ�.\\n\\n�ٽ� Ȯ�� �Ͻʽÿ�.";
			echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');window.close()\"></body></html>";exit;
		}
	}
	$del_sql = "DELETE FROM tblboardcomment WHERE board='".$board."' AND parent='".$num."' AND num = '".$_POST["c_num"]."'";
	$delete = mysql_query($del_sql,get_db_conn());

	if ($delete) {
		@mysql_query("UPDATE tblboard SET total_comment = total_comment - 1 WHERE board='".$board."' AND num='".$num."'",get_db_conn());
	}
	echo "
	<script>
		try {
			opener.location.reload();
		} catch (e) {}
		window.close();
	</script>
	";
	exit;

}

?>

<html>
<head>
<meta http-equiv=Content-type content=text/html; charset=euc-kr>

<title>��й�ȣ Ȯ��</title>
<style>
td	{font-family:"����,����";color:#4B4B4B;font-size:12px;line-height:17px;}
BODY,DIV,form,TEXTAREA,center,option,pre,blockquote {font-family:Tahoma;color:000000;font-size:9pt;}

A:link    {color:#635C5A;text-decoration:none;}
A:visited {color:#545454;text-decoration:none;}
A:active  {color:#5A595A;text-decoration:none;}
A:hover  {color:#545454;text-decoration:underline;}
.input{font-size:12px;BORDER-RIGHT: #DCDCDC 1px solid; BORDER-TOP: #C7C1C1 1px solid; BORDER-LEFT: #C7C1C1 1px solid; BORDER-BOTTOM: #DCDCDC 1px solid; HEIGHT: 18px; BACKGROUND-COLOR: #ffffff;padding-top:2pt; padding-bottom:1pt; height:19px}
.select{color:#444444;font-size:12px;}
.textarea {border:solid 1;border-color:#e3e3e3;font-family:����;font-size:9pt;color:333333;overflow:auto; background-color:transparent}
</style>
<Script language="javascript">
function PageResize() {
	var oWidth = document.all.table_body.clientWidth + 10;
	var oHeight = document.all.table_body.clientHeight + 80;

	window.resizeTo(oWidth,oHeight);
}

function delconfirm() {
	try {
		var pass = document.pwForm.up_passwd.value;

		if (pass == "") {
			alert("��й�ȣ�� �Է����ּ���.");
			document.pwForm.up_passwd.focus();
			return;
		}
	} catch (e) {}

	if (!confirm("������ ���� �Ͻðڽ��ϱ�?")) {
		return;
	}
	document.pwForm.submit();
}

</script>
</head>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 oncontextmenu="return false" style="overflow-x:hidden;overflow-y:hidden;" oncontextmenu="return false" onLoad="PageResize();">

<table width="330"  border="0" cellpadding="0" cellspacing="0" id=table_body>
<form method=post action="<?=$PHP_SELF?>" name=pwForm>
<input type=hidden name=pagetype value="comment_delpop">
<input type=hidden name=board value=<?=$board?>>
<input type=hidden name=num value=<?=$num?>>
<input type=hidden name=c_num value=<?=$c_num?>>
<input type=hidden name=mode value="delete">
<tr>
	<td><IMG SRC="<?=$Dir.BoardDir?>images/message_title.gif" border="0"></td>
	<td width="100%" background="<?=$Dir.BoardDir?>images/message_title1bg.gif"><IMG SRC="<?=$Dir.BoardDir?>images/message_title1.gif" border="0"></td>
	<td><IMG SRC="<?=$Dir.BoardDir?>images/message_title2.gif" border="0"></td>
</tr>
<tr>
<td background="<?=$Dir.BoardDir?>images/message_bg.gif"></td>
<td width="100%">
	<table width="100%"  border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td height="1" bgcolor="#cfcfcf"></td>
	</tr>
	<tr>
		<td align="center" bgcolor="#F6F6F6" style="padding:5px;padding-top:10px;padding-bottom:10px;">��� �Է½� ����� ��й�ȣ�� �Է��ϼ���.</td>
	</tr>
	<tr>
		<td height="1" bgcolor="#cfcfcf"></td>
	</tr>
	<tr>
	<td align="center" height="40">
	<?if($member[admin]=="SU" OR $boardUserid == $member[id] ) {?>
		���� �����Ͻðڽ��ϱ�?
	<?}else{?>
		��й�ȣ <input type="password" name="up_passwd" style="width:160px" class="input">
	<?}?>
	</td>
	</tr>
	</table>
</td>
<td background="<?=$Dir.BoardDir?>images/message_bg1.gif"></td>
</tr>
<tr>
	<td><IMG SRC="<?=$Dir.BoardDir?>images/message_down.gif" border="0"></td>
	<td background="<?=$Dir.BoardDir?>images/message_down1.gif"></td>
	<td><IMG SRC="<?=$Dir.BoardDir?>images/message_down2.gif" border="0"></td>
</tr>
<tr>
	<td colspan="3" align="center" style="padding-top:5px;"><img src="<?=$Dir.BoardDir?>images/board_btn01.gif" border="0" style="CURSOR:hand" onclick="delconfirm()"><img src="<?=$Dir.BoardDir?>images/board_btn02.gif" border="0" hspace="5" style="CURSOR:hand" onClick="history.go(-1);"></td>
</tr>
</form>
</table>

</body>
</html>
