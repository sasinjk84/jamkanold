<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

if(strlen($_ShopInfo->getId())==0){
	echo "<script>alert('�������� ��η� �����Ͻñ� �ٶ��ϴ�.');window.close();</script>";
	exit;
}

$type = $_REQUEST["type"];

$mode = $_POST["mode"];
$seachIdx = $_POST["seachIdx"];
$selectname = $_POST["selectname"];
$up_selectlist = $_POST["up_selectlist"];

if($type!="PR" && $type!="MA" && $type!="MO") {
	echo "<script>alert('ó���� ���� ����Ÿ�� �����մϴ�.');window.close();</script>";
	exit;
} else {
	$fieldname = array("PR"=>"production", "MA"=>"madein", "MO"=>"model");
	$pagename = array("PR"=>"�����縦", "MA"=>"��������", "MO"=>"�𵨸���");
	$valuemax = array("PR"=>"50", "MA"=>"30", "MO"=>"50");
}

if($mode == "insert" && strlen($selectname)>0) {
	$sql = "INSERT tblproductselect SET ";
	$sql.= "type		= '".$type."', ";
	$sql.= "selectname	= '".$selectname."' ";
	@mysql_query($sql,get_db_conn());
	$onload="<script>alert('����� ���������� �Ϸ� �ƽ��ϴ�.');</script>";
} else if($mode == "delete" && strlen($up_selectlist)>0) {
	$sql = "DELETE FROM tblproductselect ";
	$sql.= "WHERE type='".$type."' AND num = '".$up_selectlist."' ";
	@mysql_query($sql,get_db_conn());
	$onload="<script>alert('������ ���������� �Ϸ� �ƽ��ϴ�.');</script>";
}
?>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html;charset=euc-kr'>
<title><?=$pagename[$type]?> �����ϱ�</title>
<link rel="stylesheet" href="style.css" type="text/css">
<script type="text/javascript" src="lib.js.php"></script>
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
	var oHeight = document.all.table_body.clientHeight + 75;

	window.resizeTo(oWidth,oHeight);
}

function SearchSubmit(seachIdxval) {
	form = document.form1;

	form.seachIdx.value = seachIdxval;
	form.mode.value = "search";
	form.submit();
}

function Result() {
	try {
		if(opener.document.form1.<?=$fieldname[$type]?>) {
			if(document.form1.up_selectlist.selectedIndex>-1) {
				opener.document.form1.<?=$fieldname[$type]?>.value=document.form1.up_selectlist.options[document.form1.up_selectlist.selectedIndex].text;
				window.close();
			} else {
				alert('������ <?=$pagename[$type]?> ������ �ּ���.');
			}
		} else {
			alert('������ ���� �������� �ʽ��ϴ�.');
		}
	} catch(e) {
		alert('��ǰ���/���� ������������ ����˴ϴ�.');
	}
}

function CheckForm(modeval) {
	form = document.form1;
	
	if(modeval=="insert" && !form.selectname.value) {
		alert('����� <?=$pagename[$type]?> �Է��� �ּ���.');
	} else if(modeval=="delete" && document.form1.up_selectlist.selectedIndex==-1) {
		alert('������ <?=$pagename[$type]?> ������ �ּ���.');
	} else {
		if(modeval=="insert" && confirm("<?=$pagename[$type]?> ���� ����ϰڽ��ϱ�?")) {
			form.mode.value = modeval;
			form.submit();
		} else if(modeval=="delete" && confirm("������ �ϴ��� ���� �Էµ� ��ǰ ������ ���� ���� �ʽ��ϴ�.\n\n<?=$pagename[$type]?> <?=$pagename[$type]?> ���� �����ϰڽ��ϱ�?")) {
			form.mode.value = modeval;
			form.submit();
		}
	}
}
//-->
</SCRIPT>
</head>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 oncontextmenu="return false" style="overflow-x:hidden;overflow-y:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false" onLoad="PageResize();">

<STYLE type=text/css>
	#menuBar {}
	#contentDiv {WIDTH: 245;HEIGHT: 320;}
