<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");

$brand_name=$_REQUEST["brand_name"];
if(strlen($brand_name)>0) {
	$sql = "SELECT COUNT(*) as cnt FROM tblvenderstore ";
	$sql.= "WHERE vender!='".$_VenderInfo->getVidx()."' ";
	$sql.= "AND brand_name='".$brand_name."' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	$cnt=$row->cnt;
	mysql_free_result($result);
}
?>
<html>
<head>
<title>������ ������</title>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">

<link rel="stylesheet" href="style.css">
<script language=Javascript>
<!--
function confirmMinishopName() {
	if ( form1.brand_name.value == "" ) {
		alert("�̴ϼ����� �Է��ϼ���.");
		form1.brand_name.focus();
	} else {
		form1.submit();
	}
	return;
}

</script>
</head>
<body marginwidth=0 marginheight=0 leftmargin=0 topmargin=0 onload="form1.brand_name.select()">
<center>
<form name=form1 method=post>
<table width=600 height=400 border=0 cellspacing=0 cellpadding=0>
<tr>
	<td><img src=images/pop_title04.gif border=0></td>
</tr>
<tr>
	<td height=100% valign=top style=padding:10>
		<table width=100% border=0 cellspacing=0 cellpadding=0>
		<tr>
			<td height=4></td>
		</tr>
		<tr>
			<td valign=top bgcolor=D4D4D4 style=padding:1>
				<table width=100% border=0 cellspacing=0 cellpadding=0 bgcolor=FEFCE2>
				<tr>
					<td style=padding:20><B>�� <?=$brand_name?> ����(��)
					<?if($cnt>0) {?>
						�̹� ��� �� �� �̴ϼ� �̸��Դϴ�.
					<?}else{?>
						��� ������ �̴ϼ� �̸��Դϴ�.
					<?}?>
					</B>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
		<table width=100% border=0 cellspacing=0 cellpadding=0>
		<tr>
			<td height=30></td>
		</tr>
		<tr>
			<td style=padding-left:5><img src="images/icon_dot03.gif" border=0 align=absmiddle> <B>�̴ϼ� �̸� �ߺ� ���θ� �ݵ�� Ȯ���ϼ���.</B></td>
		</tr>
		<tr>
			<td height=2></td>
		</tr>
		<tr>
			<td height=2 bgcolor=E6567B></td>
		</tr>
		<tr>
			<td valign=top>
				<table width=100% border=0 cellspacing=0 cellpadding=0>
				<tr height=64>
					<td width=20% bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9 class=blackb>�̴ϼ� �̸�</td>
					<td width=80% style=padding:7,10><input type=text name="brand_name" maxlength=20 value="<?=$brand_name?>" class=txt> <a href="Javascript:confirmMinishopName();"><img src=images/btn_confirm10.gif border=0 align=absmiddle></a></td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td height=1 bgcolor=CDCDCD></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td height=30 align=right bgcolor=DEDEDE style=padding-top:3><a href=javascript:self.close()><img src=images/btn_close01.gif border=0 align=absmiddle></a>&nbsp;</td>
</tr>
</table>
</form>
</center>
</body>
</html>