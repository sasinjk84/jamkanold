<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "or-1";
$MenuCode = "order";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$excel_ok=$_shopdata->excel_ok;
$excel_info=$_shopdata->excel_info;

$mode=$_POST["mode"];
$etccode=$_POST["etccode"];
$up_excel_ok=$_POST["up_excel_ok"];
$codes=$_POST["codes"];
$change=$_POST["change"];

if($mode=="insert" || $mode=="delete" || $mode=="sequence") {
	if($mode=="insert" && strlen($etccode)>0) {
		$excel_info=$excel_info.$etccode.",";
		$onload="<script>alert(\"�����Ͻ� �׸��� �ٿ�Ǵ� �ֹ��� �׸� �߰��Ͽ����ϴ�.\");</script>";
	} else if($mode=="delete" && strlen($etccode)>0) {
		$excel_info=str_replace(",".$etccode.",",",",$excel_info);
		$onload="<script>alert(\"�����Ͻ� �׸��� �ٿ�Ǵ� �ֹ��� �׸񿡼� �����Ͽ����ϴ�.\");</script>";
	} else if($mode=="sequence") {
		$excel_info=$codes;
		$onload="<script>alert(\"�ٿ�Ǵ� �ֹ��� �׸� ������ �����Ͽ����ϴ�.\");</script>";
	}
	if(ereg(",24,"," ".$excel_info)) {
		$pattern = array("(,21,)","(,22,)","(,23,)","(,25,)","(,26,)");
		$replacement = array(",",",",",",",",",");
		$excel_info=preg_replace($pattern,$replacement,$excel_info); 
	}
	$sql = "UPDATE tblshopinfo SET excel_info='".$excel_info."' ";
	mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
} else if($mode=="exceltype" && strlen($up_excel_ok)>0) {
	$sql = "UPDATE tblshopinfo SET excel_ok='".$up_excel_ok."' ";
	mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$excel_ok=$up_excel_ok;
	$onload="<script>alert(\"�ֹ��� ��������� �����Ͽ����ϴ�.\");</script>";
}

$excel_name = array(
"����",
"�ֹ���",
"�ֹ��� ��ȭ(XXXXXXXX)",
"�ֹ��� ��ȭ(XX-XXXX-XXXX)",
"�̸���",
"�ֹ�ID/�ֹ���ȣ",
"�������",
"��������",
"�������(����)",
"�ֹ��ݾ�",
"ó������",
"�޴»��",
"��ȭ��ȣ �����ȭ",
"��ȭ��ȣ(XXXXXXXX)",
"�����ȭ(XXXXXXXX)",
"��ȭ��ȣ(XX-XXXX-XXXX)",
"�����ȭ(XX-XXXX-XXXX)",
"�����ȣ(XXXXXX)",
"�����ȣ(XXX-XXX)",
"�ּ�",
"���޻���",
"��ǰ��",
"�ɼ�(Ư¡����)",
"����",
"��ǰ��1-����-�ɼ� ^ ��ǰ��2-����-�ɼ�",
"��ǰ����",
"��ǰ ������",
"��۷�",
"���������",
"�Ա���",
"�����",
"�ֹ����ø޸�(������)",
"���˸���",
"��ǰ��1-����-�ɼ�^��ǰ��2-����-�ɼ�",
"�����ȣ",
"�ŷ���ȣ",
"��ǰ�ڵ�",
"�������(ī�峻��)",
"�ɼ�",
"Ư¡",
"��ǰ��(�±����ž���)",
"���޻���(�±����ž���)",
"����(�ú��� ǥ��)",
"��ǰ�� ó������",
"��ǰ�� �ֹ��޼���",
"��ǰ�� �����",
"�����ڵ�",
"�ŷ�ó����");

$cnt = count($excel_name);
$excel_info2=substr($excel_info,1,-1);
$arexcel_info = explode(",",$excel_info2);
$cnt2 = count($arexcel_info);

if(strlen($blank_info)==0) $blank_info=1;
$excel_info3=" ".$excel_info;
while($num = strpos($excel_info3,",O")) {
	$temp_info=ereg_replace(",","",substr($excel_info3,$num+2,2))+1;
	$excel_info3=substr($excel_info3,$num+2);
	if($temp_info>$blank_info) $blank_info=$temp_info;
}