</STYLE>
<TABLE WIDTH="420" BORDER=0 CELLPADDING=0 CELLSPACING=0 style="table-layout:fixed;" id=table_body>
<tr>
	<td>
	<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
		<TD>
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td><img src="images/newtitle_icon.gif" border="0" width="29" height="31"></td>
			<td width="100%" background="images/member_mailallsend_imgbg.gif">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td><b><font color="white"><?=$pagename[$type]?> �����ϱ�</b></font></td>
			</tr>
			</table>
			</td>
			<td align="right"><img src="images/member_mailallsend_img2.gif" width="20" height="31" border="0"></td>
		</tr>
		</table>
		</TD>
	</TR>
	<TR>
		<TD height="10"></TD>
	</TR>
	<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
	<input type=hidden name=mode value="">
	<input type=hidden name=type value="<?=$type?>">
	<input type=hidden name=seachIdx value="">
	<tr>
		<TD style="padding-left:4pt;padding-right:4pt;" valign="top">
		<table border=0 cellpadding=0 cellspacing=0 width="100%">
		<tr>
			<td style="padding:5px;padding-left:2px;padding-right:2px;"><b><a href="javascript:SearchSubmit('A');"><span id="A">A</span></a> 
			<a href="javascript:SearchSubmit('B');"><span id="B">B</span></a> 
			<a href="javascript:SearchSubmit('C');"><span id="C">C</span></a> 
			<a href="javascript:SearchSubmit('D');"><span id="D">D</span></a> 
			<a href="javascript:SearchSubmit('E');"><span id="E">E</span></a> 
			<a href="javascript:SearchSubmit('F');"><span id="F">F</span></a> 
			<a href="javascript:SearchSubmit('G');"><span id="G">G</span></a> 
			<a href="javascript:SearchSubmit('H');"><span id="H">H</span></a> 
			<a href="javascript:SearchSubmit('I');"><span id="I">I</span></a> 
			<a href="javascript:SearchSubmit('J');"><span id="J">J</span></a> 
			<a href="javascript:SearchSubmit('K');"><span id="K">K</span></a> 
			<a href="javascript:SearchSubmit('L');"><span id="L">L</span></a> 
			<a href="javascript:SearchSubmit('M');"><span id="M">M</span></a> 
			<a href="javascript:SearchSubmit('N');"><span id="N">N</span></a> 
			<a href="javascript:SearchSubmit('O');"><span id="O">O</span></a> 
			<a href="javascript:SearchSubmit('P');"><span id="P">P</span></a> 
			<a href="javascript:SearchSubmit('Q');"><span id="Q">Q</span></a> 
			<a href="javascript:SearchSubmit('R');"><span id="R">R</span></a> 
			<a href="javascript:SearchSubmit('S');"><span id="S">S</span></a> 
			<a href="javascript:SearchSubmit('T');"><span id="T">T</span></a> 
			<a href="javascript:SearchSubmit('U');"><span id="U">U</span></a> 
			<a href="javascript:SearchSubmit('V');"><span id="V">V</span></a> 
			<a href="javascript:SearchSubmit('W');"><span id="W">W</span></a> 
			<a href="javascript:SearchSubmit('X');"><span id="X">X</span></a> 
			<a href="javascript:SearchSubmit('Y');"><span id="Y">Y</span></a> 
			<a href="javascript:SearchSubmit('Z');"><span id="Z">Z</span></a></b></td>
			<td width="40" align="center" nowrap><b><a href="javascript:SearchSubmit('��ü');"><span id="��ü">��ü</span></a></b></td>
		</tr>
		<tr>
			<!-- ��ǰī�װ� ��� -->
			<td><select name="up_selectlist" size="20" style="width:100%;" ondblclick="Result();">
