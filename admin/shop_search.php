<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "sh-3";
$MenuCode = "shop";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$sql = "SELECT search_info FROM tblshopinfo ";
$result=mysql_query($sql,get_db_conn());
if($data=mysql_fetch_object($result)) {

}
mysql_free_result($result);

$search_info=$data->search_info;
$bestkeyword="";
$keyword="";
if(strlen($search_info)>0) {
	$temp=explode("=",$search_info);
	$cnt = count($temp);
	for ($i=0;$i<$cnt;$i++) {
		if (substr($temp[$i],0,12)=="BESTKEYWORD=") $bestkeyword=substr($temp[$i],12);	#�α�˻����� ��뿩��(Y/N)
		else if (substr($temp[$i],0,8)=="KEYWORD=") $keyword=substr($temp[$i],8);	#�α�˻��� ������� ����Ʈ
	}
}
if(strlen($bestkeyword)==0) $bestkeyword="Y";


$type=$_POST["type"];
$up_bestkeyword=$_POST["up_bestkeyword"];
$up_keyword=$_POST["up_keyword"];

if($type=="up") {
	if(strlen($up_bestkeyword)==0) $up_bestkeyword="Y";

	$search_info="";
	$search_info.="BESTKEYWORD=".$up_bestkeyword."=";
	$search_info.="KEYWORD=".$up_keyword;

	$sql="UPDATE tblshopinfo SET search_info='".$search_info."' ";
	mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload="<script>alert('��ǰ�˻� ���� ��� ������ �Ϸ�Ǿ����ϴ�.');</script>";
}

$bestkeyword="";
$keyword="";
if(strlen($search_info)>0) {
	$temp=explode("=",$search_info);
	$cnt = count($temp);
	for ($i=0;$i<$cnt;$i++) {
		if (substr($temp[$i],0,12)=="BESTKEYWORD=") $bestkeyword=substr($temp[$i],12);	#�α�˻����� ��뿩��(Y/N)
		else if (substr($temp[$i],0,8)=="KEYWORD=") $keyword=substr($temp[$i],8);	#�α�˻��� ������� ����Ʈ
	}
}
if(strlen($bestkeyword)==0) $bestkeyword="Y";

${"check_bestkeyword".$bestkeyword}="checked";

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
var IE = false ;
if (window.navigator.appName.indexOf("Explorer") !=-1) {
	IE = true;
}

function CheckForm() {
	if(document.form1.up_bestkeyword[0].checked==true && document.form1.up_keyword.value.length==0) {
		alert("�α�˻�� �Է��ϼ���.");
		document.form1.up_keyword.focus();
		return;
	}
	if(confirm("��ǰ�˻� ���� ��ɼ����� �Ͻðڽ��ϱ�?")) {
		document.form1.type.value="up";
		document.form1.submit();
	}
}

var restrictedSearchChars = /[\x25\x26\x2b\x3c\x3e\x3f\x2f\x5c\x27\x22\x3d\x20]|(\x5c\x6e)/g;

function validSearch(searchObj, e) {
	var searchVal = searchObj.value;
	var commacnt = 0;
	var key = window.event ? e.keyCode : e.which; 

	if(searchVal.charAt(searchVal.length-1) == ',' && (key == 44 || key == 32))
		return false;
	for(var i=0; i < searchVal.length; i++) {
		if(searchVal.charAt(i) == ',') {
			commacnt++;
		}
		if(commacnt >= 9) {
			alert("�±״� �ִ� 10������ �Է��� �� �ֽ��ϴ�.");
		 	return false; 
		 }
	}
	
	if (key != 0x2C && (key > 32 && key < 48) || (key > 57 && key < 65) || (key > 90 && key < 97)) 
		return false;
}

function check_searchvalidate(aEvent, input) {
	var keynum;
	if(typeof aEvent=="undefined") aEvent=window.event;
	if(IE) {
		keynum = aEvent.keyCode;
	} else {
		keynum = aEvent.which;
	}
	var ret = input.value;
	if(ret.match(restrictedSearchChars) != null ) {
		 ret = ret.replace(restrictedSearchChars, "");
		 input.value=ret;
	}
	//�޸��� �������� ������ �ϳ��� �����.
	re = /[\x2c][\x2c]+/g;
	if(ret.match(re) != null ){
		ret = ret.replace(re, ",");
		input.value=ret;
	}
}

function check_searchsvalidate(input) {
	input.value = validateSearchString(input.value);

	//�ߺ��Ǵ� �±� ����
	input.value = eliminateDuplicate(input.value);

	var searchcount = input.value.split(",").length;
	//�±� �� ����
	if(searchcount > 10) {
		alert("�α�˻���� �ִ� 10�� ���� �Է��� �����մϴ�.");
		input.value = absoluteSearchString(input.value, 10);			
		input.focus();
		return;
	}
	
	//�±��� ���� ����
	var bvalidate;
	var searchmaxlength = 100;
	bvalidate = isValidateSearchLength(input.value, searchmaxlength);
	if(!bvalidate) {
		alert("�α�˻���� 100�� �̻� �Է��� �� �����ϴ�.");
		input.focus();
		return;
	}
}

function absoluteSearchString(searchstring, maxcnt) {
	var valisearchs = validateSearchString(searchstring);
	var arraysearch = valisearchs.split(",");
	var searchnames = "";
	var absolutecnt = arraysearch.length;
	if(absolutecnt > maxcnt)
		absolutecnt = maxcnt;
		
	for(var i=0; i< absolutecnt; i++) {
		searchnames = searchnames + arraysearch[i] + ",";
	}
	searchnames = validateSearchString(searchnames);
	searchnames = searchnames.substring(0, searchnames.length-1);
	return searchnames;	
} 

