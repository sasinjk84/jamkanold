<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "pr-1";
$MenuCode = "product";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$type=$_POST["type"];
$code=$_POST["code"];
$codes=$_POST["codes"];

$exposed_list_num = $_shopdata->exposed_list;
if(strlen($exposed_list_num)==0) $exposed_list_num=",0,2,3,4,5,6,7,19,";

if ($type=="insert" || $type=="delete" || $type=="sequence") {
	if ($type=="insert") {
		$exposed_list_num = $exposed_list_num.$code.",";
		$onload="<script>alert('�ش� ���� �׸��� �߰��Ͽ����ϴ�.');</script>";
	} else if ($type=="delete") {
		$exposed_list_num = ereg_replace(",".$code.",",",",$exposed_list_num);
		$onload="<script>alert('�ش� ���� �׸��� �����Ͽ����ϴ�.');</script>";
	} else if ($type=="sequence") {
		$exposed_list_num=$codes;
		$onload="<script>alert('�ش� ���� �׸��� ������ �����Ͽ����ϴ�.');</script>";
	}
	$sql = "UPDATE tblshopinfo SET exposed_list = '".$exposed_list_num."' ";
	mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
}

$exposed_list_name = array("����ȸ��","������","���߰���","�ǸŰ���","������","Ư�̻���","����(�����Ұ�)","�ɼ�(�����Ұ�)","��ǰ��","�ؿ� ȭ�� ����","�𵨸�","�����","��������ǽ���1","��������ǽ���2","��������ǽ���3","��������ǽ���4","��������ǽ���5","�귣��","�����ڵ�","��Ű��(�����Ұ�)","��Ÿ","����","��ۺ�","����","�뿩����","ȸ������","��ۼ���");

$cnt = count($exposed_list_name);

$exposed_list_num2=substr($exposed_list_num,1,-1);
$ar_exposed_list_num = explode(",",$exposed_list_num2);
$cnt2 = count($ar_exposed_list_num);

if(strlen($blanknum)==0) $blanknum=1;
$exposed_list_num3=" ".$exposed_list_num;
while($num = strpos($exposed_list_num3,",O")){
	$tempnum=ereg_replace(",","",substr($exposed_list_num3,$num+2,2))+1;
	$exposed_list_num3=substr($exposed_list_num3,$num+2);
	if($tempnum>$blanknum) $blanknum=$tempnum;	
}

for($i=1;$i<$blanknum;$i++) $exposed_list_name["O$i"]="����(�� ��ĭ)";

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function SendMode(mode) {
	if (document.form1.outexp.selectedIndex==-1 && mode=="insert") {
		alert("���� �׸� �߰��� �׸��� �����ϼ���.");
		return;
	} else if(document.form1.inexp.selectedIndex==-1 && mode=="delete") {
		alert("���� �׸񿡼� ������ �׸��� �����ϼ���.");
		return;
	}
	if (mode=="insert") {
		if (confirm("���� �׸��� �߰��Ͻðڽ��ϱ�?")) {
			document.form1.type.value=mode;
			document.form1.code.value=document.form1.outexp.options[document.form1.outexp.selectedIndex].value;
			document.form1.submit();
		}
	} else if (mode=="delete"){
		document.form1.code.value=document.form1.inexp.options[document.form1.inexp.selectedIndex].value;
		if (document.form1.code.value!=6 && document.form1.code.value!=7 && document.form1.code.value!=19) {
			if (confirm("���� �׸��� �����Ͻðڽ��ϱ�?")) {
				document.form1.type.value=mode;
				document.form1.submit();
			}
		} else if (document.form1.code.value==6){
			alert("������ ���� �Ұ����մϴ�.");
			return;
		} else if (document.form1.code.value==7){
			alert("�ɼ��� ���� �Ұ����մϴ�.");
			return;
		} else if (document.form1.code.value==19){
			alert("��Ű���� ���� �Ұ����մϴ�.");
			return;
		}
	}
}

function move(gbn) {
	change_idx = document.form1.inexp.selectedIndex;
	if (change_idx<0) {
		alert("������ ������ �׸��� �����ϼ���.");
		return;
	}
	if (gbn=="up" && change_idx==0) {
		alert("�����Ͻ� �׸��� ���̻� ���� �̵����� �ʽ��ϴ�.");
		return;
	}
	if (gbn=="down" && change_idx==(document.form1.inexp.length-1)) {
		alert("�����Ͻ� �׸��� ���̻� �Ʒ��� �̵����� �ʽ��ϴ�.");
		return;
	}
	if (gbn=="up") idx = change_idx-1;
	else idx = change_idx+1;

	idx_value = document.form1.inexp.options[idx].value;
	idx_text = document.form1.inexp.options[idx].text;

	document.form1.inexp.options[idx].value = document.form1.inexp.options[change_idx].value;
	document.form1.inexp.options[idx].text = document.form1.inexp.options[change_idx].text;

	document.form1.inexp.options[change_idx].value = idx_value;
	document.form1.inexp.options[change_idx].text = idx_text;

	document.form1.inexp.selectedIndex = idx;
	document.form2.change.value="Y";
}

