<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

$board=$_POST["board"];

if(strlen($_ShopInfo->getId())==0 || strlen($board)==0){
	echo "<script>alert('�������� ��η� �����Ͻñ� �ٶ��ϴ�.');window.close();</script>";
	exit;
}

$sql = "SELECT * FROM tblboardadmin WHERE board='".$board."' ";
$result=mysql_query($sql,get_db_conn());
$data=mysql_fetch_object($result);
mysql_free_result($result);

if(!$data) {
	echo "<script>alert(\"�ش� �Խ����� �������� �ʽ��ϴ�.\");window.close();</script>";
	exit;
}
if($data->comment_width==0) {
	$data->comment_width=500;
}

$mode=$_POST["mode"];
$use_hidden=$_POST["use_hidden"];
$use_hide_ip=(strlen($_POST["use_hide_ip"])==0?"N":$_POST["use_hide_ip"]);
$use_hide_email=(strlen($_POST["use_hide_email"])==0?"N":$_POST["use_hide_email"]);
$use_html=$_POST["use_html"];
$use_comip=$_POST["use_comip"];
$admin_name=$_POST["admin_name"];
$newimg=(int)$_POST["newimg"];
$datedisplay=$_POST["datedisplay"];
$hitdisplay=$_POST["hitdisplay"];
$use_wrap=$_POST["use_wrap"];
$use_article_care=$_POST["use_article_care"];
$use_hide_button=$_POST["use_hide_button"];
$comment_width=(int)$_POST["comment_width"];
$hitplus=$_POST["hitplus"];
$use_admin_mail=$_POST["use_admin_mail"];
$admin_mail=$_POST["admin_mail"];
$filter=$_POST["filter"];
$avoid_ip=$_POST["avoid_ip"];

//20120309 �Խ��� ����߰�
$reply_sms=$_POST["reply_sms"];
$use_admin_sms=$_POST["use_admin_sms"];
$admin_sms=$_POST["admin_sms"];
$sns_state=$_POST["sns_state"];

if($mode=="modify" && strlen($board)>0) {
	$sql = "UPDATE tblboardadmin SET ";
	$sql.= "use_hidden			= '".$use_hidden."', ";
	$sql.= "use_hide_ip			= '".$use_hide_ip."', ";
	$sql.= "use_hide_email		= '".$use_hide_email."', ";
	$sql.= "use_html			= '".$use_html."', ";
	$sql.= "use_comip			= '".$use_comip."', ";
	$sql.= "admin_name			= '".$admin_name."', ";
	$sql.= "newimg				= '".$newimg."', ";
	$sql.= "datedisplay			= '".$datedisplay."', ";
	$sql.= "hitdisplay			= '".$hitdisplay."', ";
	$sql.= "use_wrap			= '".$use_wrap."', ";
	$sql.= "use_article_care	= '".$use_article_care."', ";
	$sql.= "use_hide_button		= '".$use_hide_button."', ";
	$sql.= "comment_width		= '".$comment_width."', ";
	$sql.= "hitplus				= '".$hitplus."', ";
	$sql.= "use_admin_mail		= '".$use_admin_mail."', ";
	$sql.= "admin_mail			= '".$admin_mail."', ";
	$sql.= "filter				= '".$filter."', ";
	$sql.= "reply_sms			= '".$reply_sms."', ";
	$sql.= "use_admin_sms		= '".$use_admin_sms."', ";
	$sql.= "admin_sms			= '".$admin_sms."', ";
	$sql.= "sns_state			= '".$sns_state."', ";
	$sql.= "filter				= '".$filter."', ";
	$sql.= "avoid_ip			= '".$avoid_ip."' ";
	$sql.= "WHERE board='".$board."' ";
	$update=mysql_query($sql,get_db_conn());
	if($update) {
		echo "<script>alert(\"�Խ��� Ư����� ������ �Ϸ�Ǿ����ϴ�.\");opener.location.reload();window.close();</script>";
		exit;
	} else {
		$onload="<script>alert(\"�Խ��� Ư����� ������ ������ �߻��Ͽ����ϴ�.\");</script>";
		$data->use_hidden=$use_hidden; $data->use_hide_ip=$use_hide_ip; $data->use_hide_email=$use_hide_email;
		$data->use_html=$use_html; $data->use_comip=$use_comip; $data->admin_name=$admin_name;
		$data->newimg=$newimg; $data->datedisplay=$datedisplay; $data->hitdisplay=$hitdisplay;
		$data->use_wrap=$use_wrap; $data->use_article_care=$use_article_care; $data->use_hide_button=$use_hide_button;
		$data->comment_width=$comment_width; $data->hitplus=$hitplus; $data->use_admin_mail=$use_admin_mail;
		$data->admin_mail=$admin_mail; $data->filter=$filter;
		$data->avoid_ip=$avoid_ip;$data->reply_sms=$reply_sms;$data->use_admin_sms=$use_admin_sms;
		$data->admin_sms=$admin_sms;$data->sns_state=$sns_state;
	}
}
?>