<?
	$sql = "SELECT * FROM tblproductselect ";
	$sql .= "WHERE type='".$type."' ";

	if(ereg("^[A-Z]", $seachIdx)) {
		$sql.= "AND selectname LIKE '".$seachIdx."%' OR selectname LIKE '".strtolower($seachIdx)."%' ";	
		$sql.= "ORDER BY selectname ";
	} else if(ereg("^[��-��]", $seachIdx)) {
		if($seachIdx == "��") $sql.= "AND (selectname >= '��' AND selectname < '��') OR (selectname >= '��' AND selectname < '��') ";
		if($seachIdx == "��") $sql.= "AND (selectname >= '��' AND selectname < '��') OR (selectname >= '��' AND selectname < '��') ";
		if($seachIdx == "��") $sql.= "AND (selectname >= '��' AND selectname < '��') OR (selectname >= '��' AND selectname < '��') ";
		if($seachIdx == "��") $sql.= "AND (selectname >= '��' AND selectname < '��') OR (selectname >= '��' AND selectname < '��') ";
		if($seachIdx == "��") $sql.= "AND (selectname >= '��' AND selectname < '��') OR (selectname >= '��' AND selectname < '��') ";
		if($seachIdx == "��") $sql.= "AND (selectname >= '��' AND selectname < '��') OR (selectname >= '��' AND selectname < '��') ";
		if($seachIdx == "��") $sql.= "AND (selectname >= '��' AND selectname < '��') OR (selectname >= '��' AND selectname < '��') ";
		if($seachIdx == "��") $sql.= "AND (selectname >= '��' AND selectname < '��') OR (selectname >= '��' AND selectname < '��') ";
		if($seachIdx == "��") $sql.= "AND (selectname >= '��' AND selectname < '��') OR (selectname >= '��' AND selectname < '��') ";
		if($seachIdx == "��") $sql.= "AND (selectname >= '��' AND selectname < '��') OR (selectname >= '��' AND selectname < 'ī') ";
		if($seachIdx == "��") $sql.= "AND (selectname >= '��' AND selectname < '��') OR (selectname >= 'ī' AND selectname < 'Ÿ') ";
		if($seachIdx == "��") $sql.= "AND (selectname >= '��' AND selectname < '��') OR (selectname >= 'Ÿ' AND selectname < '��') ";
		if($seachIdx == "��") $sql.= "AND (selectname >= '��' AND selectname < '��') OR (selectname >= '��' AND selectname < '��') ";
		if($seachIdx == "��") $sql.= "AND (selectname >= '��' AND selectname < '��') OR (selectname >= '��' AND selectname < 'ɡ') ";
		$sql.= "ORDER BY selectname ";
	} else if($seachIdx == "��Ÿ") {
		$sql.= "AND (selectname < '��' OR selectname >= '��') AND (selectname < '��' OR selectname >= 'ɡ') AND (selectname < 'a' OR selectname >= '{') AND (selectname < 'A' OR selectname >= '[') ";
		$sql.= "ORDER BY selectname ";
	} else {
		$sql.= "ORDER BY selectname ";
	}

	$result=mysql_query($sql,get_db_conn());
	while($row=mysql_fetch_object($result)) {
		echo "<option value=\"".$row->num."\">".$row->selectname."</option>";
	}
?>
			</select></td>
			<td width="40" align="center" nowrap style="line-height:21px;" valign="top"><b><a href="javascript:SearchSubmit('��');"><span id="��">��</span></a><br>
			<a href="javascript:SearchSubmit('��');"><span id="��">��</span></a><br>
			<a href="javascript:SearchSubmit('��');"><span id="��">��</span></a><br>
			<a href="javascript:SearchSubmit('��');"><span id="��">��</span></a><br>
			<a href="javascript:SearchSubmit('��');"><span id="��">��</span></a><br>
			<a href="javascript:SearchSubmit('��');"><span id="��">��</span></a><br>
			<a href="javascript:SearchSubmit('��');"><span id="��">��</span></a><br>
			<a href="javascript:SearchSubmit('��');"><span id="��">��</span></a><br>
			<a href="javascript:SearchSubmit('��');"><span id="��">��</span></a><br>
			<a href="javascript:SearchSubmit('��');"><span id="��">��</span></a><br>
			<a href="javascript:SearchSubmit('��');"><span id="��">��</span></a><br>
			<a href="javascript:SearchSubmit('��');"><span id="��">��</span></a><br>
			<a href="javascript:SearchSubmit('��');"><span id="��">��</span></a><br>
			<a href="javascript:SearchSubmit('��');"><span id="��">��</span></a><br>
			<a href="javascript:SearchSubmit('��Ÿ');"><span id="��Ÿ">��Ÿ</span></a></b></td>
			<!-- ��ǰī�װ� ��� �� -->
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td><?=$pagename[$type]?> ��� : <input type="text" name="selectname" value="" class="input" size="38" onKeyDown="chkFieldMaxLen(<?=$valuemax[$type]?>)"> <a href="javascript:CheckForm('insert');"><img src="images/btn_input.gif" border="0" align="absmiddle"></a></td>
			<td align="center"><a href="javascript:CheckForm('delete');"><img src="images/btn_delete1.gif" border="0" align="absmiddle"></a></td>
		</tr>
		</table>
		</TD>
	</tr>
	<TR>
		<TD height="10"></TD>
	</TR>
	<TR>
		<TD align=center><a href="javascript:Result();"><img src="images/btn_select1.gif" border="0"></a>&nbsp;&nbsp;<a href="javascript:window.close();"><img src="images/btn_close.gif" border="0" hspace="2"></a></TD>
	</TR>
	</form>
	</table>
	</td>
</tr>
</TABLE>
<script language="javascript">
<!--
<?
	if(strlen($seachIdx)>0) {
		echo "document.getElementById(\"$seachIdx\").style.color=\"#FF4C00\";";
	} else {
		echo "document.getElementById(\"��ü\").style.color=\"#FF4C00\";";
	}
?>
//-->
</script>
<?=$onload?>
</body>
</html>