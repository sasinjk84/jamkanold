<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");
include_once($Dir."lib/admin_more.php");


$date=$_POST["date"];
if(strlen($date)!=8) {
	echo "<html></head><body onload=\"alert('��ȸ ��¥�� �߸��Ǿ����ϴ�.');window.close();\"></body></html>";exit;
}

$sql = "SELECT * FROM order_account_new WHERE vender='".$_VenderInfo->getVidx()."' AND date='".$date."' ";
$result=mysql_query($sql,get_db_conn());
$adata=mysql_fetch_object($result);
mysql_free_result($result);

$tmpdate=substr($date,0,4)."/".substr($date,4,2)."/".substr($date,6,2);
if(!$adata) {
	echo "<html></head><body onload=\"alert('".$tmpdate." ��¥�� ���곻���� �������� �ʽ��ϴ�.');window.close();\"></body></html>";exit;
}

$mode=$_POST["mode"];
if($mode=="confirm") {
	if($adata->confirm!="Y") {
		$sql = "UPDATE order_account_new SET confirm='Y' WHERE vender='".$_VenderInfo->getVidx()."' AND date='".$date."' ";
		if(mysql_query($sql,get_db_conn())) {

			completeVenderOrderAccount($_VenderInfo->getVidx(), $date);
			$adata->confirm="Y";
		}
	}
}
?>

<html>
<head>
<title>������ ������</title>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">

<link rel="stylesheet" href="style.css">
<script language=Javascript>
function PageResize() {
	var oWidth = document.all.table_body.clientWidth + 10;
	var oHeight = document.all.table_body.clientHeight + 75;

	window.resizeTo(oWidth,oHeight);
}
function accountOK() {
	if(confirm("���� �ݾ��� �������� �Աݵ� ������ Ȯ���ϼ̽��ϱ�?")) {
		document.form1.mode.value="confirm";
		document.form1.submit();
	}
}
</script>
</head>
<body marginwidth=0 marginheight=0 leftmargin=0 topmargin=0 style="overflow-x:hidden;overflow-y:hidden;" onLoad="PageResize();">
<center>
<table border=0 cellpadding=0 cellspacing=0 width=400 style="table-layout:fixed;" id=table_body>
<tr>
	<td width=100%>
	<table border=0 cellpadding=3 cellspacing=0 width=100% style="table-layout:fixed;">
	<tr>
		<td bgcolor="#F9799A" style="padding-left:15"><FONT COLOR="#ffffff"><B><?=$tmpdate?> ���� ����</B></FONT></td>
	</tr>
	</table>

	<table border=0 cellpadding=0 cellspacing=0 width=100%>
	<tr><td height=10></td></tr>
	<tr>
		<td align=center>
		<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
		<col width=100></col>
		<col width=></col>
		<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
		<tr>
			<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:2>
			&nbsp;��������
			</td>
			<td style=padding:7,10>
			<B><?=$tmpdate?></B>
			</td>
		</tr>
		<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
		<tr>
			<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:2>
			&nbsp;����ݾ�
			</td>
			<td style=padding:7,10>
			<B><?=number_format($adata->price)?>��</B>
			</td>
		</tr>
		<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
		<tr>
			<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:2>
			&nbsp;�Ա�Ȯ��
			</td>
			<td style=padding:7,10>
			<B>
			<?=($adata->confirm=="Y"?"�Ա� Ȯ��":"<font color=red>�Ա� ��Ȯ��</font>")?>
			</B>
			<?if($adata->confirm!="Y"){?>
			&nbsp;
			<!--<A HREF="javascript:accountOK()"><img src=images/btn_accountok.gif border=0></A>-->
			<A HREF="javascript:accountOK()"><span style="color:#ffffff;background-color:#acacac;padding:3px;">�Ա�Ȯ��</span></A>
			<?}?>
			</td>
		</tr>
		<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
		<tr>
			<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:2>
			&nbsp;�Աݰ���
			</td>
			<td style=padding:7,10>
			<B><?=$adata->bank_account?></B>
			</td>
		</tr>
		<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
		<tr>
			<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:2>
			&nbsp;����޸�
			</td>
			<td style=padding:7,10>
			<textarea style="width:100%;height:80" readonly><?=$adata->memo?></textarea>
			</td>
		</tr>
		<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
		<tr><td height=10></td></tr>
		<tr>
			<td colspan=2 align=center>
			<A HREF="javascript:window.close()"><img src=images/btn_close03.gif border=0></A>
			
			</td>
		</tr>
		</table>
		</td>
	</tr>
	<tr><td height=10></td></tr>
	</table>
	</td>
</tr>

<form name=form1 method=post action="<?=$_SERVER[PHP_SELF]?>">
<input type=hidden name=mode>
<input type=hidden name=date value="<?=$date?>">
</form>

</table>
</center>
</body>
</html>