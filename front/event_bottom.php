<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$num=$_REQUEST["num"];
?>
<HTML>
<HEAD>
<TITLE>�̺�Ʈ</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<style>
td {font-family:Tahoma;color:666666;font-size:9pt;}

tr {font-family:Tahoma;color:666666;font-size:9pt;}
BODY,TD,SELECT,DIV,form,TEXTAREA,center,option,pre,blockquote {font-family:Tahoma;color:000000;font-size:9pt;}

</style>
</HEAD>
<BODY STYLE="MARGIN:0; PADDING:0" bgcolor=#000000>
<table border=0 cellpadding=0 cellspacing=0 width=100%>
<form name=event_form1 method=post action="<?=$Dir.FrontDir?>event.php" target=_parent>
<input type=hidden name=type value="close">
<input type=hidden name=num value="<?=$num?>">
<tr>
	<td nowrap style="padding-left:20;"><input type=checkbox id="idx_no" name=no value="yes"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_no><font color=#ffffff>���� �� â�� �ٽ� ���� ����</font></label></td>
	<td align=right style="padding-right:15"><a href="JavaScript:document.event_form1.submit();"><FONT COLOR="#ffffff">[����]</FONT></a></td>
</form>
</tr>
</table>
</BODY>
</HTML>