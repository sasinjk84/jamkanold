<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "pr-5";
$MenuCode = "product";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$tmp_filter=explode("#",$_shopdata->filter);
$filter = $tmp_filter[0];
$arfilter = explode("=",$filter);
$review_filter = $tmp_filter[1];

$type=$_POST["type"];
$patten=(array)$_POST["patten"];
$replace=(array)$_POST["replace"];

if ($type=="update") {
	$filter="";
	for($i=0;$i<count($patten);$i++){
		if (strpos($patten[$i],"#")==true || strpos($patten[$i],"|")==true || strpos($patten[$i],"")==true || strpos($replace[$i],"#")==true || strpos($replace[$i],"|")==true || strpos($replace[$i],"")==true) {
			echo "<script language='javascript'>alert ('�Է��Ͻ� ������ ��|���� ��#���� �������ڰ� ���ԵǾ� ����� �Ұ����մϴ�.');location='".$_SERVER[PHP_SELF]."';</script>\n";
			exit;
		}
		if(strlen($patten[$i])>0) $filter.="=".$patten[$i]."=".$replace[$i];
	}
	$detail_filter=substr($filter,3)."#".$review_filter;
	$sql = "UPDATE tblshopinfo SET filter = '".$detail_filter."' ";
	$update = mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload="<script>alert('�ܾ� ���͸� ������ ����Ǿ����ϴ�.');</script>";

	$tmp_filter=explode("#",$detail_filter);
	$filter = $tmp_filter[0];
	$arfilter = explode("=",$filter);
} else if ($type=="delete") {
	$detail_filter="#".$review_filter;
	$sql = "UPDATE tblshopinfo SET filter = '".$detail_filter."' ";
	$update = mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload="<script>alert('�ܾ� ���͸� ��� ��ü�� �����Ͽ����ϴ�.');</script>";

	$tmp_filter=explode("#",$detail_filter);
	$filter = $tmp_filter[0];
	$arfilter = explode("=",$filter);
}
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm() {
	document.form1.type.value="update";
	document.form1.submit();
}

function Delete() {
	document.form1.type.value="delete";
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
			<? include ("menu_product.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ��ǰ���� &gt; ����ǰ/����/��Ÿ���� &gt; <span class="2depth_select">��ǰ�󼼳��� �ܾ� ���͸�</span></td>
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
					<TD><IMG SRC="images/product_detailfilter_title.gif" border="0"></TD>
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
					<TD width="100%" class="notice_blue">��ǰ���������� ��ǰ�󼼳����� �ܾ� ���͸��� ���� ����� �ִ� ����Դϴ�.</TD>
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
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<col width=364></col>
				<col width=></col>
				<col width=364></col>
				<TR>
					<TD background="images/table_top_line.gif" colspan="3"></TD>
				</TR>
				<TR align=center>
					<TD class="table_cell"><B>�˻� �ܾ�</B></TD>
					<TD class="table_cell1">&nbsp;</TD>
					<TD class="table_cell1"><B>������ �ܾ�</B></TD>
				</TR>
				<TR>
					<TD colspan="3" background="images/table_con_line.gif"></TD>
				</TR>
<?
				for($i=0;$i<20;$i++) {
					$str_class="lineleft";
					if ($i==19) $str_class="linebottomleft";
?>
					<tr>
						<TD class="td_con2"><input type=text name="patten[]" maxlength=40 value="<?=$arfilter[$i*2]?>" style="WIDTH: 99%" class=input></td>
						<TD class="td_con1"><NOBR><p align="center">&nbsp;<img src="images/btn_next1.gif" width="25" height="25" border="0"></td>
						<TD class="td_con1"><input type=text name="replace[]" maxlength=40 value="<?=$arfilter[$i*2+1]?>" style="WIDTH: 100%" class=input></td>
					</tr>
					<TR>
						<TD colspan="3" background="images/table_con_line.gif"></TD>
					</TR>
<?
				}
?>
				<TR>
					<TD background="images/table_top_line.gif" colspan="3"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=10></td></tr>																				
			<tr>
				<td align=center><a href="javascript:CheckForm();"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a>&nbsp;&nbsp;<a href="javascript:Delete();"><img src="images/btn_totaldel.gif" width="113" height="38" border="0" hspace="2"></a></td>
			</tr>
			</form>
			<tr><td height=20></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 height="45" ALT=""></TD>
					<TD><IMG SRC="images/manual_title.gif" WIDTH=113 height="45" ALT=""></TD>
					<TD width="100%" background="images/manual_bg.gif" height="35"></TD>
					<TD background="images/manual_bg.gif">&nbsp;</TD>
					<td background="images/manual_bg.gif"><IMG SRC="images/manual_top2.gif" WIDTH=18 height="45" ALT=""></td>
				</TR>
				<TR>
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<col width=20></col>
					<col width=></col>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">��ǰ�󼼳��� �ܾ� ���͸�</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- ���͸� ���� �ܾ��� ���� �ִ� 20�� ������ ���ѵǾ� �ֽ��ϴ�..</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- ���͸��� ��ǰ�󼼳����� �����Ͽ� ����ϴ°��� �ƴ� ��½ÿ��� ���͸��� ���ؼ� �ش� �ܾ �ٲ㼭 ��½�ŵ�ϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- �ܾ� �Է½� Ư�����ڴ� �Է����� ���ñ� �ٶ��ϴ�.</td>
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