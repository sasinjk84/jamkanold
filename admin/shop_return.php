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
$up_return1_type=$_POST["up_return1_type"];
$up_return2_type=$_POST["up_return2_type"];
$up_ordercancel=$_POST["up_ordercancel"];
$up_nocancel_msg=$_POST["up_nocancel_msg"];
$up_okcancel_msg=$_POST["up_okcancel_msg"];

if ($type=="up") {
	$sql = "UPDATE tblshopinfo SET ";
	$sql.= "return1_type		= '".$up_return1_type."', ";
	$sql.= "return2_type		= '".$up_return2_type."', ";
	$sql.= "ordercancel			= '".$up_ordercancel."', ";
	$sql.= "nocancel_msg		= '".$up_nocancel_msg."', ";
	$sql.= "okcancel_msg		= '".$up_okcancel_msg."' ";
	$update = mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload = "<script> alert('��ǰ/ȯ�� ���� ������ �Ϸ�Ǿ����ϴ�.'); </script>";
}

$sql = "SELECT return1_type, return2_type, ordercancel, nocancel_msg, okcancel_msg FROM tblshopinfo ";
$result = mysql_query($sql,get_db_conn());
if ($row=mysql_fetch_object($result)) {
	$return1_type = $row->return1_type;
	$return2_type = $row->return2_type;
	$ordercancel = $row->ordercancel;
	$nocancel_msg = $row->nocancel_msg;
	$okcancel_msg = $row->okcancel_msg;
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ���θ� � ���� &gt; <span class="2depth_select">��ǰ ��ǰ/ȯ�� ��ɼ���</span></td>
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
			<tr>
				<td height="8"></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_return_title.gif"  border="0"></TD>
					</tr>
<tr>
<TD width="100%" background="images/title_bg.gif" height="21"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="3"></td>
			</tr>
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
					<TD width="100%" class="notice_blue"><p>��ȯ/��ǰ/ȯ�ҿ� ���� ������ �Ͻ� �� �ֽ��ϴ�.</p></TD>
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
					<TD><IMG SRC="images/shop_return_stitle1.gif" WIDTH="196" HEIGHT=31 ALT=""></TD>
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
					<TD width="100%" class="notice_blue">1) ���θ��� �̿�ȳ����������� <b>��ȯ/��ǰ/ȯ��</b> �ȳ� �κп� ������ ������ ǥ��˴ϴ�.<br>2) �̿�ȳ��� ���� �������� ���� ���ܵ˴ϴ�.</TD>
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
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD width="146" class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�� ������ ���</TD>
					<TD class="td_con1" >���� ���ɿ� ���� ��ȯ �� ��ǰ�� ��쿡 ��ۺ�� <select name="up_return2_type" class="select" style="width:100px">
						<option value="2" <? if ($return2_type=="2") echo "selected"; ?>>�Һ���</option>
						<option value="1" <? if ($return2_type=="1") echo "selected"; ?>>�Ǹ���</option>
						</select> �δ��Դϴ�.</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD width="146" class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ǰ �̻��� ���</TD>
					<TD class="td_con1" >��ǰ�� �̻� ���� ��ȯ �� ��ǰ�� ��쿡 ��ۺ�� <select name="up_return1_type" class="select" style="width:100px">
						<option value="2" <? if ($return1_type=="2") echo "selected"; ?>>�Һ���</option>
						<option value="1" <? if ($return1_type=="1") echo "selected"; ?>>�Ǹ���</option>
						</select> �δ��Դϴ�.</TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
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
					<TD><IMG SRC="images/shop_return_stitle2.gif" WIDTH="196" HEIGHT=31 ALT=""></TD>
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
					<TD width="100%" class="notice_blue">1) ���Ű��� �ֹ�����Ȯ�ο��� �ֹ���Ұ� ������ �ܰ踦 �����մϴ�.<br>2) ���� �ܰ� ���Ŀ��� �ֹ��� �󼼺��� �ϴܿ� <b>[�ֹ����]</b>��� �޴��� ��Ÿ���� �ʽ��ϴ�.</TD>
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
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD width="146" class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�ֹ���� ���� �ܰ�</TD>
					<TD class="td_con1" >���� �ֹ���Ҵ� ���θ����� <select name="up_ordercancel" class="select">
						<option value="0" <? if ($ordercancel=="0") echo "selected"; ?>>�ֹ� ��� �Ϸ�</option>
						<option value="2" <? if ($ordercancel=="2") echo "selected"; ?>>�ֹ� �߼� �غ�</option>
						<option value="1" <? if ($ordercancel=="1") echo "selected"; ?>>�ֹ� ���� �Ϸ�</option>
						</select> ������ �����մϴ�.</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD width="146" class="table_cell" valign="top"><img src="images/icon_point2.gif" width="8" height="11" border="0">�ֹ���ҽ�<br>&nbsp;&nbsp;���޼��� ����</TD>
					<TD class="td_con1" >
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="108">�ֹ���� �Ϸ��</td>
						<td ><input type=text name=up_okcancel_msg value="<?=$okcancel_msg?>" size=65 maxlength=250 onKeyDown="chkFieldMaxLen(250)" style="width:100%" class="input"></td>
					</tr>
					<tr>
						<td width="108">�ֹ���� �Ұ� �ܰ�</td>
						<td ><input type=text name=up_nocancel_msg value="<?=$nocancel_msg?>" size=65 maxlength=250 onKeyDown="chkFieldMaxLen(250)" style="width:100%" class="input"></td>
					</tr>
					</table>
					</TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
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
						<td ><b><span class="font_orange">�ֹ�����Ʈ�� �������� �� ó���ܰ��� Ȯ�� ����</b>���� �ϸ�, <b>�̹� �߼��� ���� �ڵ��ֹ���� �ȵ˴ϴ�.</b><br></span></td>
					</tr>
					<tr>
						<td width="20" align="right"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td  class="space_top">���Է½� �⺻ �޽��� ����<br></td>
					</tr>
					<tr>
						<td width="20" align="right"><p>&nbsp;</p></td>
						<td class="space_top"><p><b>��߼���</b></p></td>
					</tr>
					<tr>
						<td width="20" align="right"><p>&nbsp;</p></td>
						<td  class="space_top">&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/icon_point3.gif" width="7" height="12" border="0">�������&nbsp;: ���������� ��ҵǾ����ϴ�. �ֹ����� �� ȯ���� ���� �ȳ��� �帮�ڽ��ϴ�<br>&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/icon_point3.gif" width="7" height="12" border="0">��ҺҰ�&nbsp;: �ֹ����� �� ���, ȯ���� ���θ��� ���� ���� �ٶ��ϴ�.<br></td>
					</tr>
					<tr>
						<td width="20" align="right"><p>&nbsp;</p></td>
						<td  class="space_top"><p><b>��߼��غ���</b></p></td>
					</tr>
					<tr>
						<td width="20" align="right"><p>&nbsp;</p></td>
						<td class="space_top">&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/icon_point3.gif" width="7" height="12" border="0">�������&nbsp;: ���������� ��ҵǾ����ϴ�. �ֹ����� �� ȯ���� ���� �ȳ��� �帮�ڽ��ϴ�.<br>&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/icon_point3.gif" width="7" height="12" border="0">��ҺҰ�&nbsp;: �ֹ����� �� ���, ȯ���� ���θ��� ���� ���� �ٶ��ϴ�.&nbsp;<br></td>
					</tr>
					<tr>
						<td width="20" align="right"><p>&nbsp;</p></td>
						<td  class="space_top"><p><b>���Ա���</b></p></td>
					</tr>
					<tr>
						<td width="20" align="right"><p>&nbsp;</p></td>
						<td  class="space_top">&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/icon_point3.gif" width="7" height="12" border="0">�������&nbsp;: ���������� ��ҵǾ����ϴ�. �ֹ������� ���� �ȳ��� �帮�ڽ��ϴ�.<br>&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/icon_point3.gif" width="7" height="12" border="0">��ҺҰ�&nbsp;: �ֹ����� �� ��Ҵ� ���θ��� ���� ���� �ٶ��ϴ�.</td>
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