<html>
<head>
<meta http-equiv='Content-Type' content='text/html;charset=euc-kr'>
<title>�Խ��� Ư����� ����</title>
<link rel="stylesheet" href="style.css" type="text/css">
<style>td {line-height:14pt}</style>
<script type="text/javascript" src="lib.js.php"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--
document.onkeydown = CheckKeyPress;
document.onkeyup = CheckKeyPress;
function CheckKeyPress() {
	ekey = event.keyCode;
	if(ekey==38 || ekey==40 || ekey==112 || ekey==17 || ekey==18 || ekey==25 || ekey==122 || ekey==116) {
		try {
			event.keyCode = 0;
			return false;
		} catch(e) {}
	}
}

function PageResize() {
	var oWidth = 630;
	var oHeight = 600;

	window.resizeTo(oWidth,oHeight);
}

function CheckForm(form) {
	form.mode.value="modify";
	form.submit();
}
//-->
</SCRIPT>
</head>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 oncontextmenu="return false" style="overflow-x:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false" onLoad="PageResize();">

<TABLE WIDTH="630" BORDER=0 CELLPADDING=0 CELLSPACING=0 style="table-layout:fixed;" id=table_body>
<TR>
	<TD>
	<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
		<TD><IMG SRC="images/community_list_sort_fun2.gif" WIDTH=143 HEIGHT=31 ALT=""></TD>
		<TD width="100%" background="images/community_list_sortbg.gif"></TD>
		<TD><IMG SRC="images/community_list_sortimg.gif" WIDTH=12 HEIGHT=31 ALT=""></TD>
	</TR>
	</TABLE>
	</TD>