function validateSearchString(searchstring) {
	var ret = searchstring.replace(restrictedSearchChars, "");

	//�޸��� �������� ������ �ϳ��� �����.
	re = /[\x2c]+/g;
	return ret.replace(re, ",");
}

function eliminateDuplicate(searchstring) {
	var valisearchs = validateSearchString(searchstring);
	var arraysearch = valisearchs.split(",");
	var searchnames = "";
	for(var i=0; i<arraysearch.length; i++) {
		for(var j=0; j<i; j++) {
			//�̹� ���� �ϴ� �±׶�� ����.
			if(arraysearch[j]==arraysearch[i]) {
				arraysearch[i]="";
			}
		}

		searchnames = searchnames + arraysearch[i] + ",";
	}
	searchnames = validateSearchString(searchnames);
	searchnames = searchnames.substring(0, searchnames.length-1);
	return searchnames;
}

function isValidateSearchLength(searchstring, maxlen) {
	var arraysearch = searchstring.split(",");
	for(var i=0; i<arraysearch.length; i++) {
		if(arraysearch[i].length > maxlen) {
			return false;
		}
	}
	return true;
}

</script>
<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
<tr>
	<td valign="top">
	<table cellpadding="0" cellspacing="0" width=100% style="table-layout:fixed">
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed"  background="images/con_bg.gif">
		<col width=198></col>
		<col width=10></col>
		<col width=></col>
		<tr>
			<td valign="top"  background="images/leftmenu_bg.gif">
			<? include ("menu_shop.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ���θ� � ���� &gt; <span class="2depth_select">��ǰ�˻� ���� ��ɼ���</span></td>
			</tr>
			</table>
		</td>
	</tr>   
	<tr>
        <td width="16"><img src="images/con_t_01.gif" width="16" height="16" border="0"></td>
        <td background="images/con_t_01_bg.gif"></td>
        <td width="16"><img src="images/con_t_02.gif" width="16" height="16" border="0"></td>
    </tr>
    <tr>
        <td width="16" background="images/con_t_04_bg1.gif"></td>
        <td bgcolor="#ffffff" style="padding:10px">






			<table cellpadding="0" cellspacing="0" width="100%">
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_productsearch_title.gif" border="0"></TD>
					</tr>
<tr>
<TD width="100%" background="images/title_bg.gif" height="21"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="3"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue"><p>��ǰ�˻��� �α�˻��� ���� ����� �����Ͻ� �� �ֽ��ϴ�.</p></TD>
					<TD background="images/distribute_07.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/distribute_08.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
					<TD><IMG SRC="images/distribute_10.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="20"></td></tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_productsearch_stitle2.gif" border="0"></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="180"><img src="images/icon_point2.gif" width="8" height="11" border="0">�α�˻��� ��뿩�� ����</TD>
					<TD class="td_con1"><input type=radio id="idx_bestkeyword1" name=up_bestkeyword value="Y" <?=$check_bestkeywordY?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_bestkeyword1>�α�˻��� ��� ���</label>&nbsp;&nbsp;&nbsp;<input type=radio id="idx_bestkeyword2" name=up_bestkeyword value="N" <?=$check_bestkeywordN?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_bestkeyword2>�α�˻��� ��� �̻��</label><br><span class=font_blue>* �α�˻���� ���θ��� Ư�� ��ǰ�� �����Ű���� �� ��� �����ϰ� ���˴ϴ�.</span></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="180"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� �α�˻��� �Է�</TD>
					<TD class="td_con1"><input type=text name=up_keyword value="<?=$keyword?>" size=50 onkeyup="check_searchvalidate(event, this);" onblur="check_searchsvalidate(this);" class="input"><br><span class=font_blue>* �α�˻��� ����� ����� ��쿡�� ����˴ϴ�. (�޸�","�� �����Ͽ� ����ϼ���)</span></TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			
			<tr><td height=10></td></tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm();"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
			</tr>
			</form>
			<tr><td height=20></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 HEIGHT=45 ALT=""></TD>
					<TD><IMG SRC="images/manual_title.gif" WIDTH=113 HEIGHT=45 ALT=""></TD>
					<TD width="100%" background="images/manual_bg.gif"></TD>
					<TD background="images/manual_bg.gif"></TD>
					<TD><IMG SRC="images/manual_top2.gif" WIDTH=18 HEIGHT=45 ALT=""></TD>
				</TR>
				<TR>
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" class="menual_bg" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- <b>�α�˻����?</b><br>
						<b>&nbsp;&nbsp;</b>���θ��� Ư�� ��ǰ�� �̿��ڵ鿡�� ���� �����ϰ��� �� ��� ���Ǹ�, �˻��� Ŭ���� �ش��ǰ�� �˻��˴ϴ�.</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top"><b>&nbsp;&nbsp;</b><IMG SRC="images/search_desc02.gif" border="0"></td>
					</tr>
					<tr>
						<td height="5" colspan="2"></td>
					</tr>
					</table>
					</TD>
					<TD background="images/manual_right1.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/manual_left2.gif" WIDTH=15 HEIGHT=8 ALT=""></TD>
					<TD COLSPAN=3 background="images/manual_down.gif"></TD>
					<TD><IMG SRC="images/manual_right2.gif" WIDTH=18 HEIGHT=8 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="50"></td></tr>
			</table>



</td>
        <td width="16" background="images/con_t_02_bg.gif"></td>
    </tr>
    <tr>
        <td width="16"><img src="images/con_t_04.gif" width="16" height="16" border="0"></td>
        <td background="images/con_t_04_bg.gif"></td>
        <td width="16"><img src="images/con_t_03.gif" width="16" height="16" border="0"></td>
    </tr>
    <tr><td height="20"></td></tr>
</table>




			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>
<?=$onload?>

<? INCLUDE "copyright.php"; ?>