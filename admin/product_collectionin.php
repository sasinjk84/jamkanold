<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

if(strlen($_ShopInfo->getId())==0){
	echo "<script>alert('�������� ��η� �����Ͻñ� �ٶ��ϴ�.');window.close();</script>";
	exit;
}

$productcode=$_POST["productcode"];
if (strlen($productcode)==0) {
	echo "<script>window.close();</script>";
	exit;
}
if(strlen($code)==12) {
	$codeA=substr($code,0,3);
	$codeB=substr($code,3,3);
	$codeC=substr($code,6,3);
	$codeD=substr($code,9,3);
}

$mode=$_POST["mode"];
$code=$_POST["code"];
$end=$_POST["end"];
$change=$_POST["change"];
$selcodes=$_POST["selcodes"];
$keyword=$_POST["keyword"];
$searchtype=$_POST["searchtype"];
if (strlen($searchtype)==0) $searchtype=0;

if ($mode=="insert") {
	$sql = "INSERT tblcollection SET ";
	$sql.= "productcode		= '".$productcode."', ";
	$sql.= "collection_list	= '".$selcodes."' ";
	mysql_query($sql,get_db_conn());
} else if ($mode=="modify") {
	$sql = "UPDATE tblcollection SET collection_list = '".$selcodes."' ";
	$sql.= "WHERE productcode = '".$productcode."' ";
	mysql_query($sql,get_db_conn());
	if($end=="Y") {
		echo "<script>
			try {
				opener.document.form1.mode.value=\"\";
				opener.document.form1.submit();
			} catch (e) {}
			window.close();</script>";
		exit;
	}
} else if ($mode=="delete") {
	if(strlen($selcodes)==0) {
		$sql = "DELETE FROM tblcollection WHERE productcode='".$productcode."'";
	} else {
		$sql = "UPDATE tblcollection SET collection_list = '".$selcodes."' ";
		$sql.= "WHERE productcode = '".$productcode."' ";
	}
	mysql_query($sql,get_db_conn());
}

?>

<html>
<head>
<meta http-equiv='Content-Type' content='text/html;charset=euc-kr'>
<title>���û�ǰ ���</title>
<link rel="stylesheet" href="style.css" type="text/css">
<STYLE type=text/css>
	#menuBar {}
	#contentDiv {WIDTH: 350;HEIGHT: 150;}
</STYLE>
<script type="text/javascript" src="codeinit.js.php"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--
var code="<?=$code?>";
var mode="<?=$mode?>";
var cnt=0;
function CodeProcessFun(_code) {
	if(_code=="out" || _code.length==0 || _code=="000000000000") {
		document.all["code_top"].style.background="#dddddd";
		selcode="";
		seltype="";

		if(_code!="out") {
			BodyInit('');
		} else {
			_code="";
		}
	} else {
		document.all["code_top"].style.background="#ffffff";
		BodyInit(_code);
	}

	if(mode.length>0 || cnt>0) {
		if(selcode.length==12 && selcode!="000000000000" && seltype.indexOf("X")!=-1) {
			document.form2.mode.value="";
			document.form2.code.value=selcode;
			document.form2.submit();
		}/* else {
			document.form2.mode.value="";
			document.form2.code.value="";
			document.form2.submit();
		}*/
	}
	cnt++;
}

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
	var oHeight = document.all.table_body.clientHeight + 65;

	window.resizeTo(oWidth,oHeight);
}

function InsertCollection() {
	if (document.form1.prcode.selectedIndex==-1) {
		alert("���û�ǰ�� ������ ��ǰ�� �����ϼ���.");
		document.form1.prcode.focus();
		return;
	}
	num = document.form1.comcode.length-1;
	if(num+1>=8){
		alert('���û�ǰ�� �ִ� 8������ ��ϰ����մϴ�.');
		return;
	}
	if (confirm("�ش� ��ǰ�� ���û�ǰ���� �����Ͻðڽ��ϱ�?")){
		temp = "";
		for (i=0;i<=num;i++) {
			if(document.form1.comcode.options[i].value == document.form1.prcode.options[document.form1.prcode.selectedIndex].value){
				alert('�̹� ��ϵ� ��ǰ�Դϴ�.');
				return;
			} 
			if (i==0) temp = document.form1.comcode.options[i].value;
			else temp+=","+document.form1.comcode.options[i].value;
		}
		if(num==-1) temp=document.form1.prcode.options[document.form1.prcode.selectedIndex].value;
		else temp+=","+document.form1.prcode.options[document.form1.prcode.selectedIndex].value;
		document.form1.selcodes.value = temp;
		document.form1.submit();
	}
}