function MoveSave() {
	if (document.form2.change.value!="Y") {
		alert("���������� ���� �ʾҽ��ϴ�.");
		return;
	}
	if (!confirm("������ ������� �����Ͻðڽ��ϱ�?")) return;
	codes = "";
	for (i=0;i<=(document.form1.inexp.length-1);i++) {
		codes+=","+document.form1.inexp.options[i].value;
	}
	codes+=",";
	document.form2.codes.value = codes;
	document.form2.submit();
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
			<? include ("menu_product.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ��ǰ���� &gt;ī�װ�/��ǰ���� &gt; <span class="2depth_select">��ǰ ���� �������</span></td>
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





			<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/product_detaillist_title.gif"  ALT=""></TD>
					</tr><tr>
					<TD width="100%" background="images/title_bg.gif" height=21></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="3"></td></tr>
			<tr>
				<td style="padding-bottom:3pt;">
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">��ǰ ������������ ����Ǵ� ����ǰ��  ���׸� ������ ������ �� �ֽ��ϴ�.</TD>
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
			<input type=hidden name=code>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=3 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" align="center"><b>������ �������</b></TD>
					<TD class="table_cell1" align="center" width="50">&nbsp;</TD>
					<TD class="table_cell1" align="center" background="images/blueline_bg.gif"><b><span class="font_blue">���� �������� �׸� </span></b></TD>
				</TR>
				<TR>
					<TD colspan="3" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD align="center" valign="top" style="padding:5pt;">
					<select name=outexp size=17 style="WIDTH:310px" size=17 class="select">
<?
					for($i=0;$i<$cnt;$i++){
						if(!ereg(",".$i.",",$exposed_list_num)){
							echo "<option value=\"".$i."\">".$exposed_list_name[$i]."\n";
						}
					}
					echo "<option value=\"O".$blanknum."\">����(�� ��ĭ)\n";
?>
					</select>
					</TD>
					<TD class="td_con1" align="center" width="50"><a href="javascript:SendMode('insert');"><img src="images/icon_nero1.gif" width="50" height="46" border="0"></a><br><br><a href="javascript:SendMode('delete');"><img src="images/icon_nero2.gif" width="50" height="46" border="0" vspace="10"></a></TD>
					<TD class="td_con1" align="center" valign="top"  style="padding:5pt;">
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<TR>
						<TD width="100%">
						<select name=inexp size=17 style="WIDTH:310px" class="select">
<?
						for($i=0;$i<$cnt2;$i++){
							echo "<option value=\"".$ar_exposed_list_num[$i]."\">".$exposed_list_name[$ar_exposed_list_num[$i]]."\n";
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
							<TD align=middle><IMG src="images/code_sort.gif" width="40" height="30"></td>
						 </tr>
						<TR>
							<TD align=middle><A href="JavaScript:move('down');"><IMG src="images/code_down.gif" align=absMiddle border=0 width="40" height="30" vspace="2"></A></td>
						</tr>
						<tr>
							<td height="20"></td>
						</tr>
						<TR>
							<TD align=middle><A href="JavaScript:MoveSave();"><IMG src="images/code_save.gif" align=absMiddle border=0 width="40" height="30" vspace="2"></A></td>
						</tr>
						</table>
						</TD>
					</TR>
					</TABLE>
					</TD>
				</TR>
				<TR>
					<TD background="images/table_top_line.gif" colspan="3"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			</form>
			<form name=form2 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type value="sequence">
			<input type=hidden name=codes>
			<input type=hidden name=change value="N">
			</form>
			<tr>
				<td height="30"></td>
			</tr>
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
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<col width=20></col>
					<col width=></col>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">��ǰ���� ���� ����</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- �������� ���� �� [�����ϱ�] �� Ŭ���ؾ߸� ����˴ϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- ��ǰ���� ���ø� ���ý� ���ݰ����� �������Ÿ� ����� ��� ��ǰ�������� �������� �ʽ��ϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- ��ǰ���� ���⼳���� �߾ �ش� ���忡 ���� ������ �Է����� ������ ��µ��� �ʽ��ϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- ��ǰ���� [�����ϱ�]�� ������¿��� ����µǸ� ���忡 �Է��� ������ �������� �ʽ��ϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- ����(�� ��ĭ)�� ����� ������� �����Ҷ� ����մϴ�.</td>
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