for($i=1;$i<$blank_info;$i++) $excel_name["O$i"]="����(�� ��ĭ)";

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm(form) {
	if(form.up_excel_ok[0].checked==false && form.up_excel_ok[1].checked==false) {
		alert("�ֹ��� ��� ������ �����ϼ���.");
		form.up_excel_ok[1].focus();
		return;
	}
	form.mode.value="exceltype";
	form.submit();
}

function SendMode(mode) {
	if (document.form1.noest.selectedIndex==-1 && mode=="insert") {
		alert("�ٿ� ������ �ֹ��� �׸��� �����ϼ���.");
		return;
	} else if(document.form1.est.selectedIndex==-1 && mode=="delete") {
		alert("�ٿ�Ǵ� �ֹ��� �׸��� �����ϼ���.");
		return;
	}
	if (mode=="insert") {
		if (confirm("���õ� �ֹ��� �׸��� �ٿ�Ǵ� �ֹ��� �׸� �߰��Ͻðڽ��ϱ�?")) {
			document.form1.mode.value=mode;
			document.form1.etccode.value=document.form1.noest.options[document.form1.noest.selectedIndex].value;
			document.form1.submit();
		}
	} else if (mode=="delete"){
		document.form1.etccode.value=document.form1.est.options[document.form1.est.selectedIndex].value;
		if (confirm("���õ� �ֹ��� �׸��� �����Ͻðڽ��ϱ�?")) {
			document.form1.mode.value=mode;
			document.form1.submit();
		}
	}
}

function move(gbn) {
	change_idx = document.form1.est.selectedIndex;
	if (change_idx<0) {
		alert("������ ������ �ֹ��� �׸��� �����ϼ���.");
		return;
	}
	if (gbn=="up" && change_idx==0) {
		alert("�����Ͻ� �ֹ��� �׸��� ���̻� ���� �̵����� �ʽ��ϴ�.");
		return;
	}
	if (gbn=="down" && change_idx==(document.form1.est.length-1)) {
		alert("�����Ͻ� �ֹ��� �׸��� ���̻� �Ʒ��� �̵����� �ʽ��ϴ�.");
		return;
	}
	if (gbn=="up") idx = change_idx-1;
	else idx = change_idx+1;

	idx_value = document.form1.est.options[idx].value;
	idx_text = document.form1.est.options[idx].text;

	document.form1.est.options[idx].value = document.form1.est.options[change_idx].value;
	document.form1.est.options[idx].text = document.form1.est.options[change_idx].text;

	document.form1.est.options[change_idx].value = idx_value;
	document.form1.est.options[change_idx].text = idx_text;

	document.form1.est.selectedIndex = idx;
	document.form2.change.value="Y";
}

