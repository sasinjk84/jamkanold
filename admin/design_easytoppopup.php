<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

if(strlen($_ShopInfo->getId())==0){
	echo "<script>alert('�������� ��η� �����Ͻñ� �ٶ��ϴ�.');window.close();</script>";
	exit;
}

$mode=$_POST["mode"];
$menus=$_POST["menus"];
if($mode=="change" && strlen($menus)>0) {
	$sql = "UPDATE tbldesign SET menu_list='".$menus."' ";
	mysql_query($sql,get_db_conn());
	DeleteCache("tbldesign.cache");
	echo "<html></head><body onload=\"alert('��ܸ޴� ���� ������ �Ϸ�Ǿ����ϴ�.');opener.location.href=opener.location.href;window.close()\"></body></html>";exit;
}

$sql = "SELECT * FROM tbldesign ";
$result=mysql_query($sql,get_db_conn());
$row=mysql_fetch_object($result);
if($row->top_set=="Y") {
	$menu_list=$row->menu_list;
} else {
	echo "<html></head><body onload=\"alert('�̹��� ��� �� �޴������� �����մϴ�.');window.close()\"></body></html>";exit;
}
mysql_free_result($result);
?>

<html>
<head>
<meta http-equiv='Content-Type' content='text/html;charset=euc-kr'>
<title>���θ� ��ܸ޴� ��������</title>
<link rel="stylesheet" href="style.css" type="text/css">
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
	var oHeight = document.all.table_body.clientHeight + 72;

	window.resizeTo(oWidth,oHeight);
}

function move(gbn) {
	change_idx = document.form1.menu.selectedIndex;
	if (change_idx<0) {
		alert("������ ������ ��ܸ޴��� �����ϼ���.");
		return;
	}
	if (gbn=="up" && change_idx==0) {
		alert("�����Ͻ� ��ܸ޴��� ���̻� ���� �̵����� �ʽ��ϴ�.");
		return;
	}
	if (gbn=="down" && change_idx==(document.form1.menu.length-1)) {
		alert("�����Ͻ� ��ܸ޴��� ���̻� �Ʒ��� �̵����� �ʽ��ϴ�.");
		return;
	}
	if (gbn=="up") idx = change_idx-1;
	else idx = change_idx+1;

	idx_value = document.form1.menu.options[idx].value;
	idx_text = document.form1.menu.options[idx].text;

	document.form1.menu.options[idx].value = document.form1.menu.options[change_idx].value;
	document.form1.menu.options[idx].text = document.form1.menu.options[change_idx].text;

	document.form1.menu.options[change_idx].value = idx_value;
	document.form1.menu.options[change_idx].text = idx_text;

	document.form1.menu.selectedIndex = idx;
	document.form1.change.value="Y";
}

function move_save() {
	if (document.form1.change.value!="Y") {
		alert("���� ������ ���� �ʾҽ��ϴ�.");
		return;
	}
	if (!confirm("������ ������� �����Ͻðڽ��ϱ�?")) return;
	menus = "";
	for (i=0;i<document.form1.menu.length;i++) {
		if (i==0) menus = document.form1.menu.options[i].value;
		else menus+=","+document.form1.menu.options[i].value;
	}
	document.form1.menus.value=menus;
	document.form1.mode.value="change";
	document.form1.submit();
}

//-->
</SCRIPT>
</head>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 oncontextmenu="return false" style="overflow-x:hidden;overflow-y:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false" onLoad="PageResize();">
<table border=0 cellpadding=0 cellspacing=0 width=352 style="table-layout:fixed;" id=table_body>
<TR>
	<TD>
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td><p><img src="images/design_easytop_win_title.gif" width="212" height="31" border="0"></p></td>
		<td width="100%" background="images/member_mailallsend_imgbg.gif"><p>&nbsp;</p></td>
		<td><p align="right"><img src="images/member_mailallsend_img2.gif" width="20" height="31" border="0"></p></td>
	</tr>
	</table>
	</TD>
</TR>
<form name=form1 method=post action="<?=$_SERVER[PHP_SELF]?>">
<input type=hidden name=mode>
<input type=hidden name=change>
<input type=hidden name=menus>
<tr>
	<TD style="padding:6pt;">
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="99%"><select name=menu size=13 style="WIDTH: 100%" class="select">
<?
		$menu_all_name=array(1=>"����������",2=>"ȸ��Ұ�",3=>"�̿�ȳ�",4=>"ȸ������/����",5=>"��ٱ���",6=>"�ֹ���ȸ",7=>"�α���",8=>"�α׾ƿ�",9=>"ȸ��Ż��",10=>"����������",11=>"������",12=>"�űԻ�ǰ",13=>"�α��ǰ",14=>"��õ��ǰ",15=>"Ư����ǰ",16=>"�߰��̹���1",17=>"�߰��̹���2",18=>"�߰��̹���3",19=>"�߰��̹���4",20=>"�߰��̹���5");

		$arr_menu_list=explode(",",$menu_list);
		for($i=0;$i<count($arr_menu_list);$i++) {
			echo "<option value=\"".$arr_menu_list[$i]."\">".($i+1).". ".$menu_all_name[$arr_menu_list[$i]]."</option>\n";
		}
?>
			</select></td>
		<td width="1%">
		<table cellpadding="0" cellspacing="0" width="34">
		<TR>
			<TD align=middle style="padding-left:2pt;"><p align="right"><a href="JavaScript:move('up')"><IMG src="images/code_up.gif" align=absMiddle border=0 width="40" height="30" vspace="2"></A></td>
		</tr>
		<TR>
			<TD align=middle><p align="right"><IMG src="images/code_sort.gif" width="40" height="30"></td>
		</tr>
		<TR>
			<TD align=middle><p align="right"><a href="JavaScript:move('down')"><IMG src="images/code_down.gif" align=absMiddle border=0 width="40" height="30" vspace="2"></A></td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	</TD>
</tr>
<TR>
	<TD><p align="center"><a href="javascript:move_save();"><img src="images/btn_save.gif" width="55" height="18" border="0" vspace="5" border=0></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:self.close()"><img src="images/btn_close.gif" width="36" height="18" border="0" vspace="5" border=0 hspace="2"></a></p></TD>
</TR>
</form>
</table>
</body>
</html>