<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "sh-2";
$MenuCode = "shop";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$type=$_POST["type"];
$up_frame_type=$_POST["up_frame_type"];
$up_align_type=$_POST["up_align_type"];
$up_predit_type=$_POST["up_predit_type"];

if ($type=="up") {
	$sql = "UPDATE tblshopinfo SET ";
	$sql.= "frame_type		= '".$up_frame_type."', ";
	$sql.= "align_type		= '".$up_align_type."', ";
	$sql.= "predit_type		= '".$up_predit_type."' ";
	$result = mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload = "<script> alert('������ �Ϸ�Ǿ����ϴ�.'); </script>";
}

$sql = "SELECT frame_type, align_type, predit_type FROM tblshopinfo ";
$result = mysql_query($sql,get_db_conn());
if ($row=mysql_fetch_object($result)) {
	$frame_type= $row->frame_type;
	$align_type= $row->align_type;
	$predit_type = $row->predit_type;
}
mysql_free_result($result);

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script>
function CheckForm(){
	document.form1.type.value="up";
	document.form1.submit();
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ���θ� ȯ�� ���� &gt; <span class="2depth_select">������/���� ����</span></td>
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
					<TD><IMG SRC="images/shop_displaytype_title.gif" ALT=""></TD>
				</TR>
				</TR>
					<TD width="100%" background="images/title_bg.gif" HEIGHT=21></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3></td>
			</tr>
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
					<TD width="100%" class="notice_blue">������, ������ ���ļ���, ��ǰ���Է��� �������� ����� �ϰ� ������ �� �ֽ��ϴ�.</TD>
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
					<TD><IMG SRC="images/shop_displaytype_stitle1.gif"  ALT=""></TD>
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
					<TD background="images/distribute_04.gif"><IMG SRC="images/distribute_04.gif" ></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">1) <B>�������� Ÿ��(���+����������)</B> : ���θ� ������ �ּ� ���� �� ��ܸ޴� ����(���ΰ�ħ F5 - ���θ� �������� �̵�)<br>2) <B>�������� Ÿ��(�ּҰ���)</B> : ���θ� ������ �ּҰ� �׻� ���ε����θ����� ����(���ΰ�ħ F5 - ���θ� �������� �̵�)<br>3) <B>�������� Ÿ��(�ּҺ���)</B> : ���θ��� �� ������ �ּҸ� �״�� �����Ͽ� ǥ��(���ΰ�ħ F5 - ���� ������ ����</b>)</TD>
					<TD background="images/distribute_07.gif"><IMG SRC="images/distribute_07.gif" ></TD>
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
				<td height="6"></td>
			</tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD background="images/table_top_line.gif" width="248"></TD>
					<TD background="images/table_top_line.gif" width="512" colspan="2" ></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="228" align="center"><input type=radio id="idx_frame_type1" name=up_frame_type value="N" <? if($frame_type == "N") echo "checked ";?>> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_frame_type1>�������� Ÿ��(���+����������)</label></TD>
					<TD class="table_cell1" width="248" align="center"><input type=radio id="idx_frame_type2" name=up_frame_type value="Y" <? if($frame_type == "Y") echo "checked ";?>> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_frame_type2>�������� Ÿ��(�ּҰ���)</label></TD>
					<TD class="table_cell1" width="234" align="center"><input type=radio id="idx_frame_type3" name=up_frame_type value="A" <? if($frame_type == "A") echo "checked ";?>> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_frame_type3>�������� Ÿ��(�ּҺ���)</label></TD>
				</TR>
				<TR>
					<TD colspan="3" width="760" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD width="248" align="center">&nbsp;<img src="images/shop_framepage.gif" border="0"></TD>
					<TD class="td_con1" width="256" align="center"><img src="images/shop_noframepage.gif" border="0"></TD>
					<TD class="td_con1" width="242" align="center"><img src="images/shop_noframepage.gif" border="0"></TD>
				</TR>
				<TR>
					<TD background="images/table_top_line.gif" width="248"></TD>
					<TD background="images/table_top_line.gif" width="512" colspan="2"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="30"></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_displaytype_stitle2.gif"  ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"><IMG SRC="images/distribute_04.gif" ></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">1) �������Ӱ� ��� ������ ������ ��� ��ũ�ѹ� ������ ���θ��� ���°� ��߳� �� �ֽ��ϴ�.<br>2) �������ӿ� ��� ������ �Ͻǰ��� �����մϴ�.</TD>
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
				<td height=3></td>
			</tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD background="images/table_top_line.gif" width="383"><img src="images/table_top_line.gif"></TD>
					<TD background="images/table_top_line.gif" width="377" ></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="363" align="center"><input type=radio id="idx_align_type1" name=up_align_type value="N" <? if($align_type == "N") echo "checked ";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_align_type1>���� ����(�������� X, �������� O)</label></TD>
					<TD class="table_cell1" width="362" align="center"><input type=radio id="idx_align_type2" name=up_align_type value="Y" <? if($align_type == "Y") echo "checked ";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_align_type2>��� ����(�������� O, �������� O)</label></TD>
				</TR>
				<TR>
					<TD colspan="2" width="760" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD width="383" align="center"><img src="images/shop_alignleft.gif" border="0"></TD>
					<TD class="td_con1" width="370" align="center"><img src="images/shop_aligncenter.gif" border="0"> </TD>
				</TR>
				<TR>
					<TD background="images/table_top_line.gif" width="383"></TD>
					<TD background="images/table_top_line.gif" width="377"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="30"></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_displaytype_stitle3.gif"  ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3></td>
			</tr>
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
					<TD width="100%" class="notice_blue">1) ��ǰ��Ͻ� ������ �Է� Ÿ���� �������� �Ǵ� �̻���� �ϰ� ������ �� �ֽ��ϴ�.<br>2) �Է� Ÿ���� ������ ��� ���� �Է¸���� �޶��� �� �ֽ��ϴ�.</TD>
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
				<td height=3></td>
			</tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD background="images/table_top_line.gif" width="383"></TD>
					<TD background="images/table_top_line.gif" width="377" ></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="363" align="center"><input type=radio id="idx_predit_type1" name=up_predit_type value="Y" <? if($predit_type == "Y") echo "checked ";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_predit_type1>��������� �Է�(<b>����</b>)</label></TD>
					<TD class="table_cell1" width="362" align="center"><input type=radio id="idx_predit_type2" name=up_predit_type value="N" <? if($predit_type == "N") echo "checked ";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_predit_type2>�ܼ� �Է�â���� �Է�(���� HTML ���)</label></TD>
				</TR>
				<TR>
					<TD colspan="2" width="760" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD width="383" align="center">&nbsp;<img src="images/shop_detailediter.gif" border="0"></TD>
					<TD class="td_con1" width="370" align="center"> <img src="images/shop_detailhtml.gif" border="0"></TD>
				</TR>
				<TR>
					<TD background="images/table_top_line.gif" width="383"></TD>
					<TD background="images/table_top_line.gif" width="377"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=10></td>
			</tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm();"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
			</tr>
			</form>
			<tr>
				<td height=20></td>
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
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">ȭ�鼳���� �����Ӱ� ����</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">
						- �����Ӱ� �¿������� �����Ӱ� �����մϴ�.<br>
						- �������ӿ��� ������ �� �� ������������ ����� ��� �����¿� ������ ��Ȯ�� ��ġ���� ���� �� �ֽ��ϴ�.<br>
						- �¿������� �����ϸ� ���� �����ο� ��ȭ�� ���� �� �ֽ��ϴ�.<br>
						- ��ǰ�� Ư���̳� ���θ��� ��ȭ�� �� �� �¿����� �� �������� �����ϸ鼭 ����Ͻ� �� �ֽ��ϴ�.<br>
						</td>
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