function MoveSave() {
	if (document.form2.change.value!="Y") {
		alert("���������� ���� �ʾҽ��ϴ�.");
		return;
	}
	if (!confirm("������ ������� �����Ͻðڽ��ϱ�?")) return;
	codes = "";
	for (i=0;i<=(document.form1.est.length-1);i++) {
		codes+=","+document.form1.est.options[i].value;
	}
	document.form2.codes.value = codes+",";
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
			<? include ("menu_order.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �ֹ�/���� &gt; �ֹ���ȸ �� ��۰��� &gt; <span class="2depth_select">�ֹ�����Ʈ �������� ����</span></td>
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
					<TD><IMG SRC="images/order_excelinfo_title.gif" border="0"></TD>
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
					<TD width="100%" class="notice_blue">�ֹ�����Ʈ�� �������Ϸ� �ٿ�ε��� ���, �ֹ�����Ʈ�� �� �׸� �� �迭������ ������ �� �ֽ��ϴ�.</TD>
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
			<tr>
				<td height="20"></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/order_excelinfo_stitle1.gif" border="0"></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3></td>
			</tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=mode>
			<input type=hidden name=etccode>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=3 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="326" align="center"><b>�ٿ�ε� ������ �ֹ�����Ʈ �׸�</b></TD>
					<TD class="table_cell1" width="47" align="center">&nbsp;</TD>
					<TD class="table_cell1" width="337" align="center" background="images/blueline_bg.gif"><b><span class="font_blue">�ٿ�ε� �Ǵ� �ֹ�����Ʈ �׸�</span></b></TD>
				</TR>
				<TR>
					<TD colspan="3" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD width="346" align="center" valign="top" style="padding:8pt;"><select name=noest size=17 style="width:100%;" class="select">
<?
					for($i=0;$i<$cnt;$i++){
						if(!ereg(",".$i.",",$excel_info)){
							echo "<option value=\"".$i."\">".$excel_name[$i]."\n";
						}
					}
					echo "<option value=\"O".$blank_info."\">����(�� ��ĭ)\n";
?>
					</select></TD>
					<TD class="td_con1" width="55" align="center"><a href="javascript:SendMode('insert');"><img src="images/icon_nero1.gif" width="50" height="46" border="0" vspace="2"></a><br><br><a href="javascript:SendMode('delete');"><img src="images/icon_nero2.gif" width="50" height="46" border="0" vspace="2"></a></TD>
					<TD class="td_con1" width="345" align="center" valign="top">
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<TR>
						<TD>
						<table cellpadding="8" cellspacing="0" width="290" bgcolor="#ededed">
						<tr>
							<td width="286">
							<select name=est size=17 style="width:320px" class="select">
<?
							for($i=0;$i<$cnt2;$i++){
								echo "<option value=\"".$arexcel_info[$i]."\">".$excel_name[$arexcel_info[$i]]."\n";
							}
?>
							</select>
							</td>
						</tr>
						</table>
						</TD>
						<TD noWrap align=middle width=50 align="center"><a href="javascript:move('up');"><img src="images/code_up.gif" width="40" height="30" border="0" vspace="0"></a><br><img src="images/code_sort.gif" width="40" height="30" border="0" vspace="2"><br><a href="javascript:move('down');"><img src="images/code_down.gif" width="40" height="30" border="0" vspace="0"></a><br><br><a href="javascript:MoveSave();"><img src="images/code_save.gif" width="40" height="30" border="0" vspace="2"></a></TD>
					</TR>
					</TABLE>
					</TD>
				</TR>
				<TR>
					<TD colspan=3 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="40">* ������ǰ �ֹ����̳� �ֹ������� 1�� �̻��� ���, ���� �׸��� �ݺ� ����մϴ�.&nbsp;&nbsp;<input type=radio name=up_excel_ok value="Y" <?if($excel_ok=="Y")echo"checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none">��&nbsp;&nbsp;<input type=radio name=up_excel_ok value="N" <?if($excel_ok=="N")echo"checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none">�ƴϿ�</td>
			</tr>
			<TR>
				<TD  background="images/table_con_line.gif"></TD>
			</TR>
			<tr>
				<td align="center" height="10"></td>
			</tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm(document.form1);"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
			</tr>
			</form>
			<form name=form2 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=mode value="sequence">
			<input type=hidden name=codes>
			<input type=hidden name=change value="N">
			</form>
			<tr>
				<td height=20></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 height="45" ALT=""></TD>
					<TD><IMG SRC="images/manual_title.gif" WIDTH=113 height="45" ALT=""></TD>
					<TD width="100%" background="images/manual_bg.gif" height="35"></TD>
					<TD background="images/manual_bg.gif"></TD>
					<td background="images/manual_bg.gif"><IMG SRC="images/manual_top2.gif" WIDTH=18 height="45" ALT=""></td>
				</TR>
				<TR>
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">�ֹ�����Ʈ �������� ����</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top" style="letter-spacing:-0.5pt;">- �ֹ�����Ʈ ���� ����� ���ϴ� Ÿ������ �� �׸� �� �迭������ ������ �� [�����ϱ�] ��ư�� ���� �����մϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top" style="letter-spacing:-0.5pt;">- �ֹ�����Ʈ �׸��� [��ǰ��1-����-�ɼ�^��ǰ��2-����-�ɼ�] �׸�� [��ǰ��], [�ɼ�], [����], [����] �׸��� ���� ������ �Ұ����մϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top" style="letter-spacing:-0.5pt;">- ������ǰ �ֹ����̳� �ֹ������� 1�� �̻��� ���, ������ ������ �׸��� �ݺ� �������, �������� ������� �����մϴ�.</td>
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
			<tr>
				<td height="50"></td>
			</tr>
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