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
<table width="100%" height="100%" cellpadding="0" cellspacing="0">
<tr>
	<td height="100%" valign="top" background="<?=$Dir?>images/common/event/<?=$row->design?>/eventpopup_skin1_bg.gif">
	<table width="100%" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td height="100%" valign="top">			
		<table width="100%" width="100%" height="100%" cellpadding="0" cellspacing="0">
		<tr>
			<TD><IMG SRC="<?=$Dir?>images/common/event/<?=$row->design?>/eventpopup_skin3_t01.gif" border="0"></TD>
			<TD width="100%" background="<?=$Dir?>images/common/event/<?=$row->design?>/eventpopup_skin3_t01bg.gif"></TD>
			<TD><IMG SRC="<?=$Dir?>images/common/event/<?=$row->design?>/eventpopup_skin3_t02.gif" border="0"></TD>
		</tr>
		<tr>
			<td background="<?=$Dir?>images/common/event/<?=$row->design?>/eventpopup_skin3_t04bg.gif"></td>
			<td width="100%" height="100%" bgcolor="#FFFFF6">
			<table width="100%" height="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td colspan="2">
				<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td><IMG SRC="<?=$Dir?>images/common/event/<?=$row->design?>/eventpopup_skin3_title.gif" border="0"></td>
					<td width="100%" background="<?=$Dir?>images/common/event/<?=$row->design?>/eventpopup_skin3_titlebg.gif"></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td width="100%" height="100%" valign="top" style="padding:15px;">
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
				</td>
			</tr>
			</table>
			</td>
			<td background="<?=$Dir?>images/common/event/<?=$row->design?>/eventpopup_skin3_t02bg.gif"></td>
		</tr>
		<tr>
			<TD><IMG SRC="<?=$Dir?>images/common/event/<?=$row->design?>/eventpopup_skin3_t04.gif" border="0"></TD>
			<TD width="100%" background="<?=$Dir?>images/common/event/<?=$row->design?>/eventpopup_skin3_t03bg.gif"></TD>
			<TD><IMG SRC="<?=$Dir?>images/common/event/<?=$row->design?>/eventpopup_skin3_t03.gif" border="0"></TD>
		</tr>
		<?if($one!=1){?>
		<TR>
			<TD valign="top" colspan="3" bgcolor="#FFFFFF">
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
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>
<?if($layer!="Y"){?>
</BODY>
</HTML>
<?}?>