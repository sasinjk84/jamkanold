<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

########################### TEST ���θ� Ȯ�� ##########################
DemoShopCheck("������������� �׽�Ʈ�� �Ұ��� �մϴ�.", "history.go(-1)");
#######################################################################

####################### ������ ���ٱ��� check ###############
$PageCode = "sh-2";
$MenuCode = "shop";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$type=$_POST["type"];
$adultused=$_POST["adultused"];
$adultauthid=$_POST["adultauthid"];
$adultauthpw=$_POST["adultauthpw"];
if($type=="up") {
	if(!preg_match("/^(Y|N)$/",$adultused)) {
		$adultused="N";
	}
	$adultauth=$adultused."=".$adultauthid."=".$adultauthpw;
	$_shopdata->adultauth=$adultauth;

	$sql = "UPDATE tblshopinfo SET adultauth='".$adultauth."' ";
	mysql_query($sql,get_db_conn());

	DeleteCache("tblshopinfo.cache");
	$onload = "<script> alert('���� ������ �Ϸ�Ǿ����ϴ�.'); </script>";
}

unset($adultused);
unset($adultauthid);
unset($adultauthpw);
if(strlen($_shopdata->adultauth)>0) {
	$tempadult=explode("=",$_shopdata->adultauth);
	$adultused=$tempadult[0];
	$adultauthid=$tempadult[1];
	$adultauthpw=$tempadult[2];
}

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script>
function CheckForm() {
	if(confirm("�Ǹ����� ������ �����Ͻðڽ��ϱ�?")) {
		document.form1.type.value="up";
		document.form1.submit();
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ���θ� ȯ�� ���� &gt; <span class="2depth_select">�Ǹ����� ���� ����</span></td>
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
					<TD><IMG SRC="images/shop_bizsiren_title.gif" ALT=""></TD>
					</tr>
<tr>
<TD width="100%" background="images/title_bg.gif" height="21"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=20></td></tr>
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
					<TD width="100%" class="notice_blue">1) <b>�Ǹ������� <span class=font_orange>����Ǹ����� ����</span>�� �����ϼž� ��� �����մϴ�.</b>
					<br>2) �Ǹ����� ���� ������ <b><span class=font_orange><A HREF="http://www.siren24.com" target="_blank">����ſ�������(siren24.com)</a></span></b>���� ���� �����մϴ�.
					<br>3) �ڼ��� ��� �� ���� ����� �Ʒ� �޴����� �� �����ϼ���.</TD>
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
				<td height=10></td>
			</tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<col width=140></col>
				<col width=></col>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0"><B>�Ǹ����� ��뿩��</B></TD>
					<TD class="td_con1">
					<input type=radio name=adultused value="Y" <?=($adultused=="Y"?"checked":"")?>>�����
					<img width=20 height=0>
					<input type=radio name=adultused value="N" <?=($adultused!="Y"?"checked":"")?>>������
					<br>
					<span class=font_orange>�� �Ǹ����� ���񽺸� ����ϰų� ������� �ʵ��� �����մϴ�.<br><img width=17 height=0>��, ����� ��� �Ǹ����� ���񽺿� ���ԵǾ� �־�� �մϴ�.</span>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0"><B>�Ǹ����� ����ID</B></TD>
					<TD class="td_con1">
					<input type=text name=adultauthid value="<?=$adultauthid?>" size=10 class="input_selected">
					<span class=font_orange>�� ����ſ�������(��)���� �߱� ���� ID�� ����ϼ���.</span>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0"><B>���� ��й�ȣ</B></TD>
					<TD class="td_con1">
					<input type=text name=adultauthpw value="<?=$adultauthpw?>" size=10 class="input_selected">
					<span class=font_orange>�� ����ſ�������(��)���� �߱� ���� ��й�ȣ�� ����ϼ���.</span>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
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
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">�Ǹ����� ���� ���� �� ���� ���</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td class="space_top">
						- �Ǹ����� ���� ������(<B>siren24.com</B>)�� ������ ȭ�鿡 �Ʒ��� ���� ������ ����ؾ��մϴ�.<br>
						- �Ǹ�Ȯ�� ������ ��� ��û���� �Ʒ��� ������(�ּ�) �߰�<br>
						<span class=font_orange>&nbsp;&nbsp;&nbsp;<b>http://<?=$_ShopInfo->getShopurl().FrontDir?>getnamecheck.php</b></span>

						<br><br>
						<span class=font_orange>�ؽǸ������� ���� ���� �Դϴ�. �ڼ��� ������ <A HREF="" target="_blank"><B>[�Ǹ����� �ȳ�]</B></A> �������� �����ϼ���.</span><br>
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