<?
if(strlen($Dir)==0) $Dir="../";

include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

if($one==1) {
	$sql = "SELECT * FROM tbleventpopup WHERE num='".$num."' ";
	$result = mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	mysql_free_result($result);
}

$cookiename="eventpopup_".$row->num;

if ($layer=="Y" && $row->end_date==$_COOKIE[$cookiename]) return;

if($layer!="Y") {
?>
<HTML>
<HEAD>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">

<TITLE><?=$row->title?></TITLE>
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
<SCRIPT LANGUAGE="JavaScript">
<!--
parent.window.moveTo('<?=$row->x_to?>','<?=$row->y_to?>');
//-->
</SCRIPT>
</HEAD>
<BODY BGCOLOR=#FFFFFF LEFTMARGIN=0 TOPMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>
<?}?>
<table cellpadding="0" cellspacing="0" width="100%" height="100%" bgcolor="#FFFFFF">
<tr>
	<td>
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td><IMG SRC="<?=$Dir?>images/common/event/<?=$row->design?>/eventpopup_skin4_title.gif" border="0"></td>
		<td align="right"><IMG SRC="<?=$Dir?>images/common/event/<?=$row->design?>/eventpopup_skin4_img.gif" border="0"></td>
	</tr>
	</table>
	</td>
</tr>
<tr>
	<td height="100%" valign="top">
	<TABLE WIDTH="100%" height="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0">
	<TR>
		<TD><IMG SRC="<?=$Dir?>images/common/event/<?=$row->design?>/eventpopup_skin4_t09.gif" border="0"></TD>
		<TD width="100%" background="<?=$Dir?>images/common/event/<?=$row->design?>/eventpopup_skin4_t02.gif"></TD>
		<TD><IMG SRC="<?=$Dir?>images/common/event/<?=$row->design?>/eventpopup_skin4_t03.gif" border="0"></TD>
	</TR>
	<TR>
		<TD background="<?=$Dir?>images/common/event/<?=$row->design?>/eventpopup_skin4_t08.gif"></TD>
		<TD width="100%" height="100%" valign="top">
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td><?=$row->title?></td>
		</tr>
		<tr>
			<td height="10"></td>
		</tr>
		<tr>
			<td><?=$row->content?></td>
		</tr>
		</table>
		</TD>
		<TD background="<?=$Dir?>images/common/event/<?=$row->design?>/eventpopup_skin4_t04.gif"></TD>
	</TR>
	<TR>
		<TD><IMG SRC="<?=$Dir?>images/common/event/<?=$row->design?>/eventpopup_skin4_t07.gif" border="0"></TD>
		<TD width="100%" background="<?=$Dir?>images/common/event/<?=$row->design?>/eventpopup_skin4_t06.gif">&nbsp;</TD>
		<TD><IMG SRC="<?=$Dir?>images/common/event/<?=$row->design?>/eventpopup_skin4_t05.gif" border="0"></TD>
	</TR>
	</TABLE>
	</td>
</tr>
<?if($one!=1){?>
<TR>
	<TD align=center valign=top height=30>
	<TABLE width="100%" border="0" cellspacing="3" cellpadding="0">
	<form name=event_form1 method=post action="<?=$Dir.FrontDir?>event.php">
	<input type=hidden name=type value="close">
	<input type=hidden name=num value="<?=$row->num?>">
	<TR>
		<TD align="right"><input type=checkbox id="idx_no" name=no value="yes" style="border:none" <?=($layer=="Y"?" onclick=p_windowclose('".$cookiename."','1');":"")?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_no><font color=#000000>���� �� â�� �ٽ� ���� ����</font></label>&nbsp;&nbsp;<a href="JavaScript:<?=($layer=="Y"?"p_windowclose('".$cookiename."','0')":"document.event_form1.submit();")?>"><IMG src="<?=$Dir?>images/common/event_popup_close.gif" border="0" align=absmiddle></A>&nbsp;</TD>
	</TR>
	</form>
	</TABLE>
	</TD>
</TR>
<?}?>
</table>
<?if($layer!="Y"){?>
</BODY>
</HTML>
<?}?>