var shop="layer1";
var ArrLayer = new Array ("layer1","layer2");
function ViewLayer(gbn){
	if(document.all){
		for(i=0;i<2;i++) {
			if (ArrLayer[i] == gbn)
				document.all[ArrLayer[i]].style.display="";
			else
				document.all[ArrLayer[i]].style.display="none";
		}
	} else if(document.getElementById){
		for(i=0;i<2;i++) {
			if (ArrLayer[i] == gbn)
				document.getElementByld[ArrLayer[i]].style.display="";
			else
				document.getElementByld[ArrLayer[i]].style.display="none";
		}
	} else if(document.layers){
		for(i=0;i<2;i++) {
			if (ArrLayer[i] == gbn)
				document.layers[ArrLayer[i]].display="";
			else
				document.layers[ArrLayer[i]].display="none";
		}
	}
	shop=gbn;
	PageResize();
}

function CheckSearch() {
	document.form1.mode.value = "";
	if (document.form1.keyword.value.length<2) {
		if(document.form1.keyword.value.length==0) alert("�˻�� �Է��ϼ���.");
		else alert("�˻���� 2���� �̻� �Է��ϼž� �մϴ�."); 
		document.form1.keyword.focus();
		return;
	} else {
		document.form1.submit();
	}
}

function CheckKeyPress(){
	ekey=event.keyCode;
	if (ekey==13) {
		CheckSearch()
	}
}

function Delete() {
	if(document.form1.comcode.selectedIndex !=-1) {
		if(!confirm("�����Ͻ� ��ǰ�� ���û�ǰ���� �����Ͻðڽ��ϱ�?")) return;
		document.form1.mode.value="delete";
		codes = "";
		num = document.form1.comcode.length-1;
		delcode=document.form1.comcode.options[document.form1.comcode.selectedIndex].value;
		j=-1;
		for (i=0;i<=num;i++) {
			if(delcode!=document.form1.comcode.options[i].value){
				j++;
				if (j==0) codes = document.form1.comcode.options[i].value;
				else codes+=","+document.form1.comcode.options[i].value;
			}
		}
		document.form1.selcodes.value = codes;
		document.form1.submit();
	} else {
		alert('�����Ͻ� ��ǰ�� �����ϼ���');
	}
}

function move(gbn) {
	change_idx = document.form1.comcode.selectedIndex;
	if (change_idx<0) {
		alert("������ ������ ��ǰ�� �����ϼ���.");
		return;
	}
	if (gbn=="up" && change_idx==0) {
		alert("�����Ͻ� ��ǰ�� ���̻� ���� �̵����� �ʽ��ϴ�.");
		return;
	}
	if (gbn=="down" && change_idx==(document.form1.comcode.length-1)) {
		alert("�����Ͻ� ��ǰ�� ���̻� �Ʒ��� �̵����� �ʽ��ϴ�.");
		return;
	}
	if (gbn=="up") idx = change_idx-1;
	else idx = change_idx+1;

	idx_value = document.form1.comcode.options[idx].value;
	idx_text = document.form1.comcode.options[idx].text;

	document.form1.comcode.options[idx].value = document.form1.comcode.options[change_idx].value;
	document.form1.comcode.options[idx].text = document.form1.comcode.options[change_idx].text;

	document.form1.comcode.options[change_idx].value = idx_value;
	document.form1.comcode.options[change_idx].text = idx_text;

	document.form1.comcode.selectedIndex = idx;
	document.form1.change.value="Y";
}

function move_save() {
	if (document.form1.change.value=="Y") {
		if (!confirm("������ ������� �����Ͻðڽ��ϱ�?")) return;
	}
	codes = "";
	for (i=0;i<=(document.form1.comcode.length-1);i++) {
		if (i==0) codes = document.form1.comcode.options[i].value;
		else codes+=","+document.form1.comcode.options[i].value;
	}
	document.form1.selcodes.value = codes;
	document.form1.mode.value="modify";
	document.form1.end.value="Y";
	document.form1.submit();
}

