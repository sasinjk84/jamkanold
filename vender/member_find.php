<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");

$formname=$_POST["formname"];
$s_check=$_POST["s_check"];
$search=$_POST["search"];
?>

<html>
<head>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">

<title>�ܰ�ȸ�� ���̵� �˻�</title>
<link rel="stylesheet" href="style.css" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
function PageResize() {
	var oWidth = document.all.table_body.clientWidth + 10;
	//var oHeight = document.all.table_body.clientHeight + 55;
	var oHeight = 300;

	window.resizeTo(oWidth,oHeight);
}

function SearchMember() {
	if(document.form1.search.value.length==0) {
		alert("ȸ�� ���̵�, �Ǵ� ȸ������ �Է��ϼ���.");
		document.form1.search.focus();
		return;
	}
	if(document.form1.search.value.length<=2) {
		alert("�˻� Ű����� 2�� �̻� �Է��ϼž� �մϴ�.");
		document.form1.search.focus();
		return;
	}
	document.form1.submit();
}

function selectid(id) {
	opener.document[document.form1.formname.value].id.value = id;
	window.close();
}
//-->
</SCRIPT>
</head>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 oncontextmenu="return false" style="overflow-x:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false" onLoad="PageResize();">

<table border=0 cellpadding=0 cellspacing=0 width=380 style="table-layout:fixed;" id=table_body>
<tr>
	<td width=100% align=center>
	<table border=0 cellpadding=3 cellspacing=0 width=100% style="table-layout:fixed;">
	<tr height=30>
		<td bgcolor="#F9799A" style="padding-left:15"><FONT COLOR="#ffffff"><B>�ܰ�ȸ�� ���̵� �˻�</B></FONT></td>
	</tr>
	</table>

	<table border=0 cellpadding=0 cellspacing=0 width=100%>
	<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
	<input type=hidden name=formname value="<?=$formname?>">
	<tr><td height=30></td></tr>
	<tr>
		<td align=center>
		<table border=0 cellpadding=0 cellspacing=0 width=100%>
		<tr>
			<td align=center>
			<select name=s_check>
			<option value="id" <?if($s_check=="id") echo "checked";?>>���̵�</option>
			<option value="name" <?if($s_check=="name") echo "checked";?>>ȸ����</option>
			</select>
			<input type=text name=search value="<?=$search?>" style="width:120">
			<input type=button value=" �˻� " class=button onclick="SearchMember();">
			</td>
		</tr>
		<tr>
			<td align=center style="padding-top:3">* ���̵�, ȸ�������� ��ȸ�Ͻ� �� �ֽ��ϴ�.</td>
		</tr>
		<tr><td height=20></td></tr>
		</table>
		</td>
	</tr>
	<tr>
		<td style="padding-left:5px">
		<?if(strlen($search)>0 && strlen($s_check)>0) {?>
		<table border=0 cellpadding=2 cellspacing=0 width=94% style="table-layout:fixed">
		<col width=80></col>
		<col width=70></col>
		<col width=></col>
		<col width=60></col>
		<tr height=30 bgcolor=#F5F5F5>
			<td colspan=4 align=center><B>�˻����</B></td>
		</tr>
<?
		$sql = "SELECT b.id, b.name, b.home_tel, b.resno FROM tblregiststore a, tblmember b ";
		$sql.= "WHERE a.vender='".$_VenderInfo->getVidx()."' AND a.id=b.id AND b.member_out = 'N' ";
		$sql.= "AND b.".$s_check." LIKE '%".$search."%' ";
		$result = mysql_query($sql,get_db_conn());
		$count=0;
		while($row=mysql_fetch_object($result)) {
			$count++;
			echo "<tr bgcolor=#FFFFFF>\n";
			echo "	<td align=center><A HREF=\"javascript:selectid('".$row->id."');\"><FONT COLOR=\"blue\"><U>".$row->id."</U></FONT></A></td>\n";
			echo "	<td align=center>".$row->name."</td>\n";
			echo "	<td align=center>&nbsp;".$row->home_tel."&nbsp;</td>\n";
			echo "	<td align=center><A HREF=\"javascript:selectid('".$row->id."')\"><U><B>����</B></U></A></td>\n";
			echo "</tr>\n";
		}
		mysql_free_result($result);
?>
		</table>
		<?}?>
		</td>
	</tr>
	<tr><td height=10></td></tr>
	</form>
	</table>
	</td>
</tr>
</table>
</body>
</html>