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

$type=$_POST["type"];
$up_recom_ok=$_POST["up_recom_ok"];
$up_recom_addreserve=$_POST["up_recom_addreserve"];
$up_recom_memreserve_type=$_POST["up_recom_memreserve_type"];
$up_recom_memreserve=$_POST["up_recom_memreserve_$up_recom_memreserve_type"];
$up_recom_memreserve_chk=$_POST["up_recom_memreserve_chk"];
$up_recom_memreserve_chk2=$_POST["up_recom_memreserve_chk2"];

$orgMemRecommandReserve = $_POST["orgMemRecommandReserve"];
$orgMemRecommandReserveType1 = $_POST["orgMemRecommandReserveType1"];
$orgMemRecommandReserveType2 = $_POST["orgMemRecommandReserveType2"];
$newMemRecommandReserve = $_POST["newMemRecommandReserve"];

if($up_recom_memreserve_type =="A")
{
	$up_recom_memreserve_chk="";$up_recom_memreserve_chk2="";
}
$recom_memreserve_type = $up_recom_memreserve_type."".$up_recom_memreserve_chk."".$up_recom_memreserve_chk2;
$up_recom_limit=$_POST["up_recom_limit"];
$up_recom_url_ok=$_POST["up_recom_url_ok"];
if(!$up_recom_url_ok) $up_recom_url_ok="N";






// ���� ����
if ($type=="up") {
	$sql = "UPDATE tblshopinfo SET ";
	$sql.= "recom_ok			= '".$up_recom_ok."', ";
	$sql.= "recom_url_ok			= '".$up_recom_url_ok."', ";
	$sql.= "recom_memreserve	= '".$up_recom_memreserve."', ";
	$sql.= "recom_memreserve_type	= '".$recom_memreserve_type."', ";
	$sql.= "recom_addreserve	= '".$up_recom_addreserve."', ";
	if(strlen($up_recom_limit)==0) {
		$sql.= "recom_limit	= NULL ";
	} else {
		$sql.= "recom_limit	= '".$up_recom_limit."' ";
	}
	mysql_query($sql,get_db_conn());

	// ��õ�� ���� ���� ====================
	$arr = array();
	$arr['orgMemRecommandReserve'] = $orgMemRecommandReserve;
	$arr['orgMemRecommandReserveType1'] = $orgMemRecommandReserveType1;
	$arr['orgMemRecommandReserveType2'] = $orgMemRecommandReserveType2;
	$arr['newMemRecommandReserve'] = $newMemRecommandReserve;
	recommandSetting( $arr ); // ����


	$onload="<script>alert('��õ�� ���� ������ �Ϸ�Ǿ����ϴ�.');</script>\n";
} else{
	$orgMemRecommandReserve = 0; // ���� ��õ�� ȸ�� ������
	$orgMemRecommandType = "join"; // ���� ��õ�� ȸ�� ���� ���� Ÿ�� join : ����������� / orderA : ��ǰ���ſϷ� 1ȸ���� / ��ǰ���ſϷᶧ ���� ���� ����
	$newMemRecommandReserve = 0; // ��õ�޾� ������ ȸ�� ������

	$set = recommandSetting(); // ȣ��
	foreach( $set as $k => $v ) {
		${$k} = $v;
	}
}



$sql = "SELECT recom_ok,recom_url_ok,recom_memreserve,recom_memreserve_type,recom_addreserve,recom_limit FROM tblshopinfo ";
$result=mysql_query($sql,get_db_conn());
if ($row=mysql_fetch_object($result)) {
	$recom_ok=$row->recom_ok;
	$recom_url_ok=$row->recom_url_ok;
	$recom_memreserve=$row->recom_memreserve;
	$recom_memreserve_type=$row->recom_memreserve_type;
	$recom_addreserve=$row->recom_addreserve;
	$recom_limit=$row->recom_limit;
	$arRecomType = explode("",$recom_memreserve_type);
}
mysql_free_result($result);
${"check_recom_ok".$recom_ok} = "checked";