</TR>
<TR>
	<TD style="padding:6pt;">
	<table cellpadding="0" cellspacing="0" width="100%">
	<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
	<input type=hidden name=mode>
	<input type=hidden name=board value="<?=$board?>">
	<tr>
		<td width="100%">
		<TABLE cellSpacing=0 cellPadding=0 width="584" border=0>
		<col width=120></col>
		<col width=></col>
		<TR>
			<TD colspan=2 background="images/table_top_line.gif"></TD>
		</TR>
		<TR>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�Խ��� �̸�</TD>
			<TD class="td_con1"><b><span class="font_orange" style="font-size:11px;letter-spacing:-0.5pt;">&nbsp;<?=strip_tags($data->board_name)?></span></b></TD>
		</TR>
		<TR>
			<TD colspan=2 background="images/table_con_line.gif"></TD>
		</TR>
		<TR>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�Խ��� ���� ���<br></TD>
			<TD class="td_con1"><INPUT type=radio name=use_hidden value="N" <?if($data->use_hidden=="N")echo"checked";?> id=idx_use_hidden0><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_use_hidden0>�Խ��� ǥ��</LABEL>&nbsp;&nbsp;&nbsp;&nbsp;<INPUT type=radio name=use_hidden value="Y" <?if($data->use_hidden=="Y")echo"checked";?> id=idx_use_hidden1><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_use_hidden1>�Խ��� ����</LABEL></TD>
		</TR>
		<TR>
			<TD colspan=2 background="images/table_con_line.gif"></TD>
		</TR>
		<TR>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">ȸ�� ��ȣ ���<br></TD>
			<TD class="td_con1"><input type=checkbox name=use_hide_ip value="Y" <?if($data->use_hide_ip=="Y")echo"checked";?> type="checkbox" id=idx_use_hide_ip><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_use_hide_ip>ȸ��IP�����</LABEL>&nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=use_hide_email value="Y" <?if($data->use_hide_email=="Y")echo"checked";?> id=idx_use_hide_email><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_use_hide_email>ȸ��E-mail�����</LABEL>
			<br>
			<span class="font_orange" style="font-size:11px;letter-spacing:-0.5pt;">&nbsp;* ȸ���� ���������� ��ȣ�Ͻǰ�� üũ�ϼ���.&nbsp;</span>
			</TD>
		</TR>
		<TR>
			<TD colspan=2 background="images/table_con_line.gif"></TD>
		</TR>
		<TR>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">HTML �Է� ���<br></TD>
			<TD class="td_con1"><INPUT type=radio name=use_html value="Y" <?if($data->use_html=="Y")echo"checked";?> id=idx_use_html0><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_use_html0>�����</LABEL>&nbsp;&nbsp;&nbsp;&nbsp;<INPUT type=radio name=use_html value="N" <?if($data->use_html=="N")echo"checked";?> id=idx_use_html1><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_use_html1>������� ����</LABEL><br><span class="font_orange" style="font-size:11px;letter-spacing:-0.5pt;">&nbsp;* �߸��� HTML�� ������� �Խ��� ��߳� ���̴� ���� ���� �� �ֽ��ϴ�.</span></TD>
		</TR>
		<TR>
			<TD colspan=2 background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���IP���� ���<br></TD>
			<TD class="td_con1"><INPUT type=radio name=use_comip value="Y" <?if($data->use_comip=="Y")echo"checked";?> id=idx_use_comip0><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_use_comip0>�����</LABEL>&nbsp;&nbsp;&nbsp;&nbsp;<INPUT type=radio name=use_comip value="N" <?if($data->use_comip=="N")echo"checked";?> id=idx_use_comip1><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_use_comip1>������� ����</LABEL></TD>
		</tr>
		<TR>
			<TD colspan=2 background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">������ ��Ī ����<br>
			</TD>
			<TD class="td_con1">&nbsp;<INPUT maxLength="10" size="10" name=admin_name value="<?=$data->admin_name?>" class="input_selected1">
			<br>
			<span class="font_orange" style="font-size:11px;letter-spacing:-0.5pt;">&nbsp;* ������ ��Ī �۾��⸦ �����ϰ��� �� ��� �Խ��� ������ ������ �Է��ϼ���.<br>&nbsp;* �� �̸����� ��Ͻ� ��й�ȣ�� �Խ��� ���� ��й�ȣ�� ���ƾ� ����� �˴ϴ�.</span>
			</TD>
		</tr>
		<TR>
			<TD colspan=2 background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�ű� ��� �Խù�<br></TD>
			<TD class="td_con1">
			<INPUT type=radio name=newimg value=0 <?if($data->newimg==0)echo"checked";?> id=idx_newimg0><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_newimg0>1��</LABEL>&nbsp;&nbsp;
			<INPUT type=radio name=newimg value=1 <?if($data->newimg==1)echo"checked";?> id=idx_newimg1><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_newimg1>2��</LABEL>&nbsp;&nbsp;
			<INPUT type=radio name=newimg value=2 <?if($data->newimg==2)echo"checked";?> id=idx_newimg2><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_newimg2>24�ð�</LABEL>&nbsp;&nbsp;
			<INPUT type=radio name=newimg value=3 <?if($data->newimg==3)echo"checked";?> id=idx_newimg3><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_newimg3>36�ð�</LABEL>&nbsp;&nbsp;
			<INPUT type=radio name=newimg value=4 <?if($data->newimg==4)echo"checked";?> id=idx_newimg4><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_newimg4>48�ð�</LABEL>
			<br><span class="font_orange" style="font-size:11px;letter-spacing:-0.5pt;">&nbsp;* �ϴ��� ������ <b>0�� ����</b>���� �̹����� ���Դϴ�.</span></TD>
		</tr>
		<TR>
			<TD colspan=2 background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�Խñ� ��¥ ǥ��<br>
			</TD>
			<TD class="td_con1">
			<INPUT type=radio name=datedisplay value="Y" <?if($data->datedisplay=="Y")echo"checked";?> id=idx_datedisplay0><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_datedisplay0>��¥ ǥ����(�ð�����)</LABEL>&nbsp;&nbsp;
			<INPUT type=radio name=datedisplay value="O" <?if($data->datedisplay=="O")echo"checked";?> id=idx_datedisplay1><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_datedisplay1>��¥ ǥ����(����ϸ�)</LABEL>&nbsp;&nbsp;
			<INPUT type=radio name=datedisplay value="N" <?if($data->datedisplay=="N")echo"checked";?> id=idx_datedisplay2><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_datedisplay2>��¥ ǥ�þ���</LABEL>
			<br>
			<span class="font_orange" style="font-size:11px;letter-spacing:-0.5pt;">&nbsp;* ��¥ ǥ�ð� �ʿ���� FAQ���� <b>&quot;��¥ǥ�þ���&quot;</b>�� üũ�Ͻø� ����� �ȵ˴ϴ�.</span></TD>
		</tr>
		<TR>
			<TD colspan=2 background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ȸ�� ǥ�ÿ���<br>
			</TD>
			<TD class="td_con1">
			<INPUT type=radio name=hitdisplay value="Y" <?if($data->hitdisplay=="Y")echo"checked";?> id=idx_hitdisplay0><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_hitdisplay0>��ȸ�� ǥ����(ȸ��/��ȸ��)</LABEL>
			<INPUT type=radio name=hitdisplay value="M" <?if($data->hitdisplay=="M")echo"checked";?> id=idx_hitdisplay1><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_hitdisplay1>��ȸ�� ǥ����(ȸ����)</LABEL>
			<INPUT type=radio name=hitdisplay value="N" <?if($data->hitdisplay=="N")echo"checked";?> id=idx_hitdisplay2><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_hitdisplay2>��ȸ�� ǥ�þ���</LABEL>
			</TD>
		</tr>
		<TR>
			<TD colspan=2 background="images/table_con_line.gif"></TD>
		</TR>
		<TR>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�۾��� �ڵ��ٹٲ�</TD>
			<TD class="td_con1">
			<INPUT type=radio name=use_wrap value="Y" <?if($data->use_wrap=="Y")echo"checked";?> id=idx_use_wrap0><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_use_wrap0>�ڵ��ٹٲ� ���</LABEL>&nbsp;&nbsp;&nbsp;&nbsp;
			<INPUT type=radio name=use_wrap value="N" <?if($data->use_wrap=="N")echo"checked";?> id=idx_use_wrap1><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_use_wrap1>�ڵ��ٹٲ� �̻��</LABEL>
			</TD>
		</TR>
		<TR>
			<TD colspan=2 background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�Խñ� ��ȣ ���</TD>
			<TD class="td_con1">
			<INPUT type=radio name=use_article_care value="N" <?if($data->use_article_care=="N")echo"checked";?> id=idx_use_article_care0><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_use_article_care0>�Խñ� �ۼ��ڰ� ����/����</LABEL>&nbsp;&nbsp;&nbsp;&nbsp;
			<INPUT type=radio name=use_article_care value="Y" <?if($data->use_article_care=="Y")echo"checked";?> id=idx_use_article_care1><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_use_article_care1>�Խñ� �ۼ��ڰ� ����/���� ����</LABEL>
			</TD>
		</tr>
		<TR>
			<TD colspan=2 background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ư ���� ���</TD>
			<TD class="td_con1">
				<INPUT type=radio name=use_hide_button value="N" <?if($data->use_hide_button=="N")echo"checked";?> id=idx_use_hide_button0><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_use_hide_button0>�۾���,����,���� ��ư ���̱�</LABEL>&nbsp;&nbsp;&nbsp;&nbsp;
				<INPUT type=radio name=use_hide_button value="Y" <?if($data->use_hide_button=="Y")echo"checked";?> id=idx_use_hide_button1><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_use_hide_button1>�۾���,����,���� ��ư �����</LABEL>
			</TD>
		</tr>
		<TR>
			<TD colspan=2 background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��� ÷�� �̹���<br />�ִ� ������</TD>
			<TD class="td_con1">&nbsp;<INPUT type=text name=comment_width value="<?=$data->comment_width?>" onkeyup="return strnumkeyup(this);" maxLength="10" size="10" class="input_selected1"> <span class="font_orange" style="font-size:11px;letter-spacing:-0.5pt;">* �Է°��� 100 ������ ��� %�� �����˴ϴ�.</span></TD>
		</tr>
		<TR>
			<TD colspan=2 background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">������ ��ȸ�� �ɼ�</TD>
			<TD class="td_con1">
			<INPUT type=radio name=hitplus value="Y" <?if($data->hitplus=="Y")echo"checked";?> id=idx_hitplus0><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_hitplus0>������ ��ȸ�� ��������</LABEL>&nbsp;&nbsp;&nbsp;&nbsp;
			<INPUT type=radio name=hitplus value="N" <?if($data->hitplus=="N")echo"checked";?> id=idx_hitplus1><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_hitplus1>������ ��ȸ�� ����</LABEL>
			</TD>
		</tr>
		<TR>
			<TD colspan=2 background="images/table_con_line.gif"></TD>
		</TR>
		<TR>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">SNS��� ��뼳��</TD>
			<TD class="td_con1"><INPUT type=radio name=sns_state value="Y" <?if($data->sns_state=="Y")echo"checked";?> id=idx_sns_state1><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_sns_state1>SNS��� ���</LABEL>&nbsp;&nbsp;&nbsp;&nbsp;<INPUT type=radio name=sns_state value="N" <?if($data->sns_state=="N")echo"checked";?> id=idx_sns_state0><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_sns_state0>SNS��� ������</LABEL>
			<br><span class="font_blue" style="font-size:11px;letter-spacing:-0.5pt;">&nbsp;* ��д��/���ϴ��/��� �̹��� ���δ��� SNS��� ���� ������� �ʽ��ϴ�.</span>
			</TD>
		</TR>
		<TR>
			<TD colspan=2 background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�亯��Ͻ� sms�߼�</TD>
			<TD class="td_con1">
			<INPUT type=radio name=reply_sms value="Y" <?if($data->reply_sms=="Y")echo"checked";?> id=reply_sms0><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=reply_sms0>�����</LABEL>&nbsp;&nbsp;&nbsp;&nbsp;
			<INPUT type=radio name=reply_sms value="N" <?if($data->reply_sms=="N")echo"checked";?> id=reply_sms1><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=reply_sms1>������� ����</LABEL>
			</TD>
		</tr>
		<TR>
			<TD colspan=2 background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�� ��Ͻ� sms����<br></TD>
			<TD class="td_con1">
			<INPUT type=radio name=use_admin_sms value="Y" <?if($data->use_admin_sms=="Y")echo"checked";?> id=idx_use_admin_sms0><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_use_admin_sms0>�����</LABEL>&nbsp;&nbsp;&nbsp;&nbsp;
			<INPUT type=radio name=use_admin_sms value="N" <?if($data->use_admin_sms=="N")echo"checked";?> id=idx_use_admin_sms1><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_use_admin_sms1>������� ����</LABEL>
			<br>
			<span class="font_orange" style="font-size:11px;letter-spacing:-0.5pt;">&nbsp;* ����� ����Ͻ÷��� �Ʒ� ������ �޴����� ����� �Ͻñ� �ٶ��ϴ�.&nbsp;</span>
			</TD>
		</tr>
		<TR>
			<TD colspan=2 background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">������ �޴���<br></TD>
			<TD class="td_con1">
			<TEXTAREA style="WIDTH: 100%" name=admin_sms rows="3" cols="56" class="textarea"><?=$data->admin_sms?></TEXTAREA><br>
			<span class="font_orange" style="font-size:11px;letter-spacing:-0.5pt;">&nbsp;* �޸�(&quot;,&quot;)�� �����Ͽ� �������� ������ �޴��� ����� �����մϴ�.</span><br>
			<span class="font_orange" style="font-size:11px;letter-spacing:-0.5pt;">&nbsp;* �亯��Ͻ� sms�߼� ����� ����Ͻ� ��� �� ��ϵ� ������ ���Ϸ� ������ �߼��մϴ�.</span>
			</TD>
		</tr>
		<TR>
			<TD colspan=2 background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�� ��Ͻ� ���ϼ���<br></TD>
			<TD class="td_con1">
			<INPUT type=radio name=use_admin_mail value="Y" <?if($data->use_admin_mail=="Y")echo"checked";?> id=idx_use_admin_mail0><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_use_admin_mail0>�����</LABEL>&nbsp;&nbsp;&nbsp;&nbsp;
			<INPUT type=radio name=use_admin_mail value="N" <?if($data->use_admin_mail=="N")echo"checked";?> id=idx_use_admin_mail1><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_use_admin_mail1>������� ����</LABEL>
			<br>
			<span class="font_orange" style="font-size:11px;letter-spacing:-0.5pt;">&nbsp;* ����� ����Ͻ÷��� �Ʒ� ������ �̸��ϵ� ����� �Ͻñ� �ٶ��ϴ�.&nbsp;</span>
			</TD>
		</tr>
		<TR>
			<TD colspan=2 background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">������ �̸���<br></TD>
			<TD class="td_con1">
			<TEXTAREA style="WIDTH: 100%" name=admin_mail rows="5" cols="56" class="textarea"><?=$data->admin_mail?></TEXTAREA><br>
			<span class="font_orange" style="font-size:11px;letter-spacing:-0.5pt;">&nbsp;* �޸�(&quot;,&quot;)�� �����Ͽ� �������� ������ ���ϵ���� �����մϴ�.</span><br>
			<span class="font_orange" style="font-size:11px;letter-spacing:-0.5pt;">&nbsp;* �� ��Ͻ� ���Ϲ߼� ����� ����Ͻ� ��� �� ��ϵ� ������ ���Ϸ� ������ �߼��մϴ�.</span>
			</TD>
		</tr>
		<TR>
			<TD colspan=2 background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���͸� �ܾ� �Է�<br></TD>
			<TD class="td_con1">
			<TEXTAREA style="WIDTH: 100%" name=filter rows="5" cols="56" class="textarea"><?=$data->filter?></TEXTAREA><br>
			<span class="font_orange" style="font-size:11px;letter-spacing:-0.5pt;">&nbsp;* �޸�(&quot;,&quot;)�� �����Ͽ� �������� ���͸� �ܾ ��� �����մϴ�.</span><br>
			<span class="font_orange" style="font-size:11px;letter-spacing:-0.5pt;">&nbsp;* �Խ��� �� �Է½� ��ϵ� ���͸� �ܾ ���ԵǾ� ������ �Խñ� ����� �ȵ˴ϴ�.</span><br>
			<span class="font_orange" style="font-size:11px;letter-spacing:-0.5pt;">&nbsp;* �弳 �� ���� ����ϴ� �ܾ� ���� ����Ͽ� ����Ͻø� �˴ϴ�.</span>
			</TD>
		</tr>
		<TR>
			<TD colspan=2 background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�Խ��� ��������IP</TD>
			<TD class="td_con1">
			<TEXTAREA style="WIDTH: 100%" name=avoid_ip rows="5" cols="56" class="textarea"><?=$data->avoid_ip?></TEXTAREA><br>
			<span class="font_orange" style="font-size:11px;letter-spacing:-0.5pt;">&nbsp;* �޸�(&quot;,&quot;)�� �����Ͽ� �������� ��������IP�� ��� �����մϴ�.</span><br>
			<span class="font_orange" style="font-size:11px;letter-spacing:-0.5pt;">&nbsp;* �ҷ������ �Ǵ� ����Ʈ ��� �ذ� �Ǵ� ����ڸ� ������ �� �ֽ��ϴ�.</span>
			</TD>
		</tr>
		<TR>
			<TD colspan=2 background="images/table_top_line.gif"></TD>
		</TR>
		</TABLE>
		</td>
	</tr>
	<tr><td height=10></td></tr>
	<tr>
		<td width="100%" align="center"><input type="image" src="images/bnt_apply.gif" width="76" height="28" border="0" vspace="10" border=0 onclick="CheckForm(this.form)"><a href="javascript:window.close()"><img src="images/btn_cancel.gif" width="76" height="28" border="0" vspace="10" border=0 hspace="2"></a></td>
	</tr>
	</table>
	</TD>
</TR>
</TABLE>
</body>
</html>

<?=$onload?>