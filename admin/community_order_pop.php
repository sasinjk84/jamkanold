<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

if(strlen($_ShopInfo->getId())==0){
	echo "<script>alert('�������� ��η� �����Ͻñ� �ٶ��ϴ�.');window.close();</script>";
	exit;
}

$mode=$_POST["mode"];
$boards=$_POST["boards"];
$cnt=(int)$_POST["cnt"];
$change=$_POST["change"];

//��������
if($mode=="sequence" && strlen($boards)>0 && $cnt>1 && $change=="Y") {
	$date=date("Ymd");
	$date1=date("His");
	$tok = strtok($boards,",");
	while ($tok) {
		$board = $tok;
		$tok = strtok(",");
		$sql = "UPDATE tblboardadmin SET date='".$date.$date1."' WHERE board='".$board."' ";
		mysql_query($sql,get_db_conn());
		$date1--;
	}
	echo "<script>alert('�Խ��� ���� ������ �Ϸ�Ǿ����ϴ�.');opener.location.reload();window.close();</script>";
	exit;
}
?>

<html>
<head>
<meta http-equiv='Content-Type' content='text/html;charset=euc-kr'>
<title>�Խ��� ���� ����</title>
<link rel="stylesheet" href="style.css" type="text/css">
<script type="text/javascript" src="codeinit.js.php"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--
//document.onkeydown = CheckKeyPress;
//document.onkeyup = CheckKeyPress;
function CheckKeyPress() {
	ekey = event.keyCode;

	if(ekey == 38 || ekey == 40 || ekey == 112 || ekey ==17 || ekey == 18 || ekey == 25 || ekey == 122 || ekey == 116) {
		event.keyCode = 0;
		return false;
	}
}

function PageResize() {
	var oWidth = document.all.table_body.clientWidth + 10;
	var oHeight = document.all.table_body.clientHeight + 62;

	window.resizeTo(oWidth,oHeight);
}

function move(gbn) {
	change_idx = document.form1.board.selectedIndex;
	if (change_idx<0) {
		alert("������ ������ �Խ����� �����ϼ���.");
		return;
	}
	if (gbn=="up" && change_idx==0) {
		alert("�����Ͻ� �Խ����� ���̻� ���� �̵����� �ʽ��ϴ�.");
		return;
	}
	if (gbn=="down" && change_idx==(document.form1.board.length-1)) {
		alert("�����Ͻ� �Խ����� ���̻� �Ʒ��� �̵����� �ʽ��ϴ�.");
		return;
	}
	if (gbn=="up") idx = change_idx-1;
	else idx = change_idx+1;

	idx_value = document.form1.board.options[idx].value;
	idx_text = document.form1.board.options[idx].text;

	document.form1.board.options[idx].value = document.form1.board.options[change_idx].value;
	document.form1.board.options[idx].text = document.form1.board.options[change_idx].text;

	document.form1.board.options[change_idx].value = idx_value;
	document.form1.board.options[change_idx].text = idx_text;

	document.form1.board.selectedIndex = idx;
	document.form2.change.value="Y";
}

function MoveSave() {
	if (document.form2.change.value!="Y") {
		alert("���������� ���� �ʾҽ��ϴ�.");
		return;
	}
	if (!confirm("������ ������� �����Ͻðڽ��ϱ�?\n\n����� ���� ������ ���ʸ޴��� �����۾��������\n�ݿ��� �ȵ˴ϴ�.")) return;
	boards = "";
	for (i=0;i<=(document.form1.board.length-1);i++) {
		if (i==0) boards = document.form1.board.options[i].value;
		else boards+=","+document.form1.board.options[i].value;
	}
	document.form2.boards.value = boards;
	document.form2.cnt.value=document.form1.board.length;
	document.form2.submit();
}

//-->
</SCRIPT>
</head>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 oncontextmenu="return false" style="overflow-x:hidden;overflow-y:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false" onLoad="PageResize();">
<TABLE WIDTH="328" BORDER=0 CELLPADDING=0 CELLSPACING=0 style="table-layout:fixed;" id=table_body>
<TR>
	<TD>
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td><IMG SRC="images/community_list_sort_title.gif" ALT=""></td>
		<td width="100%" background="images/member_mailallsend_imgbg.gif">&nbsp;</td>
	</tr>
	</table>
	</TD>
</TR>
<TR>
	<TD style="padding:6pt;">
	<table cellpadding="0" cellspacing="0" width="100%">
	<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
	<input type=hidden name=mode>
	<tr>
		<td width="392" height="30">
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td width="270">
			<SELECT style="width:100%;" size="10" name=board class="select">
<?
			$sql = "SELECT * FROM tblboardadmin ORDER BY date DESC ";
			$result=mysql_query($sql,get_db_conn());
			$cnt=0;
			while($row=mysql_fetch_object($result)) {
				$cnt++;
				echo "<option value=\"".$row->board."\">".$row->board_name."</option>\n";
			}
			mysql_free_result($result);
?>
			</SELECT>
			</td>
			<td width="58" align=center><a href="JavaScript:move('up')"><img src="images/icon_tarea_short.gif" width="40" height="22" border="0" vspace="0"></a><br><a href="JavaScript:move('down')"><img src="images/icon_tarea_long.gif" width="40" height="22" border="0" vspace="2"></a>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td width="392"><img src="images/member_idsearch_line.gif" width="100%" height="1" border="0"></td>
	</tr>
	<tr>
		<td width="392" height="25" style="padding-top:6pt; padding-bottom:6pt;">
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td width="7"><img src="images/main_center_point.gif" width="4" height="11" border="0"></td>
			<td width="305">���� ���� �� [�����ϱ�] ��ư�� Ŭ���ؾ߸� ����˴ϴ�.</td>
		</tr>
		<tr>
			<td width="7"><img src="images/main_center_point.gif" width="4" height="11" border="0"></td>
			<td width="305" class="font_blue">����� ���� �����ν� ���� ������ ���� �ʽ��ϴ�.</td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td width="392"><img src="images/member_idsearch_line.gif" width="100%" height="1" border="0"></td>
	</tr>
	<tr>
		<td width="392" align="center"><a href="javascript:MoveSave();"><img src="images/bnt_apply.gif" width="76" height="28" border="0" vspace="10" border="0"></a></td>
	</tr>
	</form>
	<form name=form2 action="<?=$_SERVER[PHP_SELF]?>" method=post>
	<input type=hidden name=mode value="sequence">
	<input type=hidden name=boards>
	<input type=hidden name=cnt>
	<input type=hidden name=change value="N">
	</form>
	</table>
	</TD>
</TR>
</TABLE>
<?=$onload?>
</body>
</html>