//-->
</SCRIPT>
</head>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 oncontextmenu="return false" style="overflow-x:hidden;overflow-y:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false" onLoad="PageResize();">
<TABLE WIDTH="400" BORDER=0 CELLPADDING=0 CELLSPACING=0 style="table-layout:fixed;" id=table_body>
<TR>
	<TD>
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td><img src="images/newtitle_icon.gif" border="0"></td>
		<td width="100%" background="images/member_mailallsend_imgbg.gif"><font color=FFFFFF><b>���û�ǰ ����</b></font></td>
		<td align=right><img src="images/member_mailallsend_img2.gif" width="20" height="31" border="0"></td>
	</tr>
	</table>
	</TD>
</TR>
<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
<input type=hidden name=end>
<input type=hidden name=code value="<?=$code?>">
<input type=hidden name=productcode value="<?=$productcode?>">
<input type=hidden name=change>
<input type=hidden name=selcodes>
<TR>
	<TD>
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
	<TR>
		<TD background="images/table_top_line.gif" colspan="2"></TD>
	</TR>
	<tr>
		<TD class="table_cell" width="110"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ǰ���⼱��</TD>
		<TD class="td_con1"><span style="letter-spacing:-0.5pt;"><input type=radio id="idx_searchtype1" name=searchtype value="0" onclick="ViewLayer('layer1')" <?if($searchtype=="0") echo "checked";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_searchtype1>ī�װ��� ��ǰ ����</label> <input type=radio id="idx_searchtype2" name=searchtype value="1" onclick="ViewLayer('layer2')" <?if($searchtype=="1") echo "checked";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_searchtype2>�˻����� ��ǰ ����</label></span></TD>
	</tr>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	</TABLE>
	</TD>
</TR>
<tr>
	<TD>
	<div id=layer1 style="margin-left:0;display:hide; display:<?=($searchtype=="0"?"block":"none")?> ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
	<DIV class=MsgrScroller id=contentDiv style="width=100%;height:160px;OVERFLOW-x: auto; OVERFLOW-y: auto;" oncontextmenu="return false" style="overflow-x:hidden;overflow-y:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false">
	<DIV id=bodyList>
	<table border=0 cellpadding=0 cellspacing=0 width="100%" height="100%" bgcolor=FFFFFF>
	<tr>
		<td height=18><IMG SRC="images/directory_root.gif" border=0 align=absmiddle> <span id="code_top" style="cursor:default;" onmouseover="this.className='link_over'" onmouseout="this.className='link_out'" onclick="ChangeSelect('out');">�ֻ��� ī�װ�</span></td>
	</tr>
	<tr>
		<!-- ��ǰī�װ� ��� -->
		<td id="code_list" nowrap valign=top></td>
		<!-- ��ǰī�װ� ��� �� -->
	</tr>
	</table>
	</DIV>
	</DIV>
	</div>
	<div id=layer2 style="margin-left:0;display:hide; display:<?=($searchtype=="1"?"block":"none")?> ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
	<tr>
		<TD class="table_cell" width="110"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ǰ�� �Է�</TD>
		<TD class="td_con1">
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td width="99%"><input type=text name=keyword size=30 value="<?=$keyword?>" onKeyDown="CheckKeyPress()" class="input" style="width:100%;"></td>
			<td width="1%"><p align="right"><a href="javascript:CheckSearch();"><img src="images/btn_search2.gif" width="50" height="25" border="0" align=absmiddle hspace="2"></a></td>
		</tr>
		</table>
		</TD>
	</tr>
	</table>
	</div>
	</TD>
</tr>
<TR>
	<TD background="images/table_top_line.gif"></TD>
</TR>
<TR>
	<TD width="100%" align=center><img src="images/product_collectionlist_img1.gif" width="80" height="23" border="0"></TD>
</TR>
<TR>
	<TD width="100%" align=center><select name=prcode size=6 style="width:100%;" class="select">
<?
	if (($searchtype=="0" && strlen($code)==12) || ($searchtype=="1" && strlen($keyword)>3)) {
		$sql = "SELECT productcode,productname,quantity,display FROM tblproduct ";
		$sql.= "WHERE 1=1 AND social_chk='N'";
		if($searchtype=="0") $sql.= "AND productcode LIKE '".$code."%' ";
		else $sql.= "AND productname LIKE '%".$keyword."%' ";
		$sql.= "ORDER BY productname";
		$result = mysql_query($sql,get_db_conn());

		$count = 0;
		while ($row = mysql_fetch_object($result)) {
			$count++;
			$quantity=(strlen($row->quantity)==0)?"������":$row->quantity."��";
			$display=($row->display=="Y")?"�Ǹ���":"�Ǹ�����";
			if ($prcode == $row->productcode) {
				echo "<option selected value=\"".$row->productcode."\">".$row->productname." [���:".$quantity." ,".$display."]</option>\n";
				$productname=$row->productname;
			} else {
				echo "<option value=\"".$row->productcode."\">".$row->productname." [���:".$quantity." ,".$display."]</option>\n";
			}
		}
		mysql_free_result($result);
	}