?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm() {
	if(isNaN(document.form1.up_recom_limit.value)){
		alert('��õ�� �ο� ���Ѽ��� ���ڸ� �Է� �����մϴ�.');
		document.form1.up_recom_limit.focus();
		return;
	}
	document.form1.type.value="up";
	document.form1.submit();
}
function rsvType(val){
	if(val =="A"){
		document.form1.up_recom_memreserve_B.value="";
		document.getElementById("up_recom_memreserve_A").disabled = false;
		document.getElementById("recom_typeB").style.display = "none";
	}else if(val =="B"){
		document.getElementById("up_recom_memreserve_A").disabled = true;
		document.getElementById("recom_typeB").style.display = "block";
	}
}
function set_RecomUrl(val){
	if(val =="N"){
		document.form1.up_recom_url_ok.disabled = true;
		document.form1.up_recom_url_ok.checked = false;
	}else if(val =="Y"){
		document.form1.up_recom_url_ok.disabled = false;
	}
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ���θ� � ���� &gt; <span class="2depth_select">��õ�� ���� ����</span></td>
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
					<TD><IMG SRC="images/shop_recommand_title.gif"  ALT=""></TD>
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
					<TD width="100%" class="notice_blue"><p>ȸ�������� ��õ�� ��õ�ο��� ���� ������ �ο��� �� �ֽ��ϴ�. Ÿ ���θ��� ����ȭ�Ǵ� ��õ�������� Ȱ���� ������.</p></TD>
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
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_recommand_stitle1.gif" WIDTH="151" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
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
					<TD width="100%" class="notice_blue">1) ��õ�� ���� ȸ�������������� ��õ�� �Է¶��� �ڵ� �����˴ϴ�.<br>2) ��õ�� ���� ������ ���� ��쿡�� ���ۿ��� �����ϱ� ���� <b>�Ǹ���������</b> �̿��� �����մϴ�.</TD>
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
			<tr><td height=3></td></tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">��õ�� ���뿩�� ����</TD>
					<TD class="td_con1">
						<span style="float:left;"><input type=radio id="idx_recom_ok2" name=up_recom_ok value="N" <?=$check_recom_okN?> onclick="set_RecomUrl('N')"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_recom_ok2>��õ�� ���Ұ�</label>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=radio id="idx_recom_ok1" name=up_recom_ok value="Y" <?=$check_recom_okY?>  onclick="set_RecomUrl('Y')"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_recom_ok1>��õ�� ���</label></span>
						<span id="hongbo_wrap" style="float:left;">(<input type=checkbox id="up_recom_url_ok" name=up_recom_url_ok value="Y" <?=($recom_url_ok=="Y")? "checked":""?> <?=($check_recom_okY)? "":"disabled"?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_recom_url_ok>ȸ������ ȫ��url ��ɻ��</label>)</span>
					</TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="30"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_recommand_stitle2.gif" WIDTH="151" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>





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
					<TD width="100%" class="notice_blue">
						1) My page > �����ݿ��� �߰��� ������ Ȯ�� �����մϴ�.<br>
						2) �������� ��� ���� �����÷��� 0������ ǥ�� �Ͻø� �˴ϴ�.<br>
						3) ���� ��õ�� ȸ���� ������� �ǰų� ��õ���� ȸ���� ��ǰ ���Ž� ����1ȸ �Ǵ� ���������� ���Žø��� �����˴ϴ�.<br>
						4) ��õ�޾� ������ ȸ���� ������� �����˴ϴ�.
					</TD>
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
			<tr><td height=3></td></tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR id="snsTypeWrap">
					<TD colspan="2">
					<table WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<col width="139">
					<col>
					<TR>
						<TD colspan="2"  background="images/table_con_line.gif"></TD>
					</TR>
					<TR>
						<TD class="table_cell" width="139px"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� ��õ�� ȸ��</TD>
						<TD class="td_con1" >
							<input name="orgMemRecommandReserve" value="<?=$orgMemRecommandReserve?>" size=10 maxlength=6 class="input" style="text-align:right;"> ��
							<select name="orgMemRecommandReserveType1" class="select" onChange="orgMemRecommandReserveType2.style.display=(this.value=='join')?'none':'inline';">
								<option value="join"<?=($orgMemRecommandType == "join")?" selected":""?>>������� ����</option>
								<option value="order"<?=($orgMemRecommandType == "orderA" OR $orgMemRecommandType == "orderB" )?" selected":""?>>��ǰ ���ſϷ�� ����</option>
							</select>
							<select name="orgMemRecommandReserveType2" class="select" style="display:<?=($orgMemRecommandType=="join"?"none":"inline")?>">
								<option value="orderA"<?=($orgMemRecommandType == "orderA" )?" selected":""?>>1ȸ����</option>
								<option value="orderB"<?=($orgMemRecommandType == "orderB" )?" selected":""?>>��������</option>
							</select>
						</TD>
					</TR>
					<TR>
						<TD colspan="2"  background="images/table_con_line.gif"></TD>
					</TR>
					<TR>
						<TD class="table_cell" width="139px"><img src="images/icon_point2.gif" width="8" height="11" border="0">��õ�޾� ������ ȸ��</TD>
						<TD class="td_con1">
							<input name="newMemRecommandReserve" value="<?=$newMemRecommandReserve?>" size=10 maxlength=6 class="input" style="text-align:right;"> �� (������� ����)
						</TD>
					</TR>


					</table>
					</TD>
				</TR>


				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="30"></td></tr>
















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
					<TD width="100%" class="notice_blue">1) 1�δ� ��õ���� ���� �� �� �ֽ��ϴ�.<br>2) ���ڸ� �Է����� ���� ��� ������ ��õ�� �����մϴ�.("0"�� ����) <b>�̻��ô� ��õ�� ���Ұ�</b> ������ ���ּ���.</TD>
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
			<tr><td height=3></td></tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">��õ ������ �ο�</TD>
					<TD class="td_con1"><input type=text name=up_recom_limit value="<?=$recom_limit?>" size=5 maxlength=4 class="input"> �� <span class="font_orange">* <b>��õ�� ������� ������ ���</b> ��õ ������ ȸ����</span></TD>
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
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;"class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">��õ�� ȸ���� Ż���� ���</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- ��õ�� �ϰ� Ż���ϴ��� ȸ������ ��� �߰��� �������� ȯ���� �ȵ˴ϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- ��õ���� ���� �������� �����ڰ� ������ �� ������ ȸ������ ��Ͽ� �����ϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- <a href="javascript:parent.topframe.GoMenu(3,'member_list.php');"><span class="font_blue">ȸ������ > ȸ���������� > ȸ����������</span></a> ���� ȸ���� �������� ������ �� �ֽ��ϴ�.</td>
					</tr>
					<tr>
						<td height="20" colspan="2"></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">��õ ������ �ο��� ����</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- ���� ȸ������ �����ǰ� �ִ� ��õ�� ȸ���� �������� �մϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- ��õ���ϰ� Ż���� ���� �����ο��� ���Ե��� �ʽ��ϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- ȸ�� Ż��� ������ ���� �� Ż��� ������ ������ ��õ���� ���� �������� �����Ͽ� ���������� ó���ϴµ� ���մϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- <a href="javascript:parent.topframe.GoMenu(1,'shop_member.php');"><span class="font_blue">�������� > ���θ� � ���� > ȸ������ ���� ����</span></a> ���� ȸ�� Ż�� ������ �� �ֽ��ϴ�.</td>
					</tr>
					<tr>
						<td height="20" colspan="2"></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">Ż���� ȸ���� ��õ �ο� ���ѿ� ���Խ�Ű�� �ʴ� ����</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- ������ �����ε� Ż���� �� �������� Ż���� ȸ������ �����ο��� ������ ���<br><b>&nbsp;&nbsp;</b>������ ��õ�� �� �� �� ���� ��찡 �߻��� �� �ֱ� ������ �����ο��� ���Խ�Ű�� �ʰ� �ֽ��ϴ�.</td>
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
<script type="text/javascript">
rsvType('<?=$arRecomType[0]?>');
</script>
<? INCLUDE "copyright.php"; ?>