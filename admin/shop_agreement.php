<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "sh-1";
$MenuCode = "shop";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$type = $_POST["type"];
$up_agreement = $_POST["up_agreement"];
$up_agreement2 = $_POST["up_agreement2"];

if ($type == "up") {
	$sql = "SELECT COUNT(*) as cnt FROM tbldesign ";
	$result = mysql_query($sql,get_db_conn());
	$row = mysql_fetch_object($result);
	$flag = $row->cnt;
	mysql_free_result($result);

	if ($flag) {
		$onload = "<script> alert('���� ������ �Ϸ�Ǿ����ϴ�.'); </script>";
		$sql = "UPDATE tbldesign SET agreement = '".$up_agreement."' , agreement2 = '".$up_agreement2."' ";
	} else {
		$onload = "<script> alert('���� ����� �Ϸ�Ǿ����ϴ�.'); </script>";
		$sql = "INSERT tbldesign SET ";
		$sql.= "agreement	= '".$up_agreement."' , ";
		$sql.= "agreement2	= '".$up_agreement2."' ";
	}
	mysql_query($sql,get_db_conn());
}

$sql = "SELECT agreement, agreement2 FROM tbldesign ";
$result = mysql_query($sql,get_db_conn());
if ($row=mysql_fetch_object($result)) {
	$flag = true;
	$agreement = ($row->agreement=="<P>&nbsp;</P>"?"":$row->agreement);
	$agreement2 = ($row->agreement2=="<P>&nbsp;</P>"?"":$row->agreement2);
}
mysql_free_result($result);
if(strlen($agreement)==0 && file_exists($Dir.AdminDir."agreement.txt")) {
	$fp=fopen($Dir.AdminDir."agreement.txt", "r");
	$agreement=fread($fp,filesize($Dir.AdminDir."agreement.txt"));
	fclose($fp);
}
if(strlen($agreement2)==0 && file_exists($Dir.AdminDir."agreement2.txt")) {
	$fp=fopen($Dir.AdminDir."agreement2.txt", "r");
	$agreement2=fread($fp,filesize($Dir.AdminDir."agreement2.txt"));
	fclose($fp);
}
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="Javascript1.2" src="htmlarea/editor.js"></script>
<script>
_editor_url = "htmlarea/";
function CheckForm(){
	form1.type.value="up";
	form1.submit();
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ���� �⺻���� ���� &gt; <span class="2depth_select">���θ� �̿���</span></td>
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
					<TD><IMG SRC="images/shop_agreement_title.gif" ALT=""></TD>
				</TR>
				<TR>
					<TD width="100%" background="images/title_bg.gif" height="21"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="20"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_agreement_stitle1.gif" WIDTH="152" HEIGHT=31 ALT=""></TD>
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
					<TD><IMG SRC="images/distribute_01.gif" WIDTH=7 HEIGHT=7 ALT=""></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif" WIDTH=8 HEIGHT=7 ALT=""></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"><IMG SRC="images/distribute_04.gif" WIDTH=7 HEIGHT="4" ALT=""></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif"></TD>
					<TD width="100%" class="notice_blue">���θ� �̿����� �����մϴ�.</b></TD>
					<TD background="images/distribute_07.gif"><IMG SRC="images/distribute_07.gif" WIDTH=8 HEIGHT="4" ALT=""></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/distribute_08.gif" WIDTH=7 HEIGHT=8 ALT=""></TD>
					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
					<TD><IMG SRC="images/distribute_10.gif" WIDTH=8 HEIGHT=8 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td>�Ϲ�ȸ��</td>
				</tr>
				<tr>
					<td><textarea name=up_agreement rows=15 wrap=off style="width:100%" class="textarea"><?=$agreement?></textarea></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td height=3></td>
			</tr>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td>����ȸ��</td>
				</tr>
				<tr>
					<td><textarea name=up_agreement2 rows=15 wrap=off style="width:100%" class="textarea"><?=$agreement2?></textarea></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td height=3></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0 style="table-layout:fixed">
				<col width=7></col>
				<col width=></col>
				<col width=8></col>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue" valign="top">
					<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<TR>
						<TD class="notice_blue" valign="top"></TD>
						<TD width="100%" class="space"><span class=notice_blue>1) </span><span class="notice_blue"><B>[COMPANY]</B>, <B>[SHOP]</B>�� ȸ���� �������� �ڵ� �Էµ˴ϴ�.<br>2) �����ŷ�����ȸ ǥ�ؾ�� �ؼ��� ���մϴ�.</span></TD>
					</TR>
					</TABLE>
					</TD>
					<TD background="images/distribute_07.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/distribute_08.gif"></TD>
					<TD background="images/distribute_09.gif"></TD>
					<TD><IMG SRC="images/distribute_10.gif"></TD>
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
					<TD background="images/manual_left1.gif"><IMG SRC="images/manual_left1.gif" WIDTH=15 HEIGHT="5" ALT=""></TD>
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">�����ŷ�����ȸ ǥ�ؾ���ΰ� ���</span></td>
						<td width="109" rowspan="2" align="right"><img src="images/shop_agreement_img1.gif" align="bottom" width="90" height="44" border="0" vspace="5"></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td class="space_top">ǥ�ؾ���� û��öȸ, ȯ�Ҿ���� ���ؼ� ��ü������ ��õǾ� �ֽ��ϴ�.<br>
						ǥ�ؾ�� �ΰ�� �Һ��ڿ��� �̷��� ǥ�ؾ���� �ؼ����� �˸����� ���θ��� �ŷڼ��� ���� �� �ֽ��ϴ�.<br>
						ǥ�ؾ���� ����ϴ� ��쿡�� ǥ�ؾ���ΰ� ����� �� �ֽ��ϴ�.</td>
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
<script language="javascript1.2">
editor_generate('up_agreement');
</script>

<?=$onload?>

<? INCLUDE "copyright.php"; ?>