?>
	</select>
	</TD>
</TR>
<TR><TD height="5"></TD></TR>
<TR>
	<TD width="100%" align=center><a href="javascript:InsertCollection();"><img src="images/btn_collectionlist.gif" border="0" vspace="3"></a></TD>
</TR>
<TR><TD height="10"></TD></TR>
<TR>
	<TD width="100%">
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 height="100%">
	<TR>
		<TD width="100%" height="100%">
		<select name=comcode size=7 style="width:100%;" class="select">
<?
		$sql = "SELECT collection_list FROM tblcollection ";
		$sql.= "WHERE productcode = '".$productcode."' ";
		$result = mysql_query($sql,get_db_conn());
		if($row = mysql_fetch_object($result)){
			$cnt_prcode=$row->collection_list;
			$coll_prcode=ereg_replace(',','\',\'',$cnt_prcode);
		}
		mysql_free_result($result);
		$mode="insert";
		$count=1;
		if(strlen($coll_prcode)>0){
			$sql = "SELECT productname,productcode,quantity,display FROM tblproduct ";
			$sql.= "WHERE productcode IN ('".$coll_prcode."')";
			$result = mysql_query($sql,get_db_conn());
			while ($row = mysql_fetch_object($result)) {
				$arraycode[$row->productcode]=$row->productname;
				$arrayquantity[$row->productcode]=(strlen($row->quantity)==0)?"������":$row->quantity."��";
				$arraydisplay[$row->productcode]=($row->display=="Y")?"�Ǹ���":"�Ǹ�����";
			}
			$viewproduct = explode(",",$cnt_prcode);
			$cnt =count($viewproduct);
			for($i=0;$i<$cnt;$i++){
			   echo "<option value=\"".$viewproduct[$i]."\">".($i+1).".".$arraycode[$viewproduct[$i]]." [���:".$arrayquantity[$viewproduct[$i]]." ,".$arraydisplay[$viewproduct[$i]]."]</option>\n";
			}
			$mode="modify";
		}
?>
		</select>
		</TD>
		<TD noWrap align=middle width=50>
		<table cellpadding="0" cellspacing="0" width="34">
		<TR>
			<TD align=middle><A href="JavaScript:move('up');"><IMG src="images/code_up.gif" align=absMiddle border=0 width="40" height="30" vspace="2"></A></td>
		</tr>
		<TR>
			<TD align=middle><A href="JavaScript:move('down');"><IMG src="images/code_down.gif" align=absMiddle border=0 width="40" height="30" vspace="2"></A></td>
		</tr>
		<TR>
			<TD align=middle><A href="JavaScript:Delete();"><IMG src="images/code_delete.gif" align=absMiddle border=0 width="40" height="30" vspace="2"></A></td>
		</tr>
		</table>
		</TD>
	</TR>
	</TABLE>
	</TD>
</TR>
<input type=hidden name=mode value="<?=$mode?>">
<TR><TD height="10"></TD></TR>
<TR>
	<TD align=center><a href="javascript:move_save();"><img src="images/btn_save1.gif" width="60" height="18" border="0" vspace="0" border=0></a>&nbsp;&nbsp;<a href="javascript:window.close();"><img src="images/btn_close.gif" width="36" height="18" border="0" vspace="0" border=0 hspace="2"></a></a></TD>
</TR>
<TR><TD height="10"></TD></TR>
</form>
<form name=form2 action="<?=$_SERVER[PHP_SELF]?>" method=post>
<input type=hidden name=mode value="changecode">
<input type=hidden name=code>
<input type=hidden name=productcode value="<?=$productcode?>">
</form>
</TABLE>
<?
$sql = "SELECT * FROM tblproductcode WHERE type not in('T','TX','TM','TMX','S','SX','SM','SMX') ";
$sql.= "ORDER BY sequence DESC ";
include ("codeinit.php");
?>

</body>
</html>